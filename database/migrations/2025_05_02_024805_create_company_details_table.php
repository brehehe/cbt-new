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
        Schema::create('company_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('company_id')->nullable();
            $table->string('one_health_code')->nullable()->comment('Kode satusehat');
            $table->string('facility_code')->nullable()->comment('Kode sarana by one health');
            $table->string('organization_id')->nullable()->comment('Nomor organisasi by one health');
            //Region
            $table->string('province_code')->nullable()->comment('Kode provinsi by one health');
            $table->string('province')->nullable();  // Provinsi
            $table->string('city_code')->nullable()->comment('Kode kabupaten by one health');
            $table->string('city')->nullable(); // Kota/Kabupaten
            $table->string('district_code')->nullable()->comment('Kode kecamatan by one health');
            $table->string('district')->nullable(); // Kecamatan
            $table->string('sub_district_code')->nullable()->comment('Kode kelurahan by one health');
            $table->string('sub_district')->nullable(); // Kelurahan
            $table->string('postal_code')->nullable();
            $table->longText('address')->nullable();
            $table->string('country')->default('ID');
            $table->string('rt', 5)->nullable()->comment('Kode RT by one health');
            $table->string('rw', 5)->nullable()->comment('Kode RW by one health');
            $table->string('longitude')->default(0)->comment('Kode longitude by one health');
            $table->string('latitude')->default(0)->comment('Kode latitude by one health');
            $table->string('altitude')->default(0)->comment('Kode altitude by one health');
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
        Schema::dropIfExists('company_details');
    }
};
