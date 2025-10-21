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
        Schema::create('wajibs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('record_id')->constrained('records')->onDelete('cascade');
            $table->date('due_date')->nullable();
            $table->boolean('completed')->default(false);
            $table->string("mark")->nullable();
            $table->boolean('isRev')->default(false);
        
            $table->string("surat");
            $table->string("from_aya");
            $table->string("to_aya");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wajib');
    }
};
