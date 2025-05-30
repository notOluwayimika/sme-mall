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
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('section_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('parent_question_id')->nullable()->constrained('questions')->onDelete('cascade');
            $table->string('question');
            $table->enum('question_type', ['text', 'radio', 'checklist', 'leading']);
            $table->string('reference_code')->nullable()->index(); // For filtering by theme
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
