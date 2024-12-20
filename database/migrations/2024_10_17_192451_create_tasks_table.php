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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title')->max(255);
            $table->string('description')->max(255);
            $table->enum('type', ['bug', 'feature', 'improvement']);
            $table->enum('status', ['open', 'inProgress', 'completed', 'blocked']);
            $table->enum('priority', ['low', 'medium', 'high']);
            $table->date('due_date');
            $table->foreignId('assigned_to')->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
