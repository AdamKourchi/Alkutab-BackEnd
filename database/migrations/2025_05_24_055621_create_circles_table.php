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
        Schema::create('circles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('path_id')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->json('days_of_week'); // Store array of tiny integers, e.g. [0,1,2] for Sun, Mon, Tue
            $table->timestamp('start_time');
            $table->timestamp('end_time');
            $table->enum('status', ['online', 'offline'])->default('offline');
            $table->string("link")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('circles');
    }
};
