import os from 'os';
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
      'https://www.cbt.mediction.id',
      'https://cbt-new.drshieldapp.com',
      'https://www.cbt-new.drshieldapp.com',
    ] : true,
    methods: ["GET", "POST"],
    credentials: true
  },
  allow_discovery: true,
  proxied: process.env.NODE_ENV === 'production'
});

// Get server IP addresses
function getServerIPs() {
  const interfaces = os.networkInterfaces();
  const ips = [];

  for (const interfaceName in interfaces) {
    const networkInterface = interfaces[interfaceName];
    for (const net of networkInterface) {
      // Skip internal and non-IPv4 addresses
      if (net.family === 'IPv4' && !net.internal) {
        ips.push(net.address);
      }
    }
  }

  return ips;
}

const serverIPs = getServerIPs();

console.log('🚀 PeerJS Server running on port', port);
console.log('�️  Host:', host);
console.log('�📡 Path: /peerjs');

// Display server IP addresses
if (serverIPs.length > 0) {
  console.log('🌍 Server IP addresses:');
  serverIPs.forEach(ip => {
    console.log(`   - ${ip}:${port}/peerjs`);
  });
}

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
