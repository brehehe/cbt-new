# PeerJS HTTPS Mixed Content Solution

## Problem
When your website runs on HTTPS (like https://cbt-new.drshieldapp.com), browsers block connections to HTTP resources (like http://213.210.21.140:9000) due to mixed content security restrictions.

## Current Error
```
Mixed Content: The page at 'https://cbt-new.drshieldapp.com/admin/exam/detail' was loaded over HTTPS, but requested an insecure resource 'http://213.210.21.140:9000/peerjs/peerjs/id?ts=...'. This request has been blocked; the content must be served over HTTPS.
```

## Solutions (Choose One)

### Solution 1: Enable HTTPS on PeerJS Server (Recommended)
1. **Obtain SSL Certificate for your server (213.210.21.140)**
   ```bash
   # Using Let's Encrypt (free)
   sudo apt update
   sudo apt install certbot
   sudo certbot certonly --standalone -d your-domain.com
   ```

2. **Update PeerJS server to support HTTPS**
   ```bash
   # Copy the enhanced HTTPS server
   cp peerjs-server-https.js peerjs-server.js

   # Update certificate paths in the file
   nano peerjs-server.js
   ```

3. **Run HTTPS PeerJS server on port 9443**
   ```bash
   HTTPS_PORT=9443 node peerjs-server.js
   ```

4. **Update client to use HTTPS**
   - The client code will automatically detect and use port 9443 for HTTPS sites

### Solution 2: Use HTTP Version of Your Site
- Access your site via: `http://cbt-new.drshieldapp.com`
- This avoids mixed content restrictions entirely
- PeerJS will work normally on HTTP

### Solution 3: Reverse Proxy with SSL Termination
Set up nginx to proxy HTTPS requests to HTTP PeerJS server:

```nginx
server {
    listen 9443 ssl;
    server_name cbt-new.drshieldapp.com;

    ssl_certificate /path/to/certificate.crt;
    ssl_certificate_key /path/to/private.key;

    location /peerjs {
        proxy_pass http://213.210.21.140:9000;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;

        # WebSocket support
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
    }
}
```

### Solution 4: Development Workaround
For testing purposes only:
1. Open Chrome with disabled security: `chrome --disable-web-security --user-data-dir=/tmp/chrome_dev`
2. Or use HTTP localhost for development

## Quick Fix Implementation

### Server Side
1. **Run enhanced PeerJS server:**
   ```bash
   node peerjs-server-enhanced.js
   ```

2. **For production with SSL:**
   ```bash
   # Install SSL certificate first, then:
   node peerjs-server-https.js
   ```

### Client Side
The client code has been updated to:
- Automatically detect HTTPS vs HTTP environment
- Try HTTPS PeerJS connection first (port 9443)
- Provide clear error messages when mixed content blocks connection
- Fall back gracefully with user notifications

## Testing

### Test HTTPS Connection
```javascript
// In browser console on https://cbt-new.drshieldapp.com
fetch('https://213.210.21.140:9443/peerjs')
  .then(() => console.log('HTTPS PeerJS server accessible'))
  .catch(err => console.log('HTTPS PeerJS server not accessible:', err));
```

### Test HTTP Connection (will fail on HTTPS site)
```javascript
// This will be blocked by browser on HTTPS sites
fetch('http://213.210.21.140:9000/peerjs')
  .then(() => console.log('HTTP PeerJS server accessible'))
  .catch(err => console.log('Mixed content blocked:', err));
```

## Status Monitoring

The client code now provides detailed connection status:
- Environment detection (HTTPS/HTTP)
- Connection attempt logging
- Fallback handling
- User-friendly error messages

Check browser console for detailed debugging information.

## Immediate Action Required

1. **Choose a solution** from the options above
2. **For quick testing**: Access site via HTTP instead of HTTPS
3. **For production**: Set up HTTPS PeerJS server or reverse proxy

The exam functionality will continue to work normally. Only the live video streaming to supervisor will be affected until the connection issue is resolved.
