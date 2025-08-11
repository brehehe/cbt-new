module.exports = {
  apps: [
    {
      name: 'peerjs-server',
      script: 'peerjs-server.js',
      instances: 1,
      autorestart: true,
      watch: false,
      max_memory_restart: '1G',
      env: {
        NODE_ENV: 'production',
        PORT: 9000
      },
      env_production: {
        NODE_ENV: 'production',
        PORT: 9000
      },
      error_file: './logs/peerjs-error.log',
      out_file: './logs/peerjs-out.log',
      log_file: './logs/peerjs-combined.log',
      time: true
    }
  ]
};
