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
        Schema::create('enrollment_requests', function (Blueprint $table) {
            $table->id();
            $table->string('education_level'); /*  primary secondary high university other */
            $table->unsignedTinyInteger('age');
            $table->text('goal');
            $table->string('memorization_capability')->nullable(); 
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('path_id')->constrained()->onDelete('cascade');
            $table->enum('status', ['waiting', 'rejected', 'approved'])->default('waiting');
            $table->string('type'); 
            $table->boolean("memorize_quran")->default(false); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollment_requests');
    }
};
