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
        Schema::create('one_health_practitiont_address_extensions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('one_health_practitiont_address_id')->nullable();
            $table->string('url')->comment('Source of the definition for the extension code - a logical name or a URL. value : province/city/district/village');
            $table->string('value_code')->comment('value of master data region');
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
        Schema::dropIfExists('one_health_practitiont_address_extensions');
    }
};
