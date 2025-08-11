import { PeerServer } from 'peer';

const port = process.env.PORT || 9000;
const host = process.env.HOST || '0.0.0.0';

const peerServer = PeerServer({
  port: port,
  host: host,
  path: '/peerjs',
  cors: {
    origin: process.env.NODE_ENV === 'production' ? [
      'https://cbt.mediction.id',
      'https://www.cbt.mediction.id'
    ] : true,
    methods: ["GET", "POST"],
    credentials: true
  },
  allow_discovery: true,
  proxied: process.env.NODE_ENV === 'production'
});

console.log('🚀 PeerJS Server running on port', port);
console.log('📡 Path: /peerjs');
console.log('🌐 Access from:', process.env.NODE_ENV === 'production' ?
  `https://cbt.mediction.id:${port}/peerjs` :
  `http://localhost:${port}/peerjs`);

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
