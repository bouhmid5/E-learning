<?php

namespace App\Services\Evaluations;

use App\Enums\StatutSoumission;
use App\Enums\TypeQuestion;
use App\Models\CritereCorrection;
use App\Models\Evaluation;
use App\Models\Question;
use App\Models\SoumissionEvaluation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AutomaticCorrectionService
{
    public function submit(SoumissionEvaluation $soumission, array $answers): SoumissionEvaluation
    {
        return DB::transaction(function () use ($soumission, $answers): SoumissionEvaluation {
            $soumission->loadMissing('evaluation.questions.optionsReponse', 'evaluation.criteresCorrection');

            $score = 0.0;
            $feedback = [];

            foreach ($soumission->evaluation->questions as $question) {
                $value = $answers[$question->id] ?? null;
                [$correct, $points, $storedValue] = $this->correctQuestion($question, $value, $soumission->evaluation);

                $score += $points;
                $feedback[] = sprintf(
                    '%s: %s (%s/%s)',
                    Str::limit($question->enonce, 80, ''),
                    $correct ? 'correct' : 'incorrect',
                    $points,
                    $question->points
                );

                $soumission->reponsesCandidats()->updateOrCreate(
                    ['question_id' => $question->id],
                    [
                        'valeur' => $storedValue,
                        'est_correcte' => $correct,
                        'points_obtenus' => $points,
                    ]
                );
            }

            $passed = $score >= (float) $soumission->evaluation->seuil_reussite;

            $soumission->forceFill([
                'date_soumission' => now(),
                'score_obtenu' => $score,
                'reussi' => $passed,
                'statut' => $passed ? StatutSoumission::REUSSIE : StatutSoumission::ECHOUEE,
                'feedback_automatique' => implode("\n", $feedback),
            ])->save();

            return $soumission->refresh();
        });
    }

    private function correctQuestion(Question $question, mixed $value, Evaluation $evaluation): array
    {
        return match ($question->type) {
            TypeQuestion::QCM, TypeQuestion::VRAI_FAUX => $this->correctOptions($question, $value),
            TypeQuestion::NUMERIQUE => $this->correctNumeric($question, $value, $evaluation),
            TypeQuestion::REPONSE_COURTE => $this->correctShortAnswer($question, $value, $evaluation),
        };
    }

    private function correctOptions(Question $question, mixed $value): array
    {
        $selected = collect(is_array($value) ? $value : [$value])
            ->filter()
            ->map(fn ($id) => (string) $id)
            ->sort()
            ->values();

        $expected = $question->optionsReponse
            ->where('est_correcte', true)
            ->pluck('id')
            ->map(fn ($id) => (string) $id)
            ->sort()
            ->values();

        $correct = $selected->all() === $expected->all();

        return [$correct, $correct ? (float) $question->points : 0.0, $selected->implode(',')];
    }

    private function correctNumeric(Question $question, mixed $value, Evaluation $evaluation): array
    {
        $criterion = $this->criterionFor($question, $evaluation);
        $expected = $criterion?->valeur_attendue;
        $tolerance = (float) ($criterion?->tolerance ?? 0);

        $correct = is_numeric($value)
            && is_numeric($expected)
            && abs((float) $value - (float) $expected) <= $tolerance;

        return [$correct, $correct ? (float) $question->points : 0.0, (string) $value];
    }

    private function correctShortAnswer(Question $question, mixed $value, Evaluation $evaluation): array
    {
        $criterion = $this->criterionFor($question, $evaluation);
        $expected = $criterion?->valeur_attendue ?? '';
        $answer = (string) $value;

        $correct = $this->normalizeText($answer) === $this->normalizeText($expected);

        return [$correct, $correct ? (float) $question->points : 0.0, $answer];
    }

    private function criterionFor(Question $question, Evaluation $evaluation): ?CritereCorrection
    {
        /** @var Collection<int, CritereCorrection> $criteria */
        $criteria = $evaluation->criteresCorrection;

        return $criteria->firstWhere('question_id', $question->id)
            ?? $criteria->firstWhere('question_id', null);
    }

    private function normalizeText(string $value): string
    {
        return Str::of($value)
            ->lower()
            ->ascii()
            ->squish()
            ->toString();
    }
}

