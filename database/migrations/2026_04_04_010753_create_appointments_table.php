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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('student_name');
            $table->string('student_id');
            
            // For example: "Academic Counseling" or "Personal Concerns"
            $table->string('purpose');
            
            $table->date('appointment_date');
            $table->time('appointment_time');
            
            // Using 'done' instead of 'completed' keeps it simple for the staff
            $table->enum('status', ['pending', 'approved', 'done'])->default('pending');
            
            $table->text('notes')->nullable();
            
            // Tracks which Guidance Counselor or Admin set the meeting
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};