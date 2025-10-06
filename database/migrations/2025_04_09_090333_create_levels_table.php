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
        Schema::create('levels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('path_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->timestamp('start_at')->nullable();;
            $table->timestamp('end_at')->nullable();;
            $table->integer('duration_months')->nullable();
            $table->text('description')->nullable();
            $table->integer('order')->default(0); // For sorting
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('levels');
    }
};
