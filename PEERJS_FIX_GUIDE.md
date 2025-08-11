# Solusi Masalah PeerJS di Admin Master Timetable Streaming

## Masalah yang Ditemukan:
**Tidak ada session ujian aktif (Active Sessions: 0)**

## Penyebab:
1. Belum ada mahasiswa yang memulai ujian
2. Session ujian telah berakhir atau dihentikan
3. Data session tidak tersimpan dengan benar

## Solusi:

### 1. **Buat Session Test untuk Development**
```bash
# Jalankan command ini untuk membuat session test
php artisan tinker --execute="
use App\Models\Exam\ExamLiveSession;
use App\Models\User;
use App\Models\Master\Timetable\Timetable;

// Ambil user pertama sebagai test student
\$user = User::first();
\$timetable = Timetable::first();

if (\$user && \$timetable) {
    \$session = ExamLiveSession::create([
        'user_id' => \$user->id,
        'timetable_id' => \$timetable->id,
        'session_token' => 'test-session-' . time(),
        'is_active' => true,
        'camera_status' => 'active',
        'connection_status' => 'connected',
        'peer_id' => 'test-peer-' . \$user->id,
        'started_at' => now(),
        'last_activity' => now()
    ]);

    echo 'Test session created: ' . \$session->id . PHP_EOL;
    echo 'User: ' . \$user->name . PHP_EOL;
    echo 'Timetable: ' . \$timetable->id . PHP_EOL;
} else {
    echo 'No user or timetable found!' . PHP_EOL;
}
"
```

### 2. **Perbaiki Frontend untuk Handle Empty Sessions**
Admin streaming page perlu di-update untuk handle kasus kosong dengan lebih baik.

### 3. **Debug PeerJS Connection**
Tambahkan logging yang lebih detail di browser console:

```javascript
// Paste ini di browser console pada halaman streaming
console.log('=== PeerJS Debug ===');
console.log('PeerJS available:', typeof Peer !== 'undefined');

// Test API endpoint
fetch('/api/stream/real-streams')
  .then(response => response.json())
  .then(data => {
    console.log('API Response:', data);
    console.log('Active streams:', data.streams?.length || 0);
  })
  .catch(error => console.error('API Error:', error));
```

### 4. **Cek PeerJS Server Status**
```bash
# Cek apakah PeerJS server berjalan
netstat -tlnp | grep :9000
# atau
ss -tlnp | grep :9000
```

### 5. **Langkah-langkah Testing:**

1. **Buat session test** dengan command di atas
2. **Refresh halaman streaming** admin
3. **Cek browser console** untuk error PeerJS
4. **Test dengan mahasiswa real** - minta mahasiswa mulai ujian
5. **Monitor network tab** untuk request API

### 6. **Kemungkinan Masalah Lain:**

- **PeerJS Server tidak running** - jalankan `node peerjs-server.js`
- **CORS issues** - pastikan server PeerJS allow cross-origin
- **SSL/TLS issues** - pastikan HTTPS properly configured
- **Port blocking** - pastikan port 9000/9443 tidak diblok firewall

### 7. **Quick Fix untuk Demo:**
Jika perlu demo cepat, uncomment bagian demo mode di kode JavaScript admin streaming.
