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
        Schema::table('timetables', function (Blueprint $table) {
            if (! Schema::hasColumn('timetables', 'allow_repeat')) {
                $table->boolean('allow_repeat')->default(false)->after('is_simulation');
            }
            if (! Schema::hasColumn('timetables', 'require_token')) {
                $table->boolean('require_token')->default(true)->after('allow_repeat');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('timetables', function (Blueprint $table) {
            if (Schema::hasColumn('timetables', 'allow_repeat')) {
                $table->dropColumn('allow_repeat');
            }
            if (Schema::hasColumn('timetables', 'require_token')) {
                $table->dropColumn('require_token');
            }
        });
    }
};
