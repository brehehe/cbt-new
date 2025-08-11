import fs from 'fs';
import os from 'os';
import { PeerServer } from 'peer';

const httpPort = process.env.HTTP_PORT || 9000;
const httpsPort = process.env.HTTPS_PORT || 9443;
const host = process.env.HOST || '0.0.0.0';

// SSL Configuration - You need to provide these certificate files
const sslOptions = {
  key: fs.readFileSync('/path/to/your/private-key.pem'), // Update path
  cert: fs.readFileSync('/path/to/your/certificate.pem'), // Update path
  // If you have intermediate certificates, uncomment the line below
  // ca: fs.readFileSync('/path/to/your/ca-bundle.pem')
};

// Common PeerJS configuration
const peerConfig = {
  host: host,
  path: '/peerjs',
  cors: {
    origin: process.env.NODE_ENV === 'production' ? [
      'https://cbt.mediction.id',
      'https://www.cbt.mediction.id',
      'https://cbt-new.drshieldapp.com',
      'https://www.cbt-new.drshieldapp.com',
      'http://cbt-new.drshieldapp.com',
      'http://www.cbt-new.drshieldapp.com',
    ] : true,
    methods: ["GET", "POST"],
    credentials: true
  },
  allow_discovery: true,
  proxied: process.env.NODE_ENV === 'production'
};

// Create HTTP PeerJS Server
const httpPeerServer = PeerServer({
  ...peerConfig,
  port: httpPort
});

// Create HTTPS PeerJS Server
let httpsPeerServer;
try {
  // Check if SSL certificates exist
  if (fs.existsSync('/path/to/your/private-key.pem') && fs.existsSync('/path/to/your/certificate.pem')) {
    httpsPeerServer = PeerServer({
      ...peerConfig,
      port: httpsPort,
      ssl: sslOptions
    });
    console.log('✅ HTTPS PeerJS Server enabled with SSL certificates');
  } else {
    console.log('⚠️  SSL certificates not found. Only HTTP server will run.');
    console.log('   To enable HTTPS:');
    console.log('   1. Obtain SSL certificate for your domain');
    console.log('   2. Update certificate paths in peerjs-server-https.js');
    console.log('   3. Restart the server');
  }
} catch (error) {
  console.log('⚠️  Failed to create HTTPS server:', error.message);
  console.log('   Only HTTP server will run.');
}

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

console.log('🚀 PeerJS Servers starting...');
console.log('📡 Path: /peerjs');
console.log('🖥️  Host:', host);

// Display HTTP server info
console.log('\n📄 HTTP PeerJS Server:');
console.log(`   Port: ${httpPort}`);
if (serverIPs.length > 0) {
  console.log('   IP addresses:');
  serverIPs.forEach(ip => {
    console.log(`   - http://${ip}:${httpPort}/peerjs`);
  });
}

// Display HTTPS server info if available
if (httpsPeerServer) {
  console.log('\n🔒 HTTPS PeerJS Server:');
  console.log(`   Port: ${httpsPort}`);
  if (serverIPs.length > 0) {
    console.log('   IP addresses:');
    serverIPs.forEach(ip => {
      console.log(`   - https://${ip}:${httpsPort}/peerjs`);
    });
  }
}

console.log('\n🌐 Recommended access:');
console.log(`   For HTTP sites: http://${serverIPs[0] || 'localhost'}:${httpPort}/peerjs`);
if (httpsPeerServer) {
  console.log(`   For HTTPS sites: https://${serverIPs[0] || 'localhost'}:${httpsPort}/peerjs`);
}

// Event handlers for HTTP server
httpPeerServer.on('connection', (client) => {
  console.log(`✅ HTTP Client connected: ${client.getId()}`);
});

httpPeerServer.on('disconnect', (client) => {
  console.log(`❌ HTTP Client disconnected: ${client.getId()}`);
});

// Event handlers for HTTPS server (if available)
if (httpsPeerServer) {
  httpsPeerServer.on('connection', (client) => {
    console.log(`✅ HTTPS Client connected: ${client.getId()}`);
  });

  httpsPeerServer.on('disconnect', (client) => {
    console.log(`❌ HTTPS Client disconnected: ${client.getId()}`);
  });
}

// Keep the servers running
process.on('SIGINT', () => {
  console.log('\n🛑 Shutting down PeerJS servers...');
  process.exit(0);
});

// Health check endpoints
console.log('\n🔍 Health check endpoints:');
console.log(`   HTTP:  http://${serverIPs[0] || 'localhost'}:${httpPort}/peerjs`);
if (httpsPeerServer) {
  console.log(`   HTTPS: https://${serverIPs[0] || 'localhost'}:${httpsPort}/peerjs`);
}
