import { useState, useEffect, useCallback } from 'react';
import axios from 'axios';
import { Room, RoomEvent, VideoPresets } from 'livekit-client';

// Global interceptor: jika session expired/dihapus admin => redirect login
axios.interceptors.response.use(
    response => response,
    error => {
        if (error.response && error.response.status === 401) {
            // Tandai agar tidak muncul konfirmasi leave
            window.isFinishingExam = true;
            window.location.href = '/login';
        }
        return Promise.reject(error);
    }
);

export const useLiveSession = (userTimetableId, isEnabled) => {
    const [connectionStatus, setConnectionStatus] = useState('disconnected');

    const connectToLiveKit = useCallback(async () => {
        if (!isEnabled) return;

        try {
            // 1. Get token from backend
            const { data } = await axios.get(`/api/exam/live-session/${userTimetableId}/token`);
            const { serverUrl, token } = data;

            // 2. Initialize and connect room
            const room = new Room({
                adaptiveStream: true,
                dynacast: true,
                videoCaptureDefaults: {
                    resolution: VideoPresets.h360.resolution, // Lightweight resolution
                }
            });

            await room.connect(serverUrl, token);
            setConnectionStatus('connected');

            // 3. Publish local video
            const stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
            const tracks = stream.getVideoTracks();
            if (tracks.length > 0) {
                await room.localParticipant.publishTrack(tracks[0]);
            }

            room.on(RoomEvent.Disconnected, () => {
                setConnectionStatus('Disconnected');
            });

            return room;
        } catch (error) {
            console.error("LiveKit connection failed:", error);
            setConnectionStatus('Connection Error');
        }
    }, [userTimetableId, isEnabled]);

    // Heartbeat + session check every 15 seconds
    useEffect(() => {
        if (!isEnabled) return;

        const heartbeat = setInterval(async () => {
             try {
                await axios.get(`/api/exam/live-session/${userTimetableId}/update`, {
                    params: {
                        connection_status: connectionStatus,
                        camera_status: connectionStatus === 'Connected' ? 'active' : 'inactive'
                    }
                });
             } catch (error) {
                // 401 sudah ditangani oleh interceptor di atas
                console.error("Heartbeat failed", error);
             }
        }, 15000);

        return () => clearInterval(heartbeat);
    }, [userTimetableId, isEnabled, connectionStatus]);

    // Polling deteksi force-logout oleh admin (dual strategy, setiap 8 detik)
    // Layer 1: /ping → 401 jika session Redis dihapus
    // Layer 2: /{id}/status → cek is_active ExamLiveSession (selalu diupdate forceLogoutUser)
    useEffect(() => {
        const checkSession = setInterval(async () => {
            try {
                // Layer 1: cek auth via ping
                const pingRes = await axios.get('/api/exam/ping');
                const ct = pingRes.headers?.['content-type'] ?? '';
                if (ct.includes('text/html')) {
                    // Dapat HTML = di-redirect ke login (302 → 200)
                    window.isFinishingExam = true;
                    window.location.href = '/login';
                    return;
                }

                // Layer 2: cek status live session langsung dari DB
                const statusRes = await axios.get(`/api/exam/${userTimetableId}/status`);
                if (statusRes.data?.redirect) {
                    window.isFinishingExam = true;
                    window.location.href = statusRes.data.redirect;
                }
            } catch (error) {
                // 401 dari /ping ditangani interceptor → redirect /login
                // Error network/500 diabaikan agar tidak false-positive
            }
        }, 8000); // setiap 8 detik

        return () => clearInterval(checkSession);
    }, [userTimetableId]);

    useEffect(() => {
        let roomInstance;
        if (isEnabled) {
            connectToLiveKit().then(room => {
                roomInstance = room;
            });
        }
        return () => {
            if (roomInstance) roomInstance.disconnect();
        };
    }, [isEnabled, connectToLiveKit]);

    return { connectionStatus };
};
