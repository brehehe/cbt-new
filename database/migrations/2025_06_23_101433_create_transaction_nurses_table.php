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
        Schema::create('transaction_nurses', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('transaction_id')->nullable()->comment('ID transaksi yang terkait dengan perawat ini');
            $table->foreignUuid('nurse_id')->nullable()->comment('ID perawat yang terkait dengan transaksi ini');
            $table->string('nurse_name')->nullable()->comment('Nama perawat yang terkait dengan transaksi ini');
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
        Schema::dropIfExists('transaction_nurses');
    }
};
