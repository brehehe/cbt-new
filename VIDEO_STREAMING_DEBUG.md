# Debug Video Streaming Issues

## Masalah yang Ditemukan:
- PeerJS berhasil connect ✅
- Stream berhasil diterima ✅
- Fungsi displayStudentStream dipanggil ✅
- **Video tidak muncul** ❌

## Kemungkinan Penyebab:

### 1. **Container Size Issues**
Container mungkin tidak memiliki ukuran yang tepat untuk menampilkan video.

**Solusi yang diterapkan:**
```css
style="min-height: 200px; aspect-ratio: 16/9;"
```

### 2. **Video Stream Format Issues**
Stream mungkin tidak kompatibel dengan video element.

**Debug di Browser Console:**
```javascript
// Check stream properties
window.AdminStreaming.activeSessions.forEach(session => {
  console.log('Session:', session);
  if (session.call && session.call.remoteStream) {
    const stream = session.call.remoteStream;
    console.log('Stream tracks:', stream.getTracks());
    stream.getTracks().forEach(track => {
      console.log('Track:', track.kind, track.enabled, track.readyState);
    });
  }
});

// Test manual video creation
const testContainer = document.querySelector('[id^="streamContainer-"]');
if (testContainer) {
  console.log('Container found:', testContainer);
  console.log('Container dimensions:', {
    width: testContainer.offsetWidth,
    height: testContainer.offsetHeight,
    clientWidth: testContainer.clientWidth,
    clientHeight: testContainer.clientHeight
  });
}
```

### 3. **Video Element Issues**
Video element mungkin tidak ter-render dengan benar.

**Debug video events:**
```javascript
// Monitor video events
document.addEventListener('DOMNodeInserted', function(e) {
  if (e.target.tagName === 'VIDEO') {
    console.log('Video element added:', e.target);
    console.log('Video src:', e.target.srcObject);

    e.target.addEventListener('loadstart', () => console.log('Video loadstart'));
    e.target.addEventListener('loadeddata', () => console.log('Video loadeddata'));
    e.target.addEventListener('canplay', () => console.log('Video canplay'));
    e.target.addEventListener('error', (err) => console.error('Video error:', err));
  }
});
```

## Manual Testing Commands:

### 1. **Test Container Existence**
```javascript
// Test if container exists
const sessionId = '019897a2-6c30-734b-8340-5b0bf26abbfc';
const container = document.getElementById(`streamContainer-${sessionId}`);
console.log('Container exists:', !!container);
console.log('Container element:', container);
```

### 2. **Test Video Creation**
```javascript
// Test manual video creation
const sessionId = '019897a2-6c30-734b-8340-5b0bf26abbfc';
const container = document.getElementById(`streamContainer-${sessionId}`);

if (container) {
  // Clear container
  container.innerHTML = '';

  // Create test video with test stream
  const video = document.createElement('video');
  video.style.width = '100%';
  video.style.height = '100%';
  video.style.minHeight = '200px';
  video.style.backgroundColor = 'red'; // Temporary background
  video.autoplay = true;
  video.muted = true;
  video.playsInline = true;

  container.appendChild(video);
  console.log('Test video added:', video);
}
```

### 3. **Test Stream Assignment**
```javascript
// Get actual stream from PeerJS
const activeSessions = window.AdminStreaming.activeSessions;
console.log('Active sessions:', activeSessions);

const realSession = activeSessions.find(s => s.type === 'real');
if (realSession && realSession.call) {
  console.log('Real session found:', realSession);

  // Get the stream
  const call = realSession.call;
  console.log('Call object:', call);

  if (call.remoteStream) {
    console.log('Remote stream found:', call.remoteStream);

    // Test manual video assignment
    const container = document.getElementById(`streamContainer-${realSession.session_id}`);
    if (container) {
      const video = container.querySelector('video');
      if (video) {
        video.srcObject = call.remoteStream;
        console.log('Stream assigned to video');
      }
    }
  }
}
```

## Next Steps:

1. **Refresh halaman admin streaming**
2. **Buka browser console**
3. **Jalankan debug commands di atas**
4. **Periksa apakah video element ter-render**
5. **Cek dimensi container**

## Expected Output:
Setelah fix ini, Anda harus melihat:
- Container memiliki ukuran yang tepat
- Video element ter-render
- Stream berhasil ditampilkan
- Debug logging yang lebih detail
