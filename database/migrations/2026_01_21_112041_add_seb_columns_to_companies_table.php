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
            $table->string('seb_encryption_key')->nullable()->after('seb_use_encryption');
            $table->boolean('seb_show_taskbar')->default(true)->after('seb_encryption_key');
            $table->boolean('seb_show_reload_button')->default(true)->after('seb_show_taskbar');
            $table->boolean('seb_show_time')->default(true)->after('seb_show_reload_button');
            $table->boolean('seb_show_input_language')->default(true)->after('seb_show_time');
            $table->boolean('seb_allow_quit')->default(true)->after('seb_show_input_language');
            $table->boolean('seb_allow_spell_check')->default(false)->after('seb_allow_quit');
            $table->boolean('seb_enable_private_clipboard')->default(true)->after('seb_allow_spell_check');
            $table->string('seb_browser_exam_key')->nullable()->after('seb_enable_private_clipboard');
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
