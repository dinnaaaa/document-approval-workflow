<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('document_revisions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('document_id')
                ->constrained('documents')
                ->cascadeOnDelete();

            $table->unsignedInteger('version');

            $table->foreignId('created_by')
                ->constrained('users')
                ->restrictOnDelete();

            $table->text('description')->nullable();

            $table->timestamps();

            $table->unique(['document_id', 'version']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_revisions');
    }
};