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
        Schema::create('timetables', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->foreignUuid('classmate_id')->nullable();
            $table->foreignUuid('module_id')->nullable();
            $table->jsonb('supervisors')->nullable();
            $table->datetime('start_time');
            $table->datetime('end_time');
            $table->foreignUuid('study_id')->nullable();
            $table->jsonb('studys')->nullable();
            $table->longText('description')->nullable();
            $table->char('code', 10)->nullable();
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
        Schema::dropIfExists('timetables');
    }
};
