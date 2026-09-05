import React, { useState, useEffect, useRef } from 'react';
import {
    Camera, Video, VideoOff, RefreshCcw,
    ShieldCheck, X, Info, Wifi, AlertTriangle, CheckCircle
} from 'lucide-react';

const Dot = ({ active, label, color }) => (
    <div className="flex items-center gap-2">
        <span className="w-2 h-2 rounded-full flex-none" style={{ backgroundColor: active ? color : '#d1d5db' }} />
        <span className="text-[11px] text-gray-600 font-medium leading-none">{label}</span>
    </div>
);

const MonitorSidebar = ({
    user,
    alertCount = 0,
    percentage = 0,
    isOpen,
    setIsOpen,
    companyColor = '#1e3a5f',
    isRecording = false,
    userTimetableId,
    isCameraEnabled = true,
    connectionStatus = 'connected',
    cameraStream = null,
    cameraError = false,
    startCamera,
    stopCamera,
}) => {
    const videoRef = useRef(null);
    const timerRef = useRef(null);

    const cameraActive = !!cameraStream;
    const [elapsed, setElapsed] = useState(0);
    const [netStats, setNetStats] = useState({ ping: 14, upload: '1.6', bandwidth: 40 });

    /* ── Recording timer ── */
    useEffect(() => {
        if (isRecording && cameraActive) {
            timerRef.current = setInterval(() => setElapsed(p => p + 1), 1000);
        } else {
            clearInterval(timerRef.current);
        }
        return () => clearInterval(timerRef.current);
    }, [isRecording, cameraActive]);

    const fmtTime = (s) => `${String(Math.floor(s / 60)).padStart(2, '0')}:${String(s % 60).padStart(2, '0')}`;

    /* bind shared stream to video element */
    useEffect(() => {
        if (videoRef.current) {
            videoRef.current.muted = true;
            videoRef.current.srcObject = cameraStream;
            if (cameraStream) {
                videoRef.current.play().catch(() => { });
            }
        }
    }, [cameraStream]);

    /* also start when mobile panel opens */
    useEffect(() => {
        if (isOpen && isCameraEnabled && !cameraActive && !cameraError) {
            startCamera();
        }
    }, [isOpen, isCameraEnabled, cameraActive, cameraError, startCamera]);

    /* network stats simulation */
    useEffect(() => {
        const iv = setInterval(() => setNetStats({
            ping: Math.floor(Math.random() * 12) + 8,
            upload: (Math.random() + 1.2).toFixed(1),
            bandwidth: Math.floor(Math.random() * 8) + 36,
        }), 5000);
        return () => clearInterval(iv);
    }, []);

    const isConnected = !connectionStatus || connectionStatus === 'connected';

    return (
        <aside
            className={`h-full flex-none flex flex-col bg-white border-l border-gray-200 overflow-y-auto overflow-x-hidden fixed lg:static top-0 right-0 z-50 lg:z-0 shadow-2xl lg:shadow-none transition-transform lg:transition-none duration-300 ${isOpen ? 'translate-x-0' : 'translate-x-full lg:translate-x-0'}`}
            style={{ width: 256 }}
        >
            {/* Header */}
            <div className="flex-none flex items-center justify-between px-3 py-2.5 bg-white border-b border-gray-100">
                <div className="flex items-center gap-2">
                    <ShieldCheck className="w-4 h-4" style={{ color: companyColor }} />
                    <span className="text-xs font-bold tracking-wider uppercase" style={{ color: companyColor }}>
                        Pengawas Aktif
                    </span>
                </div>
                <button onClick={() => setIsOpen(false)} className="lg:hidden text-gray-400 hover:text-gray-600">
                    <X className="w-4 h-4" />
                </button>
            </div>

            {/* Camera feed */}
            {isCameraEnabled && (
                <div className="flex-none mx-2 mt-2 rounded-xl overflow-hidden bg-gray-900 border border-gray-700 relative"
                    style={{ aspectRatio: '4/3' }}>
                    <video
                        ref={videoRef}
                        autoPlay
                        muted
                        playsInline
                        className="absolute inset-0 w-full h-full object-cover"
                        style={{ opacity: cameraActive ? 1 : 0, transition: 'opacity 0.3s' }}
                    />
                    {!cameraActive && !cameraError && (
                        <div className="absolute inset-0 flex flex-col items-center justify-center gap-2">
                            <Camera className="w-8 h-8 text-gray-500 animate-pulse" />
                            <span className="text-gray-400 text-xs">Menghubungkan kamera...</span>
                        </div>
                    )}
                    {cameraError && (
                        <div className="absolute inset-0 flex flex-col items-center justify-center gap-2">
                            <VideoOff className="w-8 h-8 text-red-400" />
                            <span className="text-red-400 text-xs text-center px-4">Kamera tidak tersedia</span>
                        </div>
                    )}
                    {/* Indicators */}
                    {cameraActive && (
                        <div className="absolute top-2 left-2 right-2 flex items-center justify-between pointer-events-none">
                            <div className="flex items-center gap-1 bg-black/70 px-2 py-0.5 rounded-full">
                                <span className="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse" />
                                <span className="text-white text-[10px] font-bold">LIVE</span>
                            </div>
                            {isRecording && (
                                <div className="flex items-center gap-1 bg-black/70 px-2 py-0.5 rounded-full">
                                    <Video className="w-2.5 h-2.5 text-red-400" />
                                    <span className="text-white text-[10px] font-bold">REC {fmtTime(elapsed)}</span>
                                </div>
                            )}
                        </div>
                    )}
                </div>
            )}

            {/* Camera action buttons */}
            {isCameraEnabled && (
                <div className="flex-none flex gap-2 mx-2 mt-2">
                    <button
                        onClick={() => cameraActive ? stopCamera() : startCamera()}
                        className="flex-1 flex items-center justify-center gap-1.5 py-1.5 rounded-lg border border-gray-200 bg-gray-50 hover:bg-gray-100 text-[11px] font-semibold text-gray-600 transition-all"
                    >
                        <Camera className="w-3.5 h-3.5" />
                        Test Kamera
                    </button>
                    <button
                        onClick={startCamera}
                        className="flex-1 flex items-center justify-center gap-1.5 py-1.5 rounded-lg border border-gray-200 bg-gray-50 hover:bg-gray-100 text-[11px] font-semibold text-gray-600 transition-all"
                    >
                        <RefreshCcw className="w-3.5 h-3.5" />
                        Reconnect
                    </button>
                </div>
            )}

            {/* Recording stats */}
            {isCameraEnabled && (
                <div className="flex-none mx-2 mt-3 bg-gray-50 rounded-xl border border-gray-100 p-3 space-y-2">
                    {[
                        { label: 'Resolusi', value: '720p (HD)', style: { color: '#374151', fontWeight: 700 } },
                        {
                            label: 'Status Merekam', value: isRecording ? 'Aktif' : 'Tidak Aktif',
                            style: { color: isRecording ? '#dc2626' : '#9ca3af', fontWeight: 700 },
                            dot: isRecording
                        },
                        { label: 'Antre Unggah', value: 'Tersinkron', style: { color: '#16a34a', fontWeight: 700 }, check: true },
                    ].map((row, i) => (
                        <div key={i} className="flex justify-between items-center">
                            <span className="text-[11px] text-gray-500 font-medium">{row.label}</span>
                            <span className="text-[11px] flex items-center gap-1" style={row.style}>
                                {row.dot && <span className="w-1.5 h-1.5 bg-red-500 rounded-full animate-pulse" />}
                                {row.check && <CheckCircle className="w-3 h-3 text-green-500" />}
                                {row.value}
                            </span>
                        </div>
                    ))}
                </div>
            )}

            {/* Violations */}
            <div className="flex-none mx-2 mt-3 bg-gray-50 rounded-xl border border-gray-100 p-3 space-y-2">
                <div className="flex justify-between items-center">
                    <span className="text-[11px] text-gray-500 font-medium">Pelanggaran Terdeteksi</span>
                    <span className="text-[11px] font-black flex items-center gap-1"
                        style={{ color: alertCount > 0 ? '#dc2626' : '#16a34a' }}>
                        {alertCount > 0 && <AlertTriangle className="w-3 h-3" />}
                        {alertCount} Pelanggaran
                    </span>
                </div>
                <div className="flex justify-between items-center">
                    <span className="text-[11px] text-gray-500 font-medium">Sisa Toleransi Ujian</span>
                    <span className="text-[11px] font-bold text-amber-600">{Math.max(0, 5 - alertCount)} kali</span>
                </div>
            </div>
        </aside>
    );
};

export default MonitorSidebar;
