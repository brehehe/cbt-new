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
        Schema::table('companies', function (Blueprint $table) {
            $table->boolean('seb_use_encryption')->default(false)->after('quit_password_seb');
            $table->string('seb_encryption_key')->nullable();
            $table->boolean('seb_show_taskbar')->default(true);
            $table->boolean('seb_show_reload_button')->default(true);
            $table->boolean('seb_show_time')->default(true);
            $table->boolean('seb_show_input_language')->default(true);
            $table->boolean('seb_allow_quit')->default(true);
            $table->boolean('seb_allow_spell_check')->default(false);
            $table->boolean('seb_enable_private_clipboard')->default(true);
            $table->string('seb_browser_exam_key')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn([
                'seb_use_encryption',
                'seb_encryption_key',
                'seb_show_taskbar',
                'seb_show_reload_button',
                'seb_show_time',
                'seb_show_input_language',
                'seb_allow_quit',
                'seb_allow_spell_check',
                'seb_enable_private_clipboard',
                'seb_browser_exam_key',
            ]);
        });
    }
};
