<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignUuid('company_id')->nullable()->constrained('companies')->onDelete('set null');

            // Data Pribadi Umum
            $table->string('employee_id')->nullable()->comment('ID pegawai/NIP untuk dosen atau NIM untuk mahasiswa');
            $table->string('student_id')->nullable()->comment('NIM khusus untuk mahasiswa');
            $table->string('lecturer_id')->nullable()->comment('NIDN/NIP khusus untuk dosen');
            $table->longText('address')->nullable()->comment('Alamat lengkap');
            $table->string('postal_code')->nullable()->comment('Kode pos');
            $table->string('city')->nullable()->comment('Kota');
            $table->string('province')->nullable()->comment('Provinsi');
            $table->string('country')->default('ID')->comment('Kode negara');
            $table->string('phone')->nullable()->comment('Nomor telepon');
            $table->string('mobile_phone')->nullable()->comment('Nomor HP');
            $table->string('emergency_contact_name')->nullable()->comment('Nama kontak darurat');
            $table->string('emergency_contact_phone')->nullable()->comment('Nomor kontak darurat');
            $table->string('emergency_contact_relation')->nullable()->comment('Hubungan dengan kontak darurat');

            // Data Identitas
            $table->string('identity_type')->nullable()->comment('Jenis identitas (KTP, Passport, dll)');
            $table->string('identity_number')->nullable()->comment('Nomor identitas');
            $table->longText('identity_card_path')->nullable()->comment('Path file foto kartu identitas');
            $table->string('blood_group')->nullable()->comment('Golongan darah (A, B, AB, O)');
            $table->enum('gender', ['male', 'female'])->nullable()->comment('Jenis kelamin');
            $table->string('religion')->nullable()->comment('Agama');
            $table->string('nationality')->default('Indonesian')->comment('Kewarganegaraan');
            $table->date('birth_date')->nullable()->comment('Tanggal lahir');
            $table->string('birth_place')->nullable()->comment('Tempat lahir');
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed'])->nullable()->comment('Status pernikahan');

            // Data Akademik untuk Mahasiswa
            $table->string('nim')->nullable()->comment('Nomor Induk Mahasiswa');
            $table->string('student_program')->nullable()->comment('Program studi untuk mahasiswa');
            $table->string('student_faculty')->nullable()->comment('Fakultas untuk mahasiswa');
            $table->string('student_department')->nullable()->comment('Jurusan untuk mahasiswa');
            $table->string('student_class')->nullable()->comment('Peserta untuk mahasiswa');
            $table->string('student_semester')->nullable()->comment('Semester untuk mahasiswa');
            $table->string('student_academic_year')->nullable()->comment('Tahun akademik');
            $table->enum('student_status', ['active', 'graduate', 'dropout', 'transfer', 'leave'])->nullable()->comment('Status mahasiswa');
            $table->enum('student_type', ['mb', 'm'])->default('mb')->comment('Tipe mahasiswa batu atau mahasiswa');
            $table->decimal('student_gpa', 3, 2)->nullable()->comment('IPK mahasiswa');
            $table->string('student_advisor_id')->nullable()->comment('ID dosen pembimbing');
            $table->date('student_entry_date')->nullable()->comment('Tanggal masuk mahasiswa');
            $table->date('student_graduation_date')->nullable()->comment('Tanggal lulus mahasiswa');

            // Data Akademik untuk Dosen
            $table->string('lecturer_nidn')->nullable()->comment('NIDN dosen');
            $table->string('lecturer_nip')->nullable()->comment('NIP dosen');
            $table->string('lecturer_department')->nullable()->comment('Departemen dosen');
            $table->string('lecturer_faculty')->nullable()->comment('Fakultas dosen');
            $table->string('lecturer_position')->nullable()->comment('Jabatan akademik (Asisten Ahli, Lektor, dll)');
            $table->string('lecturer_functional_position')->nullable()->comment('Jabatan fungsional');
            $table->string('lecturer_education_level')->nullable()->comment('Tingkat pendidikan (S1, S2, S3)');
            $table->string('lecturer_specialization')->nullable()->comment('Bidang keahlian');
            $table->string('lecturer_expertise')->nullable()->comment('Kepakaran');
            $table->enum('lecturer_status', ['active', 'inactive', 'retired', 'leave'])->nullable()->comment('Status dosen');
            $table->enum('lecturer_type', ['full_time', 'part_time', 'contract', 'visiting'])->nullable()->comment('Tipe dosen');
            $table->date('lecturer_start_date')->nullable()->comment('Tanggal mulai mengajar');
            $table->date('lecturer_retirement_date')->nullable()->comment('Tanggal pensiun');

            // Data Khusus untuk Pengawas/Supervisor
            $table->string('supervisor_id')->nullable()->comment('ID khusus untuk pengawas');
            $table->string('supervisor_nip')->nullable()->comment('NIP pengawas');
            $table->string('supervisor_department')->nullable()->comment('Departemen pengawas');
            $table->string('supervisor_unit')->nullable()->comment('Unit kerja pengawas');
            $table->string('supervisor_position')->nullable()->comment('Jabatan pengawas');
            $table->string('supervisor_level')->nullable()->comment('Level pengawas (Junior, Senior, Lead, Principal)');
            $table->string('supervisor_area')->nullable()->comment('Area pengawasan (Academic, Administrative, Technical, General)');
            $table->string('supervisor_specialization')->nullable()->comment('Spesialisasi pengawasan');
            $table->enum('supervisor_status', ['active', 'inactive', 'leave', 'retired'])->nullable()->comment('Status pengawas');
            $table->enum('supervisor_type', ['internal', 'external', 'contract'])->nullable()->comment('Tipe pengawas');
            $table->date('supervisor_start_date')->nullable()->comment('Tanggal mulai bertugas sebagai pengawas');
            $table->integer('supervisor_experience_years')->nullable()->comment('Pengalaman sebagai pengawas (tahun)');
            $table->json('supervisor_certifications')->nullable()->comment('Sertifikasi pengawasan (JSON array)');

            // Data Sertifikasi dan Lisensi
            $table->json('certifications')->nullable()->comment('Sertifikasi yang dimiliki (JSON array)');
            $table->json('licenses')->nullable()->comment('Lisensi yang dimiliki (JSON array)');
            $table->json('training_history')->nullable()->comment('Riwayat pelatihan (JSON array)');
            $table->json('awards')->nullable()->comment('Penghargaan yang diterima (JSON array)');

            // Data CBT/Ujian
            $table->string('exam_preference')->nullable()->comment('Preferensi ujian');
            $table->boolean('special_needs')->default(false)->comment('Memerlukan akomodasi khusus');
            $table->text('special_needs_description')->nullable()->comment('Deskripsi kebutuhan khusus');
            $table->json('exam_history')->nullable()->comment('Riwayat ujian (JSON array)');
            $table->integer('total_exams_taken')->default(0)->comment('Total ujian yang telah diambil');
            $table->decimal('average_score', 5, 2)->nullable()->comment('Rata-rata nilai ujian');
            $table->text('krs_file')->nullable()->comment('Upload KRS mahasiswa');
            $table->text('payment_registration')->nullable()->comment('Upload pembayaran ');

            // Data Teknis
            $table->string('preferred_language')->default('id')->comment('Bahasa yang disukai');
            $table->json('system_preferences')->nullable()->comment('Preferensi sistem (JSON)');
            $table->timestamp('last_login_at')->nullable()->comment('Waktu login terakhir');
            $table->string('last_login_ip')->nullable()->comment('IP login terakhir');
            $table->text('notes')->nullable()->comment('Catatan tambahan');

            // Status dan Approval
            $table->enum('verification_status', ['pending', 'verified', 'rejected'])->default('pending')->comment('Status verifikasi data');
            $table->timestamp('verified_at')->nullable()->comment('Waktu verifikasi');
            $table->foreignUuid('verified_by')->nullable()->constrained('users')->comment('Diverifikasi oleh');
            $table->enum('status', ['active', 'inactive', 'suspended', 'blocked'])->default('active')->comment('Status akun');

            // Metadata
            $table->bigInteger('order')->default(0)->comment('Urutan tampilan');
            $table->json('metadata')->nullable()->comment('Data tambahan dalam format JSON');
            $table->softDeletes();
            $table->timestamps();

            // Indexes untuk performa
            $table->index(['user_id', 'company_id']);
            $table->index(['student_id']);
            $table->index(['lecturer_id']);
            $table->index(['supervisor_id']);
            $table->index(['employee_id']);
            $table->index(['verification_status']);
            $table->index(['status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_details');
    }
};
