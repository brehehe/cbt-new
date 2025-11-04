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
        Schema::create('company_services', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id');
            $table->foreignUuid('service_month_id');
            $table->timestamp('start_date');
            $table->timestamp('expires_at')->nullable(); // <-- Add expiration timestamp
            $table->integer('duration_days')->default(0);
            $table->bigInteger('order')->default(0);
            $table->boolean('is_lifetime')->default(false);
            $table->boolean('is_active')->default(true); // Status of the service (active, inactive, etc.)
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_services');
    }
};
