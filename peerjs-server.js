import { PeerServer } from "peer";

// ===== CONFIGURASI DASAR =====
const PORT = process.env.PORT || 9000;
const HOST = process.env.HOST || "0.0.0.0";

// Jalankan PeerJS hanya sebagai HTTP server
const peerServer = PeerServer({
  port: PORT,
  host: HOST,
  path: '/peerjs',
  allow_discovery: true,
  proxied: true,
  alive_timeout: 60000, // Keep connections alive for 60 seconds before timeout
  // CORS aman untuk domain kamu
  // CORS: Allow ALL domains automatically (Maintenance Free)
  cors: {
    origin: true,
    methods: ['GET', 'POST', 'OPTIONS'],
    credentials: true
  }
});

// ===== EVENT LOG =====
peerServer.on("connection", (client) => {
    console.log(`🟢 Peer connected: ${client.getId()}`);
});
peerServer.on("disconnect", (client) => {
    console.log(`🔴 Peer disconnected: ${client.getId()}`);
});

console.log(`✅ PeerJS Server started on port ${PORT}`);
console.log(`   Path: /peerjs`);
console.log(`   Access via Nginx HTTPS proxy: https://peer.toti.my.id/peerjs`);
