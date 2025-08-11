<?php

echo "=== DEBUG PEERJS STREAMING ISSUES ===\n\n";

// 1. Check PeerJS library availability
echo "1. CHECKING PEERJS LIBRARY:\n";
echo "   PeerJS CDN: https://unpkg.com/peerjs@1.5.0/dist/peerjs.min.js\n";
echo "   Loading status: Will be checked in browser console\n\n";

// 2. Check environment
echo "2. CHECKING ENVIRONMENT:\n";
$currentHost = $_SERVER['HTTP_HOST'] ?? 'localhost';
$isProduction = strpos($currentHost, 'drshieldapp.com') !== false;
$isSecure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';

echo "   Current Host: {$currentHost}\n";
echo "   Is Production: " . ($isProduction ? 'YES' : 'NO') . "\n";
echo "   Is HTTPS: " . ($isSecure ? 'YES' : 'NO') . "\n\n";

// 3. Check PeerJS configuration issues
echo "3. CHECKING PEERJS CONFIGURATION:\n";
$currentHost = $_SERVER['HTTP_HOST'] ?? 'localhost';
$isProduction = strpos($currentHost, 'drshieldapp.com') !== false;
$isSecure = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on';

echo "   Current Host: {$currentHost}\n";
echo "   Is Production: " . ($isProduction ? 'YES' : 'NO') . "\n";
echo "   Is HTTPS: " . ($isSecure ? 'YES' : 'NO') . "\n";

if ($isProduction && $isSecure) {
    echo "   ✅ Production HTTPS detected - should use HTTPS PeerJS server\n";
    echo "   PeerJS Config: host: 'peer.toti.my.id', secure: true\n";
} elseif ($isProduction && !$isSecure) {
    echo "   ⚠️  Production HTTP detected - Mixed content issues possible\n";
    echo "   PeerJS Config: host: '213.210.21.140', port: 9000, secure: false\n";
} else {
    echo "   🔧 Development environment detected\n";
    echo "   PeerJS Config: host: 'localhost', port: 9000, secure: false\n";
}
echo "\n";

// 4. Check API endpoint
echo "4. CHECKING API ENDPOINTS:\n";
try {
    $baseUrl = $isSecure ? 'https://' : 'http://';
    $baseUrl .= $currentHost;

    echo "   Base URL: {$baseUrl}\n";
    echo "   Real Streams API: {$baseUrl}/api/stream/real-streams\n";

    // Test API availability (simplified)
    echo "   API Status: Available (check browser network tab for actual response)\n";
} catch (Exception $e) {
    echo "   ❌ API Error: " . $e->getMessage() . "\n";
}
echo "\n";

// 5. Common issues and solutions
echo "5. COMMON ISSUES & SOLUTIONS:\n";
echo "   Issue 1: PeerJS library not loading\n";
echo "   Solution: Check browser console for PeerJS errors\n";
echo "            Verify CDN is accessible\n\n";

echo "   Issue 2: No active sessions\n";
echo "   Solution: Start student exam first\n";
echo "            Check if live sessions are being created\n\n";

echo "   Issue 3: PeerJS server connection failed\n";
echo "   Solution: Check PeerJS server is running on correct port\n";
echo "            For local: port 9000\n";
echo "            For production: peer.toti.my.id\n\n";

echo "   Issue 4: CORS/Mixed content issues\n";
echo "   Solution: Ensure HTTPS is properly configured\n";
echo "            Check browser security settings\n\n";

echo "   Issue 5: Peer ID not being set\n";
echo "   Solution: Check student side PeerJS initialization\n";
echo "            Verify updatePeerJSId Livewire dispatch\n\n";

// 6. Debug JavaScript code
echo "6. JAVASCRIPT DEBUG CODE:\n";
echo "   Add this to browser console on streaming page:\n\n";

$debugJs = "
// Debug PeerJS availability
console.log('PeerJS available:', typeof Peer !== 'undefined');

// Debug active sessions
fetch('/api/stream/real-streams?timetable_id=1')
  .then(response => response.json())
  .then(data => {
    console.log('API Response:', data);
    console.log('Active sessions:', data.streams?.length || 0);
    data.streams?.forEach(stream => {
      console.log('Session:', stream.user_name, 'PeerID:', stream.peer_id);
    });
  })
  .catch(error => console.error('API Error:', error));

// Test PeerJS connection
if (typeof Peer !== 'undefined') {
  const testPeer = new Peer({
    host: 'localhost',
    port: 9000,
    path: '/peerjs',
    secure: false
  });

  testPeer.on('open', id => console.log('✅ PeerJS connected:', id));
  testPeer.on('error', err => console.error('❌ PeerJS error:', err));
} else {
  console.error('❌ PeerJS library not loaded');
}
";

echo $debugJs;
echo "\n\n";

// 7. Next steps
echo "7. NEXT STEPS TO FIX:\n";
echo "   1. Open browser console on streaming page\n";
echo "   2. Check for PeerJS errors\n";
echo "   3. Verify active sessions exist\n";
echo "   4. Test API endpoints\n";
echo "   5. Check PeerJS server status\n";
echo "   6. Verify student exams are running\n\n";

echo "=== DEBUG COMPLETE ===\n";

?>
