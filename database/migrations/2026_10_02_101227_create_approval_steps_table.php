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
        Schema::create('approval_steps', function (Blueprint $table) {
            $table->id();

            $table->foreignId('document_id')
                ->constrained('documents')
                ->cascadeOnDelete();

            $table->unsignedInteger('step_order');

            $table->foreignId('approver_id')
                ->constrained('users')
                ->restrictOnDelete();

            $table->string('role');

            $table->string('status')->default('PENDING');

            $table->timestamp('acted_at')->nullable();

            $table->text('comment')->nullable();

            $table->timestamps();

            $table->unique(['document_id', 'step_order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('approval_steps');
    }
};