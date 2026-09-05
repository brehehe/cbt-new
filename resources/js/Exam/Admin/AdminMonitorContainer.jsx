import React, { useState, useEffect, useCallback } from 'react';
import axios from 'axios';
import { Room, RoomEvent, VideoPresets } from 'livekit-client';
import StudentGrid from './components/StudentGrid';
import StudentDetailModal from './components/StudentDetailModal';

const AdminMonitorContainer = ({ timetableId }) => {
    const [sessions, setSessions] = useState([]);
    const [room, setRoom] = useState(null);
    const [token, setToken] = useState(null);
    const [serverUrl, setServerUrl] = useState(null);
    const [loading, setLoading] = useState(true);
    const [error, setError] = useState(null);
    const [stats, setStats] = useState({ total: 0, active: 0, warning: 0, error: 0 });
    const [selectedSession, setSelectedSession] = useState(null);

    // 1. Fetch Session Data
    const fetchSessions = useCallback(async () => {
        try {
            const { data } = await axios.get(`/api/exam/admin/monitoring/${timetableId}/sessions`);
            if (data.success) {
                setSessions(data.sessions);

                // Calculate stats
                const newStats = data.sessions.reduce((acc, s) => {
                    acc.total++;
                    if (s.connection_status === 'connected') acc.active++;
                    if (s.alert_count >= 3 || s.warning_count >= 5) acc.warning++;
                    if (s.connection_status === 'disconnected') acc.error++;
                    return acc;
                }, { total: 0, active: 0, warning: 0, error: 0 });
                setStats(newStats);
            }
        } catch (err) {
            console.error("Failed to fetch sessions:", err);
            setError("Gagal mengambil data sesi aktif.");
        } finally {
            setLoading(false);
        }
    }, [timetableId]);

    // 2. Connect to LiveKit Room
    const connectToRoom = useCallback(async () => {
        try {
            const { data } = await axios.get(`/api/exam/admin/monitoring/${timetableId}/token`);
            const { serverUrl, token } = data;
            setServerUrl(serverUrl);
            setToken(token);

            const newRoom = new Room({
                adaptiveStream: true, // Crucial for 1000+ streams
                dynacast: true,
                videoCaptureDefaults: {
                    resolution: VideoPresets.h90.resolution, // Low res for grid
                }
            });

            await newRoom.connect(serverUrl, token);
            setRoom(newRoom);
            console.log("Connected to LiveKit as Admin");

        } catch (err) {
            console.error("LiveKit Admin connection failed:", err);
            setError("Gagal terhubung ke server LiveKit.");
        }
    }, [timetableId]);

    useEffect(() => {
        if (selectedSession) {
            const updated = sessions.find(s => s.id === selectedSession.id);
            if (updated) setSelectedSession(updated);
        }
    }, [sessions]);

    useEffect(() => {
        fetchSessions();
        connectToRoom();

        // Refresh session list every 10 seconds
        const interval = setInterval(fetchSessions, 10000);
        return () => {
            clearInterval(interval);
            if (room) room.disconnect();
        };
    }, [timetableId]);

    if (loading && !sessions.length) {
        return (
            <div className="flex items-center justify-center min-h-[400px]">
                <div className="animate-spin rounded-full h-12 w-12 border-b-2 border-orange-600"></div>
            </div>
        );
    }

    return (
        <div className="p-4">
            {/* Header Stats */}
            <div className="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div className="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                    <p className="text-sm font-medium text-gray-500">Total Peserta</p>
                    <p className="text-2xl font-bold text-gray-900">{stats.total}</p>
                </div>
                <div className="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                    <p className="text-sm font-medium text-gray-500">Aktif (Kamera)</p>
                    <p className="text-2xl font-bold text-green-600">{stats.active}</p>
                </div>
                <div className="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                    <p className="text-sm font-medium text-gray-500">Peringatan</p>
                    <p className="text-2xl font-bold text-yellow-600">{stats.warning}</p>
                </div>
                <div className="bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                    <p className="text-sm font-medium text-gray-500">Terputus</p>
                    <p className="text-2xl font-bold text-red-600">{stats.error}</p>
                </div>
            </div>

            {error && (
                <div className="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                    {error}
                </div>
            )}

            {/* Student Grid */}
            <StudentGrid
                sessions={sessions}
                room={room}
                onDetail={setSelectedSession}
            />

            {/* Modal Detail Overlay */}
            {selectedSession && (
                <StudentDetailModal
                    session={selectedSession}
                    room={room}
                    onClose={() => setSelectedSession(null)}
                />
            )}
        </div>
    );
};

export default AdminMonitorContainer;
