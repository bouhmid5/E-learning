<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('critere_corrections', function (Blueprint $table): void {
            $table->uuid('question_id')->nullable()->after('evaluation_id');

            $table->foreign('question_id', 'criteres_question_id_foreign')
                ->references('id')
                ->on('questions')
                ->restrictOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('critere_corrections', function (Blueprint $table): void {
            $table->dropForeign('criteres_question_id_foreign');
            $table->dropColumn('question_id');
        });
    }
};

