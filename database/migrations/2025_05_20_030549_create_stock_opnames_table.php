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
        Schema::create('stock_opnames', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code')->unique();  // Kode unik opname, wajib unik
            $table->date('date');
            $table->longText('description')->nullable();
            $table->foreignUuid('company_id')->nullable();
            $table->foreignUuid('user_id')->nullable(); // siapa yang buat opname
            $table->foreignUuid('branch_id')->nullable(); // lokasi/gudang opname
            $table->string('status')->default('draft'); // status opname
            $table->bigInteger('order')->default(0);
            $table->decimal('total_loss_value', 15, 2)->default(0);
            $table->decimal('total_excess_value', 15, 2)->default(0);
            $table->timestamp('approved_at')->nullable();
            $table->foreignUuid('approved_by')->nullable(); // user yang approve
            $table->boolean('is_process_finance')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_opnames');
    }
};
