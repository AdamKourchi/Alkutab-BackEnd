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
        Schema::create('paths', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->timestamp('start_at')->nullable();;
            $table->timestamp('end_at')->nullable();;
            $table->integer('duration_months')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->string('diploma_title')->nullable();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_hifd')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paths');
    }
};
