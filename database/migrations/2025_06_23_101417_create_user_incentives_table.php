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
        Schema::create('user_incentives', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id');
            $table->foreignUuid('transaction_id')->nullable()->comment('ID transaksi yang terkait dengan insentif ini');
            $table->decimal('amount', 15, 2)->nullable()->comment('Jumlah insentif, bisa kosong jika tidak ada insentif');
            $table->string('month')->nullable()->comment('Bulan insentif, bisa kosong jika tidak ada insentif');
            $table->string('year')->nullable()->comment('Tahun insentif, bisa kosong jika tidak ada insentif');
            $table->enum('status',['dokter','perawat','apoteker','kasir'])->default('perawat')->comment('Status perawat dalam transaksi ini');
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
        Schema::dropIfExists('user_incentives');
    }
};
