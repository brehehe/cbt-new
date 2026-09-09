# DAFTAR FITUR EKSKLUSIF: ProCBT vs LiteCBT

Dokumen ini menjelaskan perbedaan fitur antara **LiteCBT** dan **ProCBT**, khususnya fitur yang hanya tersedia pada ProCBT.

Keduanya tetap digunakan untuk ujian yang dilaksanakan langsung di kampus (*on-site*). Perbedaan utamanya terletak pada **tingkat pengawasan kamera selama ujian**.

---

## 1. Gambaran Singkat

### LiteCBT (CBT Standar)
LiteCBT ditujukan untuk pelaksanaan ujian komputer yang membutuhkan fitur utama CBT, seperti:
*   Login peserta.
*   Penyajian soal (Pilihan ganda, pilihan ganda kompleks, esai, rumus/LaTeX).
*   Pengacakan soal dan pilihan jawaban.
*   Timer ujian.
*   Penyimpanan jawaban otomatis (*auto-save*).
*   *Auto-submit* saat waktu habis.
*   Penilaian otomatis dan rekap nilai.
*   Administrasi peserta dan ujian.
*   Cetak kartu ujian (dengan Barcode/QR) dan dokumen pendukung (daftar hadir, berita acara).
*   Analisis hasil dan butir soal ujian.

> **Catatan**: LiteCBT **tidak menggunakan kamera peserta** dan **tidak membutuhkan sistem monitoring video**.

---

### ProCBT (CBT + Pengawasan Kamera)
ProCBT memiliki seluruh kemampuan LiteCBT, kemudian menambahkan fitur untuk pengawasan peserta berbasis kamera selama ujian berlangsung.

Fokus tambahan ProCBT adalah:
1.  **Kamera peserta (Webcam)**.
2.  **Live monitoring video**.
3.  **Perekaman video kamera**.
4.  **Monitoring banyak peserta sekaligus (Grid View)**.
5.  **Snapshot foto peserta**.
6.  **Pesan peringatan langsung dari pengawas ke layar peserta**.
7.  **Penghentian sesi peserta oleh pengawas**.
8.  **Pemeriksaan kamera dan perangkat sebelum ujian dimulai**.

> Dengan demikian, ProCBT bukan sistem ujian yang berbeda, melainkan **versi LiteCBT yang dilengkapi modul pengawasan kamera**.

---

## 2. Fitur yang Hanya Ada di ProCBT

### 2.1 Kamera Peserta
*   **ProCBT**: Peserta menggunakan webcam selama ujian berlangsung. Kamera membantu pengawas melihat kondisi peserta secara langsung (apakah kamera aktif, apakah peserta berada di depan komputer, dsb).
*   **LiteCBT**: Tidak menggunakan kamera peserta. Peserta cukup menggunakan komputer untuk mengerjakan soal seperti biasa.

---

### 2.2 Live Monitoring Kamera
*   **ProCBT**: Video dari kamera peserta ditampilkan secara langsung (*real-time*) pada halaman pengawas. Pengawas dapat melihat peserta tanpa harus mendatangi meja satu per satu.
*   **LiteCBT**: Tidak memiliki *live camera monitoring*. Pengawas hanya melihat tabel informasi ujian (nama, nomor peserta, progres soal, sisa waktu, dan status online/offline).

---

### 2.3 Perekaman Kamera
*   **ProCBT**: Kamera peserta direkam secara bertahap selama ujian sebagai dokumentasi visual. Rekaman tersimpan di sistem dan dapat diperiksa kembali oleh pengawas/admin apabila terdapat hal yang mencurigakan.
*   **LiteCBT**: Tidak memiliki perekaman kamera sama sekali.

---

### 2.4 Monitoring Banyak Peserta (Grid View)
*   **ProCBT**: Pengawas memiliki halaman khusus berbentuk petak (*grid*) untuk memantau puluhan video kamera peserta sekaligus dalam satu layar.
    ```
    ┌─────────────┬─────────────┬─────────────┬─────────────┐
    │ Peserta 001 │ Peserta 002 │ Peserta 003 │ Peserta 004 │
    │   Camera    │   Camera    │   Camera    │   Camera    │
    ├─────────────┼─────────────┼─────────────┼─────────────┤
    │ Peserta 005 │ Peserta 006 │ Peserta 007 │ Peserta 008 │
    │   Camera    │   Camera    │   Camera    │   Camera    │
    └─────────────┴─────────────┴─────────────┴─────────────┘
    ```
*   **LiteCBT**: Tidak memiliki tampilan monitoring kamera.

---

### 2.5 Snapshot Peserta
*   **ProCBT**: Pengawas dapat mengambil foto *snapshot* dari kamera peserta sewaktu-waktu sebagai barang bukti visual (misal: peserta meninggalkan meja atau posisi tidak wajar).
*   **LiteCBT**: Tidak memiliki fitur snapshot kamera.

---

### 2.6 Peringatan Langsung dari Pengawas
*   **ProCBT**: Pengawas dapat mengirimkan pesan teks teguran langsung ke layar peserta tanpa harus mendatangi mejanya (contoh: *"Peringatan: Harap kembali fokus ke layar ujian"*).
*   **LiteCBT**: Tidak memiliki fitur komunikasi teguran langsung dari halaman monitoring pengawas ke layar peserta.

---

### 2.7 Penghentian Sesi Peserta
*   **ProCBT**: Pengawas atau administrator berwenang menghentikan paksa sesi ujian peserta (*force stop/terminate*) langsung dari halaman monitor apabila ditemukan pelanggaran serius.
*   **LiteCBT**: Pengawas tidak memiliki tombol penghentian sesi interaktif langsung dari panel monitor.

