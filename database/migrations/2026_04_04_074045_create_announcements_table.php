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
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('content');
            
            // Categorize announcements for easier filtering in the UI
            $table->enum('category', ['events', 'news', 'reminders'])->default('news');
            
            // Allows pinning important announcements to the top
            $table->boolean('is_featured')->default(false);
            
            // Tracks which admin/staff member posted the announcement
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('announcements');
    }
};