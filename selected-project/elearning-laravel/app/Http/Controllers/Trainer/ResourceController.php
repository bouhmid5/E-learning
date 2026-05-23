<?php

namespace App\Http\Controllers\Trainer;

use App\Enums\TypeRessource;
use App\Http\Controllers\Controller;
use App\Http\Requests\Trainer\StoreResourceRequest;
use App\Http\Requests\Trainer\UpdateResourceRequest;
use App\Models\Lecon;
use App\Models\Ressource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class ResourceController extends Controller
{
    public function store(StoreResourceRequest $request, Lecon $lecon): RedirectResponse
    {
        Gate::authorize('update', $lecon->cours);

        $lecon->ressources()->create($this->payload($request->validated(), $request->file('fichier')));

        return redirect()->route('trainer.courses.show', $lecon->cours);
    }

    public function update(UpdateResourceRequest $request, Ressource $ressource): RedirectResponse
    {
        Gate::authorize('update', $ressource->lecon->cours);

        $payload = $this->payload($request->validated(), $request->file('fichier'), $ressource);
        $ressource->update($payload);

        return redirect()->route('trainer.courses.show', $ressource->lecon->cours);
    }

    public function destroy(Ressource $ressource): RedirectResponse
    {
        Gate::authorize('update', $ressource->lecon->cours);

        $cours = $ressource->lecon->cours;
        $this->deleteStoredFile($ressource);
        $ressource->delete();

        return redirect()->route('trainer.courses.show', $cours);
    }

    private function payload(array $validated, ?UploadedFile $file, ?Ressource $existing = null): array
    {
        $type = TypeRessource::from($validated['type']);
        $url = $validated['url'] ?? $existing?->url;
        $taille = $existing?->taille;

        if ($type !== TypeRessource::LIEN && ! $file && (! $existing || $existing->type === TypeRessource::LIEN)) {
            throw ValidationException::withMessages([
                'fichier' => 'Un fichier est requis pour les ressources document ou vidéo.',
            ]);
        }

        if ($type !== TypeRessource::LIEN && $file) {
            if ($existing) {
                $this->deleteStoredFile($existing);
            }

            $url = $file->store('ressources', 'public');
            $taille = $file->getSize();
        }

        if ($type === TypeRessource::LIEN) {
            if ($existing) {
                $this->deleteStoredFile($existing);
            }

            $taille = null;
        }

        return [
            'titre' => $validated['titre'],
            'type' => $type,
            'url' => $url,
            'ordre' => $validated['ordre'],
            'telechargeable' => (bool) ($validated['telechargeable'] ?? false),
            'taille' => $taille,
        ];
    }

    private function deleteStoredFile(Ressource $ressource): void
    {
        if ($ressource->type !== TypeRessource::LIEN && $ressource->url) {
            Storage::disk('public')->delete($ressource->url);
        }
    }
}
