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
        Schema::create('rating_scales', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('grade_letter');
            $table->decimal('min_score', 15, 2)->default(0);
            $table->decimal('max_score', 15, 2)->default(0);
            $table->longText('description')->nullable();
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
        Schema::dropIfExists('rating_scales');
    }
};
