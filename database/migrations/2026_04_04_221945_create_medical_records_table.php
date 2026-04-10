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
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->string('student_name');
            $table->string('student_id');
            $table->string('course')->nullable();
            $table->integer('year_level')->nullable();
            
            // Health Details
            $table->text('medical_history')->nullable();
            $table->text('allergies')->nullable();
            $table->string('blood_type')->nullable();
            
            // Measurements (5 total digits, 2 after decimal. e.g., 180.25)
            $table->decimal('height', 5, 2)->nullable();
            $table->decimal('weight', 5, 2)->nullable();
            
            // Status toggle for clearance
            $table->enum('status', ['fit', 'not_fit'])->default('fit');
            
            // Path to uploaded medical certificates or lab results
            $table->string('attachment')->nullable();
            
            // Track the clinic staff who created the record
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};