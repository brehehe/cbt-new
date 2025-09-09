# Dokumentasi Laporan Analisis Butir Soal

## Deskripsi
Fitur laporan analisis butir soal merupakan sistem analisis statistik untuk mengevaluasi kualitas soal-soal dalam ujian. Sistem ini mengimplementasikan rumus-rumus standar psikometri untuk menganalisis tingkat kesukaran, daya pembeda, dan reliabilitas setiap butir soal.

## Fitur Utama

### 1. Analisis Tingkat Kesukaran (Difficulty Index)
- **Rumus**: P = Jumlah peserta yang menjawab benar / Total peserta
- **Interpretasi**:
  - P < 0.3 = Soal Sukar
  - 0.3 ≤ P < 0.7 = Soal Sedang
  - P ≥ 0.7 = Soal Mudah

### 2. Analisis Daya Pembeda (Discrimination Index)
- **Metode**: Menggunakan kelompok 27% atas dan 27% bawah berdasarkan skor total
- **Rumus**: D = (Jumlah benar kelompok atas / Total kelompok atas) - (Jumlah benar kelompok bawah / Total kelompok bawah)
- **Interpretasi**:
  - D < 0.2 = Daya pembeda buruk
  - 0.2 ≤ D < 0.4 = Daya pembeda cukup/baik
  - D ≥ 0.4 = Daya pembeda sangat baik

### 3. Analisis Opsi Jawaban
- Distribusi pemilihan setiap opsi jawaban
- Persentase peserta yang memilih setiap opsi
- Identifikasi pengecoh (distractor) yang efektif

### 4. Kontribusi Reliabilitas
- **Rumus**: P × Q × D (dimana Q = 1 - P)
- Menunjukkan kontribusi setiap soal terhadap reliabilitas tes secara keseluruhan

## Struktur File

### Backend (PHP)
```
app/Livewire/Admin/Report/ItemAnalysis/Detail/AdminReportItemAnalysisDetailIndex.php
```
**Fungsi Utama:**
- `mount($id)`: Inisialisasi data timetable
- `loadTimetableData()`: Memuat data timetable, modul, dan soal
- `calculateItemAnalysis()`: Menghitung analisis untuk semua soal
- `analyzeItem($question)`: Analisis statistik per soal
- `calculateDiscrimination($question, $userScores)`: Menghitung daya pembeda
- `analyzeAnswerOptions($question, $userResponses)`: Analisis distribusi jawaban

### Frontend (Blade)
```
resources/views/livewire/admin/report/item-analysis/detail/admin-report-item-analysis-detail-index.blade.php
```

## Cara Menggunakan

### 1. Akses Laporan
- Login sebagai admin
- Pilih menu "Laporan" → "Analisis Butir Soal"
- Pilih ujian yang ingin dianalisis
- Klik "Lihat Detail"

### 2. Membaca Laporan

#### Dashboard Statistik
- Total peserta yang mengikuti ujian
- Total jumlah soal
- Durasi ujian
- Rata-rata tingkat kesukaran

#### Analisis Per Soal
Setiap soal menampilkan:
- **Statistik Utama**: Total peserta, jawaban benar/salah
- **Indeks Analisis**: Tingkat kesukaran, daya pembeda, kontribusi reliabilitas
- **Analisis Kelompok**: Perbandingan kelompok atas vs bawah
- **Analisis Opsi**: Distribusi pemilihan setiap opsi jawaban
- **Rekomendasi**: Saran perbaikan berdasarkan analisis

#### Ringkasan
- Jumlah soal berdasarkan tingkat kesukaran
- Jumlah soal dengan daya pembeda baik
- Statistik keseluruhan

## Implementasi Teknis

### Model yang Digunakan
- `Timetable`: Data ujian
- `TimetableModule`: Modul ujian
- `TimetableQuestion`: Soal-soal ujian
- `TimetableAnswer`: Opsi jawaban
- `UserTimetable`: Data peserta ujian
- `UserModuleQuestion`: Jawaban peserta per soal

### Algoritma Analisis

#### 1. Pengolahan Data Peserta
```php
// Ambil semua peserta yang sudah selesai ujian
$userTimetables = UserTimetable::where('timetable_id', $this->timetableId)
    ->where('status', 'done')
    ->with(['user', 'userModuleQuestions'])
    ->get();
```

#### 2. Perhitungan Tingkat Kesukaran
```php
$difficultyIndex = $totalParticipants > 0 ? $correctAnswers / $totalParticipants : 0;
```

#### 3. Perhitungan Daya Pembeda
```php
// Ambil 27% atas dan bawah
$groupSize = max(3, floor($totalUsers * 0.27));
$discriminationIndex = ($upperCorrect / $groupSize) - ($lowerCorrect / $groupSize);
```

#### 4. Analisis Opsi Jawaban
```php
foreach ($options as $option) {
    $selectedCount = $userResponses->where('timetable_answer_id', $option->id)->count();
    $percentage = $userResponses->count() > 0 ? ($selectedCount / $userResponses->count()) * 100 : 0;
}
```

## Keunggulan Sistem

### 1. Analisis Komprehensif
- Menggunakan rumus psikometri standar
- Analisis multi-dimensi (kesukaran, daya pembeda, reliabilitas)
- Visualisasi data yang informatif

### 2. Interface User-Friendly
- Dashboard statistik yang mudah dipahami
- Visualisasi dengan progress bar dan color coding
- Rekomendasi otomatis berdasarkan analisis

### 3. Fleksibilitas
- Dapat digunakan untuk berbagai jenis ujian
- Mendukung ujian dengan jumlah peserta bervariasi
- Adaptif terhadap jumlah soal

### 4. Export dan Print
- Fitur cetak laporan (print-friendly)
- Rencana export ke Excel
- Laporan dapat disimpan sebagai PDF

## Validasi dan Keamanan

### 1. Validasi Data
- Memastikan ujian sudah selesai (`status = 'done'`)
- Validasi minimal peserta untuk analisis daya pembeda
- Handle kasus data kosong atau tidak lengkap

### 2. Performance
- Query optimized dengan eager loading
- Penggunaan collection Laravel untuk perhitungan
- Caching untuk data yang sering diakses

## Rencana Pengembangan

### 1. Fitur Tambahan
- Export ke Excel/PDF
- Grafik tren analisis dari waktu ke waktu
- Perbandingan antar ujian
- Analisis item response theory (IRT)

### 2. Optimasi
- Caching hasil analisis
- Background processing untuk ujian besar
- API endpoint untuk integrasi eksternal

## Kesimpulan

Sistem analisis butir soal ini memberikan wawasan mendalam tentang kualitas soal ujian, membantu institusi pendidikan untuk:
- Meningkatkan kualitas soal ujian
- Mengidentifikasi soal yang perlu diperbaiki
- Memastikan reliabilitas dan validitas tes
- Membuat keputusan berbasis data dalam pengembangan instrumen penilaian

Sistem ini mengikuti standar psikometri internasional dan dapat diandalkan untuk evaluasi akademik yang objektif.
