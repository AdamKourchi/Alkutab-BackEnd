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
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->enum('exam_type', ['written', 'oral']);
            $table->string('title');
            $table->text('description');
            $table->boolean('is_final')->default(false);
            $table->integer('duration'); // in minutes
            $table->text('instructions')->nullable();
            //admin filled data
            $table->enum('status', ['submited', 'dated'])->default("submited");
            $table->dateTime('start_time')->nullable();
            $table->dateTime('end_time')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
