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
        Schema::create('task_dependencies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained('tasks')->onDelete('cascade'); // المهمة التي تعتمد
            $table->foreignId('depends_on_task_id')->constrained('tasks')->onDelete('cascade'); // المهمة التي يجب أن تكتمل أولاً
            $table->timestamps();

            // إضافة فهرس فريد لتجنب التكرار بين التبعيات
            $table->unique(['task_id', 'depends_on_task_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('task_dependencies');
    }
};