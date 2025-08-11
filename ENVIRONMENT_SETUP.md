# Environment Setup Guide

## Server Architecture

### Production Server
- **Domain**: https://cbt-new.drshieldapp.com
- **PeerJS Server**: http://213.210.21.140:9000/peerjs
- **SSL**: Required for camera access in browsers

### Local Development
- **Domain**: http://localhost or https://cbt-test.test
- **PeerJS Server**: http://localhost:9000/peerjs
- **SSL**: Not required for localhost

## Environment Configuration

### 1. Laravel Environment (.env)

#### Production Server
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://cbt-new.drshieldapp.com
```

#### Local Development
```env
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost
# or
APP_URL=https://cbt-test.test
```

### 2. PeerJS Server Setup

#### Install PeerJS Server
```bash
npm install -g peer
```

#### Start PeerJS Server
```bash
# Production (on server)
peerjs --port 9000 --path /peerjs --allow_discovery

# Local Development
peerjs --port 9000 --path /peerjs --allow_discovery
```

#### Or use the Node.js server (recommended)
```bash
# Start the custom PeerJS server
node peerjs-server.js
```

### 3. JavaScript Auto-Configuration

The JavaScript code automatically detects the environment based on:

- **Domain**: `cbt-new.drshieldapp.com` = Production
- **Domain**: `localhost`, `127.0.0.1`, or `.test` = Local Development

#### Configuration Object
```javascript
const APP_CONFIG = {
    environment: 'production|local',
    domain: 'cbt-new.drshieldapp.com',
    isProduction: true|false,
    isLocal: true|false,
    peerjs: {
        host: '213.210.21.140|localhost',
        port: 9000,
        path: '/peerjs',
        secure: false, // Always HTTP for PeerJS
        debug: 3|1
    }
};
```

## Camera Access Requirements

### Production (HTTPS Required)
- Must use `https://cbt-new.drshieldapp.com`
- Browsers require HTTPS for camera access on non-localhost domains
- SSL certificate must be valid

### Local Development
- Can use `http://localhost` or `https://cbt-test.test`
- Camera access allowed on localhost without HTTPS
- For `.test` domains, HTTPS recommended

## Network Configuration

### Firewall Rules (Production Server)
```bash
# Allow PeerJS server port
sudo ufw allow 9000/tcp

# Allow HTTPS
sudo ufw allow 443/tcp

# Allow HTTP (for PeerJS)
sudo ufw allow 80/tcp
```

### Nginx Configuration
```nginx
# Main application (HTTPS)
server {
    listen 443 ssl;
    server_name cbt-new.drshieldapp.com;

    # Laravel application
    root /var/www/html/drshieldapp/cbt-new.drshieldapp.com/public;
    index index.php;

    # SSL configuration
    ssl_certificate /path/to/certificate.crt;
    ssl_certificate_key /path/to/private.key;
}

# PeerJS server proxy (optional)
server {
    listen 80;
    server_name cbt-new.drshieldapp.com;

    location /peerjs {
        proxy_pass http://localhost:9000;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
    }
}
```

## Troubleshooting

### 1. PeerJS Connection Issues
- Check if PeerJS server is running: `http://213.210.21.140:9000/peerjs`
- Verify firewall allows port 9000
- Check browser console for PeerJS errors

### 2. Camera Access Issues
- **Production**: Ensure using HTTPS
- **Local**: Use localhost or valid SSL certificate
- Check browser permissions for camera access

### 3. Livewire Communication Issues
- Verify `@this.call()` or `Livewire.dispatch()` in browser console
- Check Laravel logs for PHP errors
- Ensure Livewire scripts are loaded

## Testing

### 1. Test PeerJS Server
```bash
curl http://213.210.21.140:9000/peerjs
# Should return PeerJS server info
```

### 2. Test Camera Access
1. Open browser console
2. Navigate to exam page
3. Look for camera initialization logs
4. Check for any error messages

### 3. Test Environment Detection
```javascript
// In browser console
console.log(APP_CONFIG);
// Should show correct environment settings
```

## Deployment Checklist

### Production Deployment
- [ ] SSL certificate installed and valid
- [ ] PeerJS server running on port 9000
- [ ] Firewall allows port 9000
- [ ] Laravel APP_ENV=production
- [ ] Domain points to correct server IP
- [ ] Test camera access on HTTPS

### Local Development
- [ ] PeerJS server running locally
- [ ] Laravel APP_ENV=local
- [ ] Camera permissions granted in browser
- [ ] Test both HTTP and HTTPS access
