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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('nim')->nullable();
            $table->string('username')->nullable();
            $table->text('photo')->nullable();
            $table->char('phone', 15)->nullable();
            $table->jsonb('alternative_contacts')->nullable()->comment('Alternative emails/phones for different contexts');
            $table->timestamp('email_verified_at')->nullable();
            $table->jsonb('studys')->nullable()->comment('Array of study programs or departments');
            $table->foreignUuid('study_id')->nullable()->comment('Foreign key to studies table');
            $table->string('password');
            $table->longText('profile')->nullable();
            $table->foreignUuid('user_id')->nullable()->comment('User Referensi untuk relasi diri sendiri');
            $table->foreignUuid('company_id')->nullable();
            $table->bigInteger('order')->default(0);
            $table->enum('type_user', ['employee', 'patient'])->default('employee')->comment('Type of user: employee, or patient');
            $table->enum('type_study', ['default', 'mahasiswa', 'general'])->default('default')->comment('Type of study: default, mahasiswa, or general');
            $table->boolean('is_head')->default(true)->comment('Apakah role ini adalah kepala dari perusahaan atau tidak');
            $table->boolean('is_active')->default(true)->comment('Status aktif dari role ini');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignUuid('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
