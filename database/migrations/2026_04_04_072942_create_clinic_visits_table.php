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
        Schema::create('clinic_visits', function (Blueprint $table) {
            $table->id();
            $table->string('student_name');
            $table->string('student_id');
            $table->string('complaint');
            $table->string('treatment')->nullable();
            
            // Status of the visit
            $table->enum('status', ['treated', 'referred', 'monitoring'])->default('treated');
            
            $table->date('visit_date');
            $table->time('visit_time');
            $table->text('notes')->nullable();
            
            // Relationship to the user who recorded the visit
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clinic_visits');
    }
};