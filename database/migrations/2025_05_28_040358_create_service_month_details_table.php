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
        Schema::create('service_month_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('service_month_id');
            $table->foreignUuid('service_id');
            $table->string('status')->default('active'); // active, inactive, deleted
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
        Schema::dropIfExists('service_month_details');
    }
};
