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
        Schema::table('questions', function (Blueprint $table) {
            $table->string('latex_preview_pdf')->nullable()->comment('latex preview pdf path');
            $table->string('latex_preview_png')->nullable()->comment('latex preview png path');
        });

        Schema::table('answers', function (Blueprint $table) {
            $table->string('latex_preview_pdf')->nullable()->comment('latex preview pdf path');
            $table->string('latex_preview_png')->nullable()->comment('latex preview png path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn(['latex_preview_pdf', 'latex_preview_png']);
        });

        Schema::table('answers', function (Blueprint $table) {
            $table->dropColumn(['latex_preview_pdf', 'latex_preview_png']);
        });
    }
};
