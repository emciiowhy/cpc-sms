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
        Schema::create('counseling_records', function (Blueprint $table) {
            $table->id();
            $table->string('student_name');
            $table->string('student_id');
            $table->string('course')->nullable();
            $table->integer('year_level')->nullable();
            
            // Categorization of the visit
            $table->enum('category', ['academic', 'personal', 'behavioral', 'career'])->default('academic');
            
            // Content details
            $table->text('concern');
            $table->text('action_taken')->nullable();
            
            // Case Management Status
            $table->enum('status', ['open', 'ongoing', 'resolved'])->default('open');
            
            // Scheduling/Record Date
            $table->date('session_date');
            
            // Relationship to the Guidance Counselor/Admin who logged it
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('counseling_records');
    }
};