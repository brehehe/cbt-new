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
        Schema::create('classmates', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->enum('type_study', ['default', 'mahasiswa', 'general'])->default('default')->comment('Type of study: default, mahasiswa, or general');
            $table->string('name');
            $table->foreignUuid('user_id')->nullable();
            $table->text('description')->nullable();
            $table->foreignUuid('company_id')->nullable();
            $table->bigInteger('order')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('classmates');
    }
};
