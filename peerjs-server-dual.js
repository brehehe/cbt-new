import fs from 'fs';
import os from 'os';
import { PeerServer } from 'peer';

const httpPort = process.env.HTTP_PORT || 9000;
const httpsPort = process.env.HTTPS_PORT || 9443;
const host = process.env.HOST || '0.0.0.0';

// Common PeerJS configuration
const peerConfig = {
  host: host,
  path: '/peerjs',
  cors: {
    origin: [
      // Allow HTTPS domain
      'https://cbt-new.drshieldapp.com',
      'https://www.cbt-new.drshieldapp.com',
      // Allow HTTP for backward compatibility
      'http://cbt-new.drshieldapp.com',
      'http://www.cbt-new.drshieldapp.com',
      // Allow localhost for development
      'http://localhost:8000',
      'https://localhost:8000',
      'http://127.0.0.1:8000',
      'https://127.0.0.1:8000',
      /^https?:\/\/localhost(:\d+)?$/,
      /^https?:\/\/127\.0\.0\.1(:\d+)?$/,
    ],
    methods: ["GET", "POST", "PUT", "DELETE", "OPTIONS"],
    credentials: true,
    allowedHeaders: ['Content-Type', 'Authorization', 'X-Requested-With']
  },
  allow_discovery: true,
  proxied: process.env.NODE_ENV === 'production'
};

// Create HTTP PeerJS Server (existing)
console.log('🚀 Starting HTTP PeerJS Server...');
const httpPeerServer = PeerServer({
  ...peerConfig,
  port: httpPort
});

// SSL certificate paths - UPDATE THESE PATHS
const sslCertPath = process.env.SSL_CERT_PATH || '/etc/ssl/peerjs/peerjs.crt';
const sslKeyPath = process.env.SSL_KEY_PATH || '/etc/ssl/peerjs/peerjs.key';

// Create HTTPS PeerJS Server (new)
let httpsPeerServer;
try {
  // Check if SSL certificates exist
  if (fs.existsSync(sslCertPath) && fs.existsSync(sslKeyPath)) {
    console.log('🔒 SSL certificates found, starting HTTPS PeerJS Server...');

    const sslOptions = {
      key: fs.readFileSync(sslKeyPath),
      cert: fs.readFileSync(sslCertPath)
    };

    httpsPeerServer = PeerServer({
      ...peerConfig,
      port: httpsPort,
      ssl: sslOptions
    });

    console.log('✅ HTTPS PeerJS Server enabled');
  } else {
    console.log('⚠️  SSL certificates not found at:');
    console.log(`   Certificate: ${sslCertPath}`);
    console.log(`   Private Key: ${sslKeyPath}`);
    console.log('');
    console.log('💡 To enable HTTPS PeerJS server:');
    console.log('   1. Get SSL certificate for 213.210.21.140');
    console.log('   2. Place files at the paths above');
    console.log('   3. Or set SSL_CERT_PATH and SSL_KEY_PATH environment variables');
    console.log('   4. Restart this server');
    console.log('');
    console.log('🔄 Running HTTP-only mode for now...');
  }
} catch (error) {
  console.log('❌ Failed to create HTTPS server:', error.message);
  console.log('🔄 Running HTTP-only mode...');
}

// Get server IP addresses
function getServerIPs() {
  const interfaces = os.networkInterfaces();
  const ips = [];

  for (const interfaceName in interfaces) {
    const networkInterface = interfaces[interfaceName];
    for (const net of networkInterface) {
      if (net.family === 'IPv4' && !net.internal) {
        ips.push(net.address);
      }
    }
  }

  return ips;
}

const serverIPs = getServerIPs();

console.log('\n📡 PeerJS Servers Status:');
console.log('═══════════════════════════════');

// HTTP Server Info
console.log('\n📄 HTTP PeerJS Server (for backward compatibility):');
console.log(`   Port: ${httpPort}`);
console.log(`   Protocol: HTTP`);
if (serverIPs.length > 0) {
  serverIPs.forEach(ip => {
    console.log(`   🔗 http://${ip}:${httpPort}/peerjs`);
  });
}

// HTTPS Server Info
if (httpsPeerServer) {
  console.log('\n🔒 HTTPS PeerJS Server (for HTTPS websites):');
  console.log(`   Port: ${httpsPort}`);
  console.log(`   Protocol: HTTPS`);
  if (serverIPs.length > 0) {
    serverIPs.forEach(ip => {
      console.log(`   🔗 https://${ip}:${httpsPort}/peerjs`);
    });
  }
} else {
  console.log('\n🔒 HTTPS PeerJS Server: DISABLED');
  console.log('   Reason: SSL certificates not found');
}

console.log('\n🌐 Client Configuration:');
console.log('════════════════════════════');
console.log('For HTTPS websites (https://cbt-new.drshieldapp.com):');
if (httpsPeerServer) {
  console.log(`   ✅ Will use: https://${serverIPs[0] || '213.210.21.140'}:${httpsPort}/peerjs`);
} else {
  console.log(`   ❌ Will fail: mixed content blocked`);
  console.log(`   💡 Need HTTPS PeerJS server on port ${httpsPort}`);
}

console.log('\nFor HTTP websites:');
console.log(`   ✅ Will use: http://${serverIPs[0] || '213.210.21.140'}:${httpPort}/peerjs`);

// Event handlers for HTTP server
httpPeerServer.on('connection', (client) => {
  console.log(`🟢 HTTP Client connected: ${client.getId()}`);
});

httpPeerServer.on('disconnect', (client) => {
  console.log(`🔴 HTTP Client disconnected: ${client.getId()}`);
});

// Event handlers for HTTPS server (if available)
if (httpsPeerServer) {
  httpsPeerServer.on('connection', (client) => {
    console.log(`🟢 HTTPS Client connected: ${client.getId()}`);
  });

  httpsPeerServer.on('disconnect', (client) => {
    console.log(`🔴 HTTPS Client disconnected: ${client.getId()}`);
  });
}

// Health check info
console.log('\n🔍 Health Check URLs:');
console.log('═══════════════════════');
console.log(`HTTP:  http://${serverIPs[0] || '213.210.21.140'}:${httpPort}/peerjs`);
if (httpsPeerServer) {
  console.log(`HTTPS: https://${serverIPs[0] || '213.210.21.140'}:${httpsPort}/peerjs`);
}

// Keep the servers running
process.on('SIGINT', () => {
  console.log('\n🛑 Shutting down PeerJS servers...');
  process.exit(0);
});

// Show startup complete message
setTimeout(() => {
  console.log('\n🎉 PeerJS Servers Ready!');
  console.log('═══════════════════════════');
  if (httpsPeerServer) {
    console.log('✅ Both HTTP and HTTPS servers running');
    console.log('✅ HTTPS websites can now connect');
  } else {
    console.log('⚠️  Only HTTP server running');
    console.log('❌ HTTPS websites will face mixed content issues');
    console.log('💡 Setup SSL certificates to enable HTTPS server');
  }
  console.log('\nPress Ctrl+C to stop servers');
}, 1000);
