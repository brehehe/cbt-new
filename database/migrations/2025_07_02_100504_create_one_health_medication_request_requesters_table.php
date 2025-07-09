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
        Schema::create('one_health_medication_request_requesters', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('one_health_medication_request_id')->nullable();
            $table->uuidMorphs('requestable');
            $table->string('reference');
            $table->string('reference_id');
            $table->string('display');
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
        Schema::dropIfExists('one_health_medication_request_requesters');
    }
};