---

### 2.8 Pemeriksaan Kamera Sebelum Ujian
*   **ProCBT**: Sebelum masuk ke halaman soal, peserta wajib melewati tahap cek perangkat (kamera, izin browser, tampilan video) agar tidak terjadi kendala kamera saat ujian sudah dimulai.
*   **LiteCBT**: Tidak membutuhkan pemeriksaan kamera. Peserta bisa langsung masuk ke halaman soal ujian.

---

## 3. Fitur yang Sama pada LiteCBT dan ProCBT

Seluruh fitur inti ujian tetap tersedia lengkap pada kedua sistem:

| Fitur Ujian | 🟢 LiteCBT | 🔵 ProCBT |
| :--- | :---: | :---: |
| Penyajian soal pilihan ganda | ✅ | ✅ |
| Pilihan ganda kompleks | ✅ | ✅ |
| Soal Esai | ✅ | ✅ |
| Rumus matematika / sains (LaTeX) | ✅ | ✅ |
| Pengacakan soal | ✅ | ✅ |
| Pengacakan pilihan jawaban (A, B, C, D) | ✅ | ✅ |
| Timer ujian sinkron server | ✅ | ✅ |
| Auto-save jawaban | ✅ | ✅ |
| Auto-submit saat waktu habis | ✅ | ✅ |
| Pause ujian darurat | ✅ | ✅ |
| Tambahan waktu peserta tertentu | ✅ | ✅ |
| Cetak kartu ujian (Barcode / QR) | ✅ | ✅ |
| Cetak daftar hadir ujian | ✅ | ✅ |
| Cetak berita acara ujian | ✅ | ✅ |
| Rekap nilai ujian | ✅ | ✅ |
| Ekspor nilai ke Excel & PDF | ✅ | ✅ |
| Analisis butir soal (tingkat kesukaran & daya pembeda) | ✅ | ✅ |
| Analisis kelompok atas / bawah (27%) | ✅ | ✅ |
| Manajemen data peserta | ✅ | ✅ |
| Manajemen bank soal | ✅ | ✅ |
| Manajemen jadwal ujian | ✅ | ✅ |

---

## 4. Perbedaan Fitur Utama

Tabel pembeda cepat yang memisahkan kedua sistem:

| Fitur | 🟢 LiteCBT | 🔵 ProCBT |
| :--- | :---: | :---: |
| Ujian berbasis komputer | ✅ | ✅ |
| Kamera peserta aktif | ❌ | ✅ |
| Live camera streaming | ❌ | ✅ |
| Rekaman kamera otomatis | ❌ | ✅ |
| Monitoring banyak kamera (Grid) | ❌ | ✅ |
| Snapshot foto peserta oleh pengawas | ❌ | ✅ |
| Pesan teguran langsung ke layar peserta | ❌ | ✅ |
| Penghentian sesi dari meja pengawas | ❌ | ✅ |
| Pemeriksaan kamera sebelum ujian | ❌ | ✅ |
| Monitoring kondisi visual peserta | ❌ | ✅ |

---

## 5. Yang TIDAK ADA di Kedua Sistem (Dieliminasi)

Untuk memperjelas ruang lingkup produk, **baik LiteCBT maupun ProCBT TIDAK MENGGUNAKAN Kiosk Lockdown / Safe Exam Browser (SEB)**. 

Artinya, sistem **tidak melakukan penguncian sistem operasi komputer peserta**.

**Tidak ada fitur:**
*   ❌ Safe Exam Browser (SEB).
*   ❌ Kiosk Mode / Lock screen OS.
*   ❌ Mengunci Windows / macOS.
*   ❌ Memblokir tombol `Alt + Tab`.
*   ❌ Memblokir tombol `Windows`.
*   ❌ Mengunci kombinasi `Ctrl + Alt + Del`.
*   ❌ Memblokir aplikasi lain secara paksa.
*   ❌ Memblokir Flashdisk.
*   ❌ Memblokir monitor kedua.
*   ❌ Memaksa komputer hanya menjalankan aplikasi ujian.

Peserta tetap menggunakan komputer kampus seperti biasa dan membuka aplikasi ujian melalui web browser biasa (Google Chrome, Microsoft Edge, Mozilla Firefox, dll).

> **Poin Utama**: Pengawasan ProCBT murni dilakukan melalui **kamera, monitoring visual, rekaman video, dan tindakan pengawas**, bukan melalui penguncian sistem operasi komputer.

---

## 6. Kesimpulan

*   **LiteCBT**:
    *   **Konsep**: CBT standar.
    *   **Alur**: Datang ke kampus $\rightarrow$ Login di browser $\rightarrow$ Kerjakan soal $\rightarrow$ Jawaban tersimpan $\rightarrow$ Ujian selesai $\rightarrow$ Nilai keluar.
*   **ProCBT**:
    *   **Konsep**: CBT standar + Proctoring Pengawasan Kamera.
    *   **Alur**: Datang ke kampus $\rightarrow$ Buka browser $\rightarrow$ Cek kamera $\rightarrow$ Login $\rightarrow$ Kamera aktif $\rightarrow$ Ujian berlangsung $\rightarrow$ Pengawas memantau video grid & rekaman $\rightarrow$ Pengawas bisa menegur $\rightarrow$ Ujian selesai $\rightarrow$ Nilai dan rekaman tersimpan.

Keduanya tetap dirancang untuk **pelaksanaan ujian langsung di kampus (*on-site*) tanpa Kiosk Lockdown / Safe Exam Browser**.
