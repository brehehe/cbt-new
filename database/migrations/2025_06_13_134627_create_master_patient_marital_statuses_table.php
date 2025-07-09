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
        Schema::create('master_patient_marital_statuses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code');
            $table->string('display');
            $table->longText('definition')->nullable();
            $table->longText('comments')->nullable();
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
        Schema::dropIfExists('master_patient_marital_statuses');
    }
};
