// WebRTC ICE Servers Configuration for CBT
// Include this in your frontend application

const ICE_SERVERS = [
    // STUN server from Cloudflare (free, public)
    {
        urls: 'stun:stun.cloudflare.com:3478'
    },
    // TURN server (your private server)
    {
        urls: 'turn:procbt.id:3478?transport=udp',
        username: 'admin',
        credential: 'ProcbtSecure123!'
    },
    {
        urls: 'turn:procbt.id:3478?transport=tcp',
        username: 'admin',
        credential: 'ProcbtSecure123!'
    },
    // TURN over TLS (secure)
    {
        urls: 'turns:procbt.id:5349?transport=tcp',
        username: 'admin',
        credential: 'ProcbtSecure123!'
    }
];

// PeerJS Configuration
const peerConfig = {
    host: 'procbt.id',
    port: 443,
    path: '/peerjs',
    secure: true,
    config: {
        iceServers: ICE_SERVERS,
        iceTransportPolicy: 'all', // or 'relay' to force TURN
    },
    debug: 2 // Set to 0 in production
};

// Usage Example:
// const peer = new Peer(peerId, peerConfig);

export { ICE_SERVERS, peerConfig };
