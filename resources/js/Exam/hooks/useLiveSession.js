import { useState, useEffect, useCallback } from 'react';
import axios from 'axios';
import { Room, RoomEvent, VideoPresets } from 'livekit-client';
import Swal from 'sweetalert2';

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

export const useLiveSession = (userTimetableId, isEnabled, sharedStream, onTimeSync) => {
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
            let stream = sharedStream;
            if (!stream) {
                stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
            }
            const tracks = stream.getVideoTracks();
            if (tracks.length > 0) {
                // Clone the track so LiveKit doesn't stop the original shared track on disconnect/cleanup
                const trackToPublish = tracks[0].clone();
                await room.localParticipant.publishTrack(trackToPublish);
            }

            room.on(RoomEvent.Disconnected, () => {
                setConnectionStatus('Disconnected');
            });

            return room;
        } catch (error) {
            console.error("LiveKit connection failed:", error);
            setConnectionStatus('Connection Error');
        }
    }, [userTimetableId, isEnabled, sharedStream]);

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
                } else {
                    if (statusRes.data?.remainingTime !== undefined && onTimeSync) {
                        onTimeSync(statusRes.data.remainingTime, statusRes.data.paused);
                    }

                    // Deteksi pesan peringatan langsung dari pengawas
                    if (statusRes.data?.supervisorMessage) {
                        const msg = statusRes.data.supervisorMessage;
                        if (window.lastSupervisorMsgId !== msg.id) {
                            window.lastSupervisorMsgId = msg.id;

                            // Bunyikan nada alert jika didukung audio browser
                            try {
                                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                                const osc = audioCtx.createOscillator();
                                const gain = audioCtx.createGain();
                                osc.type = 'triangle';
                                osc.frequency.setValueAtTime(440, audioCtx.currentTime);
                                osc.frequency.exponentialRampToValueAtTime(880, audioCtx.currentTime + 0.3);
                                gain.gain.setValueAtTime(0.15, audioCtx.currentTime);
                                gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.3);
                                osc.connect(gain);
                                gain.connect(audioCtx.destination);
                                osc.start();
                                osc.stop(audioCtx.currentTime + 0.3);
                            } catch (e) {
                                // Abaikan jika audio blocked oleh browser
                            }

                            Swal.fire({
                                title: '⚠️ PERINGATAN PENGAWAS',
                                html: `
                                    <div class="text-left text-sm space-y-3 p-1">
                                        <div class="bg-red-50 border-l-4 border-red-500 p-3.5 rounded-r text-red-900 font-semibold text-base leading-relaxed shadow-sm">
                                            "${msg.text}"
                                        </div>
                                        <div class="flex justify-between items-center text-xs text-slate-500 pt-2 border-t border-slate-200">
                                            <span>Pengawas: <strong class="text-slate-800 font-semibold">${msg.sender}</strong></span>
                                            <span class="font-mono">${msg.timestamp}</span>
                                        </div>
                                    </div>
                                `,
                                icon: 'warning',
                                confirmButtonText: 'Saya Mengerti',
                                confirmButtonColor: '#dc2626',
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                            }).then(() => {
                                axios.post(`/api/exam/message/${msg.id}/ack`).catch(err => console.error('Failed to ack message', err));
                            });
                        }
                    }
                }
            } catch (error) {
                // 401 dari /ping ditangani interceptor → redirect /login
                // Error network/500 diabaikan agar tidak false-positive
            }
        }, 8000); // setiap 8 detik

        return () => clearInterval(checkSession);
    }, [userTimetableId, onTimeSync]);

    useEffect(() => {
        let roomInstance;
        if (isEnabled && sharedStream) {
            connectToLiveKit().then(room => {
                roomInstance = room;
            });
        }
        return () => {
            if (roomInstance) roomInstance.disconnect();
        };
    }, [isEnabled, connectToLiveKit, sharedStream]);

    return { connectionStatus };
};
