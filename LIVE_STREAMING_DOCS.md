# Live Streaming Monitor System

## Overview
Sistem live streaming monitor untuk memantau kamera siswa secara real-time selama ujian berlangsung. Supervisor dapat melihat semua siswa yang sedang ujian dalam satu halaman seperti Zoom atau platform video conference lainnya.

## Features

### 1. Live Stream Monitor (`/admin/exam/live-stream`)
- **Grid View**: Menampilkan semua siswa dalam format grid (4 kolom)
- **Gallery View**: Menampilkan siswa dalam format gallery yang lebih besar (3 kolom)
- **Single View**: Fokus pada satu siswa dengan panel daftar siswa di samping

### 2. Real-time Features
- Live camera streaming dari siswa ke supervisor
- Auto refresh setiap 3 detik
- Status monitoring (connected, disconnected, warning, error)
- Alert counting dan risk assessment
- Progress tracking per siswa

### 3. Control Features
- Snapshot capture untuk setiap siswa
- Session termination (paksa keluar siswa)
- View mode switching (grid/gallery/single)
- Filtering by status (all/active/warning/error)
- Fullscreen mode untuk video

## Technical Implementation

### Client Side (Student)
1. **Camera Access**: Menggunakan `getUserMedia()` untuk akses kamera
2. **WebRTC Streaming**: Peer-to-peer connection untuk live streaming
3. **Recording**: Parallel recording untuk backup dan audit
4. **Session Monitoring**: Real-time activity tracking

### Server Side (Supervisor)
1. **Live Session Management**: Database tracking untuk semua session aktif
2. **WebRTC Signaling**: Relay offer/answer/ICE candidates
3. **API Endpoints**: RESTful API untuk streaming control
4. **Real-time Updates**: Livewire components untuk reactive UI

## Database Schema

### ExamLiveSession Table
```sql
- id (UUID)
- timetable_id
- user_timetable_id
- user_id
- session_token (untuk WebRTC)
- camera_status (active/inactive/error)
- connection_status (connected/disconnected/unstable)
- current_question_number
- total_questions
- answered_questions
- alert_count
- warning_count
- last_activity
- is_active
- session_metadata (JSON)
- browser_info (JSON)
```

## API Endpoints

### Live Streaming APIs
- `POST /api/stream/offer` - WebRTC offer dari siswa
- `POST /api/stream/answer` - WebRTC answer dari supervisor
- `POST /api/stream/ice-candidate` - ICE candidate exchange
- `GET /api/stream/sessions` - Daftar session aktif

### Session Management APIs
- `POST /api/end-live-session` - Terminate session
- `POST /api/log-alert` - Log client-side alerts

## Usage Instructions

### For Supervisors:
1. Akses `/admin/exam/live-stream`
2. Pilih view mode (Grid/Gallery/Single)
3. Monitor siswa secara real-time
4. Gunakan filter untuk fokus pada status tertentu
5. Ambil snapshot jika diperlukan
6. Terminate session jika ada pelanggaran

### For Students:
- Sistem otomatis aktif saat memulai ujian
- Kamera harus selalu menyala
- Live streaming berjalan di background
- Recording dan monitoring berlangsung otomatis

## Security & Privacy

### Student Privacy Protection:
- Stream hanya aktif selama ujian
- Data dienkripsi melalui WebRTC
- Recording tersimpan aman di server
- Akses terbatas hanya untuk supervisor

### Anti-Cheating Measures:
- Real-time face detection
- Tab switching detection
- Screen capture monitoring
- Fullscreen enforcement
- Multiple alert thresholds

## Browser Compatibility
- Chrome/Chromium: Full support
- Firefox: Full support
- Safari: Limited support (WebRTC issues)
- Edge: Full support

## Installation Notes
1. HTTPS required untuk camera access
2. WebRTC requires STUN/TURN servers for production
3. High bandwidth recommended for multiple streams
4. Modern browser versions required

## Future Enhancements
- AI-powered cheating detection
- Multi-supervisor support
- Stream recording for playback
- Mobile supervisor app
- Advanced analytics dashboard
