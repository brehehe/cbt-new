import { PeerServer } from 'peer';

const peerServer = PeerServer({
  port: 9000,
  path: '/peerjs',
  cors: {
    origin: true,
    methods: ["GET", "POST"],
    credentials: true
  },
  allow_discovery: true
});

console.log('🚀 PeerJS Server running on port 9000');
console.log('📡 Path: /peerjs');
console.log('🌐 Access from: http://localhost:9000/peerjs');

peerServer.on('connection', (client) => {
  console.log(`✅ Client connected: ${client.getId()}`);
});

peerServer.on('disconnect', (client) => {
  console.log(`❌ Client disconnected: ${client.getId()}`);
});

// Keep the server running
process.on('SIGINT', () => {
  console.log('\n🛑 Shutting down PeerJS server...');
  process.exit(0);
});
