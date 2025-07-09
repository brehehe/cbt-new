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
        Schema::create('history_stock_opname_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('product_id');
            $table->foreignUuid('stock_opname_item_id')->nullable();
            $table->foreignUuid('product_expired_date_id')->nullable(); // Untuk produk yang memiliki tanggal kadaluarsa
            $table->bigInteger('quantity')->default(0); // Stok fisik (hasil opname)
            $table->bigInteger('quantity_system')->default(0); // Stok sistem (di database)
            $table->bigInteger('quantity_difference')->default(0); // Selisih = quantity - quantity_system
            $table->decimal('hpp_average', 15, 2)->default(0); // Harga Pokok Penjualan rata-rata per unit
            $table->decimal('loss_value', 15, 2)->default(0); // Nilai kerugian jika ada selisih negatif
            $table->decimal('excess_value', 15, 2)->default(0); // Nilai kelebihan jika ada selisih positif
            $table->longText('description')->nullable(); // Keterangan opname (barang rusak, hilang, dll)
            $table->foreignUuid('branch_id')->nullable();
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
        Schema::dropIfExists('history_stock_opname_items');
    }
};
