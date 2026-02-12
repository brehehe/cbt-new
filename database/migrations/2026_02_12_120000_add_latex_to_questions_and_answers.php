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
            $table->longText('latex')->nullable()->comment('latex source for question');
        });

        Schema::table('answers', function (Blueprint $table) {
            $table->longText('latex')->nullable()->comment('latex source for answer');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            $table->dropColumn('latex');
        });

        Schema::table('answers', function (Blueprint $table) {
            $table->dropColumn('latex');
        });
    }
};
