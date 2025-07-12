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
        Schema::create('exam_alerts', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('timetable_id')->nullable();
            $table->foreignUuid('user_timetable_id');
            $table->enum('alert_type', ['right_click', 'dev_tools', 'view_source', 'alt_tab', 'ctrl_tab', 'copy_paste', 'tab_switch', 'window_blur', 'fullscreen_exit', 'camera_error', 'page_reload']);
            $table->text('description');
            $table->json('metadata')->nullable(); // untuk menyimpan data tambahan
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
        Schema::dropIfExists('exam_alerts');
    }
};
