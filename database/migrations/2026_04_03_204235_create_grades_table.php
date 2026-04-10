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
        Schema::create('grades', function (Blueprint $table) {
            $table->id();
            $table->string('student_name');
            $table->string('student_id');
            $table->string('subject');
            $table->string('course')->nullable();
            $table->integer('year_level')->nullable();
            
            // Decimal allows for precision (e.g., 95.25)
            $table->decimal('midterm', 5, 2)->default(0);
            $table->decimal('finals', 5, 2)->default(0);
            $table->decimal('average', 5, 2)->default(0);
            
            $table->enum('remarks', ['pass', 'fail'])->default('pass');
            
            // Links the grade to the Registrar or Admin who encoded it
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grades');
    }
};