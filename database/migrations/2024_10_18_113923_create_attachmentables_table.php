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
        Schema::create('attachmentables', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attachment_id')->constrained();
            $table->morphs('attachmentable'); // This will create attachmentable_id and attachmentable_type
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachmentables');
    }
};
