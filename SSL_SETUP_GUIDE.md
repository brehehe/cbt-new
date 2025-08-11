# SSL Setup Guide for PeerJS Server

## Quick Setup Commands

### Option 1: Self-Signed Certificate (for testing)
```bash
# Create SSL directory
sudo mkdir -p /etc/ssl/peerjs
cd /etc/ssl/peerjs

# Generate self-signed certificate
sudo openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
  -keyout peerjs.key \
  -out peerjs.crt \
  -subj "/C=ID/ST=Jakarta/L=Jakarta/O=CBT/OU=PeerJS/CN=213.210.21.140"

# Set permissions
sudo chmod 600 peerjs.key
sudo chmod 644 peerjs.crt

# Update server script
export SSL_CERT_PATH="/etc/ssl/peerjs/peerjs.crt"
export SSL_KEY_PATH="/etc/ssl/peerjs/peerjs.key"

# Start dual server
node peerjs-server-dual.js
```

### Option 2: Let's Encrypt (for production)
```bash
# Install certbot
sudo apt update
sudo apt install certbot

# Stop any service using port 80/443 temporarily
sudo systemctl stop nginx

# Get certificate (replace with your domain if you have one)
sudo certbot certonly --standalone --email admin@yourcompany.com --agree-tos --no-eff-email -d peerjs.yourcompany.com

# Or for IP-based certificate (limited browser support)
# Note: Most browsers won't accept IP-based certificates from Let's Encrypt

# Copy certificates to PeerJS directory
sudo mkdir -p /etc/ssl/peerjs
sudo cp /etc/letsencrypt/live/peerjs.yourcompany.com/fullchain.pem /etc/ssl/peerjs/peerjs.crt
sudo cp /etc/letsencrypt/live/peerjs.yourcompany.com/privkey.pem /etc/ssl/peerjs/peerjs.key

# Set permissions
sudo chmod 600 /etc/ssl/peerjs/peerjs.key
sudo chmod 644 /etc/ssl/peerjs/peerjs.crt

# Start nginx back
sudo systemctl start nginx

# Start dual server
export SSL_CERT_PATH="/etc/ssl/peerjs/peerjs.crt"
export SSL_KEY_PATH="/etc/ssl/peerjs/peerjs.key"
node peerjs-server-dual.js
```

### Option 3: Use Existing Certificate
```bash
# If you already have SSL certificates, copy them:
sudo cp /path/to/your/certificate.crt /etc/ssl/peerjs/peerjs.crt
sudo cp /path/to/your/private.key /etc/ssl/peerjs/peerjs.key

# Set permissions
sudo chmod 600 /etc/ssl/peerjs/peerjs.key
sudo chmod 644 /etc/ssl/peerjs/peerjs.crt

# Start server
export SSL_CERT_PATH="/etc/ssl/peerjs/peerjs.crt"
export SSL_KEY_PATH="/etc/ssl/peerjs/peerjs.key"
node peerjs-server-dual.js
```

## Testing SSL Configuration

### Test HTTPS PeerJS Server
```bash
# Test HTTPS connection
curl -k https://213.210.21.140:9443/peerjs

# Should return PeerJS server info
```

### Test from Browser
```javascript
// In browser console on https://cbt-new.drshieldapp.com
fetch('https://213.210.21.140:9443/peerjs')
  .then(response => response.text())
  .then(data => console.log('✅ HTTPS PeerJS accessible:', data))
  .catch(error => console.log('❌ HTTPS PeerJS failed:', error));
```

## Systemd Service (Auto-start)

Create service file:
```bash
sudo nano /etc/systemd/system/peerjs-dual.service
```

```ini
[Unit]
Description=PeerJS Dual Server (HTTP + HTTPS)
After=network.target

[Service]
Type=simple
User=www-data
WorkingDirectory=/var/www/html/drshieldapp/cbt-new.drshieldapp.com
ExecStart=/usr/bin/node peerjs-server-dual.js
Restart=always
RestartSec=10
Environment=NODE_ENV=production
Environment=SSL_CERT_PATH=/etc/ssl/peerjs/peerjs.crt
Environment=SSL_KEY_PATH=/etc/ssl/peerjs/peerjs.key

[Install]
WantedBy=multi-user.target
```

Enable and start service:
```bash
sudo systemctl daemon-reload
sudo systemctl enable peerjs-dual.service
sudo systemctl start peerjs-dual.service

# Check status
sudo systemctl status peerjs-dual.service

# Check logs
sudo journalctl -u peerjs-dual.service -f
```

## Firewall Configuration

```bash
# Allow HTTPS PeerJS port
sudo ufw allow 9443/tcp

# Check current rules
sudo ufw status
```

## Nginx Reverse Proxy Alternative

If you prefer using nginx instead of SSL certificates directly:

```nginx
# /etc/nginx/sites-available/peerjs-ssl
server {
    listen 9443 ssl;
    server_name 213.210.21.140;

    # Use your existing SSL certificates
    ssl_certificate /path/to/your/certificate.crt;
    ssl_certificate_key /path/to/your/private.key;

    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers HIGH:!aNULL:!MD5;

    location /peerjs {
        proxy_pass http://127.0.0.1:9000;
        proxy_set_header Host $host;
        proxy_set_header X-Real-IP $remote_addr;
        proxy_set_header X-Forwarded-For $proxy_add_x_forwarded_for;
        proxy_set_header X-Forwarded-Proto $scheme;

        # WebSocket support
        proxy_http_version 1.1;
        proxy_set_header Upgrade $http_upgrade;
        proxy_set_header Connection "upgrade";

        # CORS
        add_header Access-Control-Allow-Origin *;
        add_header Access-Control-Allow-Methods "GET, POST, OPTIONS";
        add_header Access-Control-Allow-Headers "Content-Type, Authorization";
    }
}
```

Enable nginx config:
```bash
sudo ln -s /etc/nginx/sites-available/peerjs-ssl /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

## Verification

After setup, verify both servers are working:

1. **HTTP**: http://213.210.21.140:9000/peerjs
2. **HTTPS**: https://213.210.21.140:9443/peerjs

The client code will automatically detect and use the HTTPS server when running on HTTPS domain.
