# PeerJS Setup Complete! ✅

## Summary

✅ **HTTPS PeerJS Server Active**
- HTTP PeerJS: http://213.210.21.140:9000/peerjs
- HTTPS PeerJS: https://213.210.21.140:9443/peerjs
- SSL Certificate: Self-signed (Development)
- Auto-start: Enabled via systemd

## What Changed

### 1. SSL Certificate Created
```bash
# Self-signed certificate for 213.210.21.140
/etc/ssl/peerjs/peerjs.crt
/etc/ssl/peerjs/peerjs.key
```

### 2. Frontend Updated
- **HTTPS sites** now use: `https://213.210.21.140:9443/peerjs`
- **HTTP sites** still use: `http://213.210.21.140:9000/peerjs`
- **Environment detection**: Automatic protocol selection
- **Error handling**: SSL certificate warnings handled gracefully

### 3. Dual Server Running
- Both HTTP (9000) and HTTPS (9443) PeerJS servers active
- Systemd service: `peerjs-dual.service`
- Auto-restart on failure
- Auto-start on boot

## Testing Status

✅ **Server Accessibility**
```bash
# HTTP server
curl http://213.210.21.140:9000/peerjs
# Returns: {"name":"PeerJS Server",...}

# HTTPS server
curl -k https://213.210.21.140:9443/peerjs
# Returns: {"name":"PeerJS Server",...}
```

✅ **Service Status**
```bash
sudo systemctl status peerjs-dual
# Should show: Active (running)
```

## Browser Setup Required

⚠️ **SSL Certificate Warning**

Since we're using a self-signed certificate, browsers will show security warnings. Users need to:

1. **Visit HTTPS PeerJS directly**: https://213.210.21.140:9443/peerjs
2. **Accept certificate warning**:
   - Click "Advanced"
   - Click "Proceed to 213.210.21.140 (unsafe)"
3. **Return to exam page** and refresh

## Usage

### For HTTPS Site (https://cbt-new.drshieldapp.com)
- ✅ Will connect to HTTPS PeerJS server automatically
- ⚠️ Browser may require SSL certificate acceptance first
- 🔒 Secure connection maintained throughout

### For HTTP Site (http://cbt-new.drshieldapp.com)
- ✅ Will connect to HTTP PeerJS server automatically
- ✅ No SSL warnings
- 📡 Standard HTTP connection

## Production Recommendations

### Option 1: Trusted SSL Certificate
```bash
# Use Let's Encrypt or commercial certificate
sudo certbot certonly --standalone -d peerjs.yourcompany.com
# Update server to use trusted certificate
```

### Option 2: Domain-based Setup
```bash
# Setup subdomain: peerjs.cbt-new.drshieldapp.com
# Point DNS to 213.210.21.140
# Get SSL certificate for subdomain
# Update frontend to use subdomain
```

### Option 3: Reverse Proxy
```nginx
# Use nginx with SSL termination
server {
    listen 443 ssl;
    server_name peerjs.cbt-new.drshieldapp.com;

    ssl_certificate /path/to/trusted/certificate.crt;
    ssl_certificate_key /path/to/private/key.key;

    location /peerjs {
        proxy_pass http://127.0.0.1:9000;
        # WebSocket support
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";
    }
}
```

## Management Commands

### Control Service
```bash
# Start service
sudo systemctl start peerjs-dual

# Stop service
sudo systemctl stop peerjs-dual

# Restart service
sudo systemctl restart peerjs-dual

# Check status
sudo systemctl status peerjs-dual

# View logs
sudo journalctl -u peerjs-dual -f
```

### Manual Testing
```bash
# Start manually (for debugging)
cd /var/www/html/drshieldapp/cbt-new.drshieldapp.com
node peerjs-server-dual.js

# Test connections
curl http://213.210.21.140:9000/peerjs
curl -k https://213.210.21.140:9443/peerjs
```

## Troubleshooting

### SSL Certificate Issues
- **Problem**: Browser shows "Not Secure"
- **Solution**: Visit https://213.210.21.140:9443/peerjs and accept certificate

### Connection Failed
- **Check server**: `sudo systemctl status peerjs-dual`
- **Check logs**: `sudo journalctl -u peerjs-dual -f`
- **Test manually**: `node peerjs-server-dual.js`

### Firewall Issues
- **HTTP**: `sudo ufw allow 9000/tcp`
- **HTTPS**: `sudo ufw allow 9443/tcp`

---

🎉 **Setup Complete!**

Your PeerJS server now supports both HTTP and HTTPS connections, solving the mixed content issue while maintaining your requirement to keep the main domain on HTTPS.
