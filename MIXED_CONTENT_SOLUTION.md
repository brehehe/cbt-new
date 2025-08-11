# Mixed Content Solution - PeerJS HTTP Server

# Mixed Content Solution - HTTPS Domain with HTTP PeerJS

## Current Setup Requirements
- **Main Domain**: https://cbt-new.drshieldapp.com (MUST stay HTTPS)
- **PeerJS Server**: http://213.210.21.140:9000/peerjs (Currently HTTP)
- **Problem**: Browser blocks HTTP requests from HTTPS pages (Mixed Content Policy)

## Error Message
```
Mixed Content: The page at 'https://cbt-new.drshieldapp.com/admin/exam/detail' was loaded over HTTPS, but requested an insecure resource 'http://213.210.21.140:9000/peerjs/peerjs/id?ts=...'. This request has been blocked; the content must be served over HTTPS.
```

## Solutions (Keep Domain HTTPS)

### Solution 1: Setup HTTPS PeerJS Server ⭐ (Recommended)
```bash
# 1. Get SSL certificate for 213.210.21.140
# Using Let's Encrypt (free):
sudo apt update
sudo apt install certbot
sudo certbot certonly --standalone -d your-peerjs-domain.com

# Or use existing wildcard certificate if available

# 2. Modify PeerJS server to support HTTPS
# Create peerjs-server-https.js:
const fs = require('fs');
const { PeerServer } = require('peer');

const httpsOptions = {
  key: fs.readFileSync('/path/to/private-key.pem'),
  cert: fs.readFileSync('/path/to/certificate.pem')
};

// Keep HTTP server for backward compatibility
const httpServer = PeerServer({
  port: 9000,
  host: '0.0.0.0',
  path: '/peerjs'
});

// Add HTTPS server
const httpsServer = PeerServer({
  port: 9443,
  host: '0.0.0.0',
  path: '/peerjs',
  ssl: httpsOptions
});

console.log('HTTP PeerJS: http://213.210.21.140:9000/peerjs');
console.log('HTTPS PeerJS: https://213.210.21.140:9443/peerjs');

# 3. Start both servers
node peerjs-server-https.js

# 4. Client will automatically use HTTPS connection on port 9443
```

### Solution 2: Reverse Proxy with SSL ⭐ (Alternative)
```nginx
# /etc/nginx/sites-available/peerjs-ssl
server {
    listen 9443 ssl;
    server_name 213.210.21.140;

    # SSL Configuration
    ssl_certificate /path/to/certificate.crt;
    ssl_certificate_key /path/to/private.key;
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    # Proxy to HTTP PeerJS server
    location /peerjs {
        proxy_pass http://127.0.0.1:9000;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;

        # WebSocket support for PeerJS
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";

        # CORS headers
        add_header Access-Control-Allow-Origin *;
        add_header Access-Control-Allow-Methods "GET, POST, OPTIONS";
        add_header Access-Control-Allow-Headers "Content-Type, Authorization";
    }
}

# Enable the site
sudo ln -s /etc/nginx/sites-available/peerjs-ssl /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### Solution 3: Use Cloudflare SSL (If using Cloudflare)
```bash
# If 213.210.21.140 is behind Cloudflare:
# 1. Enable "Always Use HTTPS" in Cloudflare
# 2. Set SSL/TLS mode to "Full" or "Full (strict)"
# 3. PeerJS will be accessible via HTTPS automatically
```### Solution 4: Development Override (Testing Only)
```bash
# Start Chrome with disabled security (NOT for production):
google-chrome --disable-web-security --user-data-dir=/tmp/chrome_dev --disable-features=VizDisplayCompositor

# Or use Firefox with security.mixed_content.block_active_content set to false
```

## Current Implementation

The client code now:
1. ✅ Detects HTTPS vs HTTP environment
2. ✅ Shows clear error messages when mixed content is blocked
3. ✅ Provides debugging tools to test connectivity
4. ✅ Continues exam functionality even if PeerJS fails
5. ✅ Updates UI to show connection status

## Testing Commands

### Test PeerJS Server Accessibility
```bash
# From command line:
curl -I http://213.210.21.140:9000/peerjs

# From browser console (will fail on HTTPS sites):
fetch('http://213.210.21.140:9000/peerjs')
  .then(r => console.log('✅ Accessible'))
  .catch(e => console.log('❌ Blocked:', e.message));
```

### Debug Client-Side
```javascript
// In browser console:
testPeerJSConnection(); // Use the debug button or call directly
showDebugInfo(); // Shows complete system information
```

## Status Monitoring

The UI now shows:
- **Environment**: HTTPS/HTTP detection
- **PeerJS Status**: Connection state
- **Connection Info**: Error details or peer ID
- **Manual Test Buttons**: For live debugging

## Recommended Action

**Keep domain HTTPS**: https://cbt-new.drshieldapp.com
**Setup HTTPS PeerJS**: Enable SSL on 213.210.21.140:9443

### Quick Implementation Steps:
1. **Get SSL certificate** for 213.210.21.140
2. **Run dual PeerJS servers** (HTTP + HTTPS)
3. **Client auto-detects** and uses HTTPS connection
4. **Test with both protocols** for compatibility

The exam will continue to work normally regardless of PeerJS connection status. Only the live video monitoring feature will be affected until HTTPS is configured on the PeerJS server.
