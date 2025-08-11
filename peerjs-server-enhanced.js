import os from 'os';
import { PeerServer } from 'peer';

const httpPort = process.env.HTTP_PORT || 9000;
const host = process.env.HOST || '0.0.0.0';

// For now, we'll run HTTP only but with better CORS configuration
const peerServer = PeerServer({
  port: httpPort,
  host: host,
  path: '/peerjs',
  cors: {
    origin: [
      // Allow both HTTP and HTTPS versions
      'https://cbt.mediction.id',
      'https://www.cbt.mediction.id',
      'https://cbt-new.drshieldapp.com',
      'https://www.cbt-new.drshieldapp.com',
      'http://cbt-new.drshieldapp.com',
      'http://www.cbt-new.drshieldapp.com',
      'http://localhost:8000',
      'https://localhost:8000',
      'http://127.0.0.1:8000',
      'https://127.0.0.1:8000',
      // Allow any localhost for development
      /^https?:\/\/localhost(:\d+)?$/,
      /^https?:\/\/127\.0\.0\.1(:\d+)?$/,
    ],
    methods: ["GET", "POST", "PUT", "DELETE", "OPTIONS"],
    credentials: true,
    allowedHeaders: ['Content-Type', 'Authorization', 'X-Requested-With']
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

console.log('🚀 Enhanced PeerJS Server starting...');
console.log('📡 Port:', httpPort);
console.log('🖥️  Host:', host);
console.log('📄 Path: /peerjs');
console.log('🔓 Protocol: HTTP (Mixed content workaround needed for HTTPS sites)');

// Display server IP addresses
if (serverIPs.length > 0) {
  console.log('\n🌍 Server accessible at:');
  serverIPs.forEach(ip => {
    console.log(`   - http://${ip}:${httpPort}/peerjs`);
  });
}

console.log('\n⚠️  HTTPS Mixed Content Notice:');
console.log('   - HTTPS sites cannot directly connect to HTTP PeerJS server');
console.log('   - For HTTPS sites, consider:');
console.log('     1. Setting up SSL certificates and HTTPS PeerJS server');
console.log('     2. Using reverse proxy with SSL termination');
console.log('     3. Accessing the site via HTTP for testing');

console.log('\n🔧 CORS Configuration:');
console.log('   - Allows both HTTP and HTTPS origins');
console.log('   - Supports localhost and production domains');
console.log('   - Credentials and custom headers enabled');

peerServer.on('connection', (client) => {
  console.log(`✅ Client connected: ${client.getId()}`);
  console.log(`   Timestamp: ${new Date().toISOString()}`);
});

peerServer.on('disconnect', (client) => {
  console.log(`❌ Client disconnected: ${client.getId()}`);
  console.log(`   Timestamp: ${new Date().toISOString()}`);
});

// Keep the server running
process.on('SIGINT', () => {
  console.log('\n🛑 Shutting down PeerJS server...');
  process.exit(0);
});

// Display final connection instructions
console.log('\n🔗 Connection Instructions:');
console.log('   For HTTP sites:');
console.log(`     peer = new Peer({ host: '${serverIPs[0] || 'localhost'}', port: ${httpPort}, path: '/peerjs', secure: false });`);
console.log('   For HTTPS sites (will fail without SSL):');
console.log(`     peer = new Peer({ host: '${serverIPs[0] || 'localhost'}', port: ${httpPort}, path: '/peerjs', secure: false });`);
console.log('     Note: This will cause mixed content error in browsers');
