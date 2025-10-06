<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('exam_submission_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_submission_id')->constrained()->onDelete('cascade');
            $table->foreignId('question_id')->constrained('exam_questions')->onDelete('cascade');

            // answer can be either string (text answer) or foreign key (MCQ option ID)
            $table->text('answer_text')->nullable(); // for freeform
            $table->foreignId('option_id')->nullable()->constrained('exam_question_options')->onDelete('cascade'); // for MCQ

            $table->boolean('is_correct')->nullable(); // optional, for grading
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_submission_answers');
    }
};
