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
        Schema::create('digital_books', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id')->nullable();
            $table->foreignUuid('digital_book_category_id')->nullable()->constrained('digital_book_categories')->nullOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('content_type')->default('pdf')->comment('pdf, link, video');
            $table->string('file_path')->nullable();
            $table->text('external_url')->nullable();
            $table->string('cover_image')->nullable();
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
        Schema::dropIfExists('digital_books');
    }
};
