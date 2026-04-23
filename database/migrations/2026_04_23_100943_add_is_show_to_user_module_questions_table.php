<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * is_show = true  → tampil di item analisis (default)
     * is_show = false → disembunyikan dari item analisis,
     *                   tapi history ujian siswa tetap utuh
     */
    public function up(): void
    {
        Schema::table('user_module_questions', function (Blueprint $table) {
            $table->boolean('is_show')->default(true)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('user_module_questions', function (Blueprint $table) {
            $table->dropColumn('is_show');
        });
    }
};
