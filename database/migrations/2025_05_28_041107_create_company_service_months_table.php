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
        Schema::create('company_service_months', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_service_id');
            $table->foreignUuid('service_month_id');
            $table->timestamp('start_date');
            $table->integer('duration_days')->default(0);
            $table->timestamp('expires_at')->nullable(); // <-- Add expiration timestamp
            $table->boolean('is_lifetime')->default(false);
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
        Schema::dropIfExists('company_service_months');
    }
};
