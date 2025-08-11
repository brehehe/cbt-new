#!/bin/bash

# Deploy script untuk PeerJS Server
echo "🚀 Deploying PeerJS Server..."

# Install dependencies
echo "📦 Installing dependencies..."
npm install

# Create logs directory if not exists
mkdir -p logs

# Stop existing PM2 process
echo "🛑 Stopping existing PeerJS server..."
pm2 stop peerjs-server || true
pm2 delete peerjs-server || true

# Start with PM2
echo "▶️ Starting PeerJS server with PM2..."
pm2 start ecosystem.config.js --env production

# Save PM2 configuration
pm2 save

# Setup PM2 startup script
pm2 startup

echo "✅ PeerJS Server deployed successfully!"
echo "📊 Monitor with: pm2 monit"
echo "📋 Check status: pm2 status"
echo "📄 View logs: pm2 logs peerjs-server"
