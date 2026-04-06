import { useState, useEffect, useCallback } from 'react';
import axios from 'axios';
import { Room, RoomEvent, VideoPresets } from 'livekit-client';

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

    // Heartbeat every 30 seconds
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
                console.error("Heartbeat failed", error);
             }
        }, 30000);

        return () => clearInterval(heartbeat);
    }, [userTimetableId, isEnabled, connectionStatus]);

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
