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
        Schema::create('organization_members', function (Blueprint $table) {
            $table->id();
            
            // Link to the organizations table
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            
            $table->string('student_name');
            $table->string('student_id');
            
            // Specific roles within the organization
            $table->enum('role', [
                'president', 
                'vice_president', 
                'secretary', 
                'treasurer', 
                'member'
            ])->default('member');
            
            $table->string('course')->nullable();
            $table->integer('year_level')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organization_members');
    }
};