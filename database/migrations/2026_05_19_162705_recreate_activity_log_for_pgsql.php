<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (config('database.default') !== 'pgsql') {
            return;
        }

        $tableName = config('activitylog.table_name', 'activity_log');
        $connection = config('activitylog.database_connection');

        Schema::connection($connection)->dropIfExists($tableName);

        Schema::connection($connection)->create($tableName, function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('log_name')->nullable();
            $table->text('description');

            $table->nullableUuidMorphs('subject');
            $table->nullableUuidMorphs('causer');

            $table->json('properties')->nullable();
            $table->string('event')->nullable();
            $table->uuid('batch_uuid')->nullable();

            $table->timestamps();
            $table->index('log_name');
        });
    }

    public function down(): void
    {
        $tableName = config('activitylog.table_name', 'activity_log');
        $connection = config('activitylog.database_connection');

        Schema::connection($connection)->dropIfExists($tableName);
    }
};