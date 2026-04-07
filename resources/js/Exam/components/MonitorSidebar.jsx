import React, { useState, useEffect, useRef } from 'react';
import {
    User, Camera, Video, VideoOff,
    RefreshCcw, Monitor, ShieldCheck,
    X, CheckCircle, Info, Eye, EyeOff
} from 'lucide-react';

const MonitorSidebar = ({
    user,
    percentage,
    isOpen,
    setIsOpen,
    companyColor,
    isRecording,
    connectionStatus,
    userTimetableId
}) => {
    const videoRef = useRef(null);
    const [cameraActive, setCameraActive] = useState(false);
    const [cameraVisible, setCameraVisible] = useState(true);
    const [recordingStatus, setRecordingStatus] = useState('Initializing...');

    useEffect(() => {
        setRecordingStatus(isRecording ? 'Recording' : 'Standby');
    }, [isRecording]);

    useEffect(() => {
        const startCamera = async () => {
            try {
                const stream = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
                if (videoRef.current) {
                    videoRef.current.srcObject = stream;
                    setCameraActive(true);
                    setRecordingStatus('Recording');
                }
            } catch (err) {
                console.error("Error accessing camera:", err);
                setCameraActive(false);
                setRecordingStatus('Camera Error');
            }
        };

        if (isOpen || window.innerWidth >= 1024) {
            startCamera();
        }

        return () => {
            if (videoRef.current && videoRef.current.srcObject) {
                const tracks = videoRef.current.srcObject.getTracks();
                tracks.forEach(track => track.stop());
            }
        };
    }, [isOpen]);

    return (
        <aside className={`
            fixed right-0 lg:relative z-50 h-full lg:h-auto w-80 bg-white border-l border-gray-200 transition-transform duration-300 ease-in-out
            ${isOpen ? 'translate-x-0' : 'translate-x-full lg:translate-x-0'}
        `}>
            {/* Header Mobile */}
            <div className="flex items-center justify-between p-4 border-b bg-gray-50 lg:hidden">
                <h3 className="font-bold text-gray-800">Profil & Monitoring</h3>
                <button onClick={() => setIsOpen(false)} className="text-gray-500 hover:text-gray-700">
                    <X className="w-6 h-6" />
                </button>
            </div>

            {/* Profile Section */}
            <div className="p-6 border-b bg-gray-50/30 flex flex-col items-center">
                <div className="w-20 h-20 bg-orange-600 rounded-full flex items-center justify-center text-white text-3xl font-bold shadow-lg mb-4 ring-4 ring-orange-50">
                    {user?.name ? user.name.substring(0, 2).toUpperCase() : 'ST'}
                </div>
                <h3 className="font-bold text-gray-800 text-lg text-center line-clamp-2">{user?.name || 'Student'}</h3>
                <p className="text-sm text-orange-600 font-bold tracking-wider mt-1">{user?.nim || 'Peserta Ujian'}</p>
                {/* <div className="mt-3 px-3 py-1 bg-orange-50 text-orange-700 rounded-full text-[10px] uppercase font-bold tracking-wider border border-orange-100 flex items-center gap-1">
                    <ShieldCheck className="w-3 h-3" /> Akun Terverifikasi
                </div> */}
            </div>

            {/* Camera Monitor */}
            <div className="p-5 border-b">
                <div className="flex items-center justify-between mb-4">
                    <h4 className="font-bold text-gray-800 flex items-center gap-2">
                        <Camera className="w-4 h-4 text-orange-600" /> Monitor Camera
                    </h4>
                    <div className="flex items-center gap-2">
                        <button
                            onClick={() => setCameraVisible(!cameraVisible)}
                            className="p-1 hover:bg-gray-100 rounded-md transition-colors text-gray-400 hover:text-orange-600"
                            title={cameraVisible ? "Sembunyikan Kamera" : "Tampilkan Kamera"}
                        >
                            {cameraVisible ? <Eye className="w-4 h-4" /> : <EyeOff className="w-4 h-4" />}
                        </button>
                        <span className={`px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-widest ${cameraActive ? 'bg-red-100 text-red-600 animate-pulse' : 'bg-gray-100 text-gray-500'}`}>
                            {cameraActive ? 'Live' : 'Off'}
                        </span>
                    </div>
                </div>
                <div className="relative aspect-video bg-black rounded-2xl overflow-hidden shadow-inner group">
                    <video
                        ref={videoRef}
                        autoPlay
                        muted
                        playsInline
                        className={`w-full h-full object-cover transition-opacity duration-500 ${cameraActive && cameraVisible ? 'opacity-100' : 'opacity-0'}`}
                    />
                    {(!cameraActive || !cameraVisible) && (
                        <div className="absolute inset-0 flex flex-col items-center justify-center text-gray-500 gap-2 bg-gray-50">
                            {cameraVisible ? (
                                <>
                                    <VideoOff className="w-8 h-8 opacity-20" />
                                    <span className="text-xs font-bold opacity-40 uppercase">Camera Inactive</span>
                                </>
                            ) : (
                                <>
                                    <EyeOff className="w-8 h-8 opacity-20 text-orange-600" />
                                    <span className="text-xs font-bold opacity-40 uppercase">Preview Hidden</span>
                                </>
                            )}
                        </div>
                    )}
                    <div className="absolute bottom-2 left-2 flex items-center gap-1.5 px-2 py-1 bg-black/40 backdrop-blur-md rounded-lg text-[10px] text-white font-bold uppercase border border-white/10">
                        <div className={`w-1.5 h-1.5 rounded-full ${cameraActive ? 'bg-red-500 animate-pulse' : 'bg-gray-500'}`} />
                        REC • {recordingStatus}
                    </div>
                </div>
                <div className="mt-4 p-3 bg-orange-50/50 rounded-xl border border-orange-100/50 flex gap-3">
                    <div className="w-8 h-8 bg-orange-100 rounded-lg flex items-center justify-center text-orange-600 flex-none self-center">
                        <Monitor className="w-4 h-4" />
                    </div>
                    <div className="flex-1">
                        <div className="text-[10px] text-gray-400 font-bold uppercase">Progres Ujian</div>
                        <div className="flex items-center justify-between mb-1.5">
                            <span className="text-sm font-bold text-orange-700">{Math.round(percentage)}%</span>
                            <span className="text-[10px] text-gray-400 font-medium">Terselesaikan</span>
                        </div>
                        <div className="w-full h-1.5 bg-gray-200 rounded-full overflow-hidden">
                            <div
                                className="h-full bg-orange-600 rounded-full transition-all duration-700 ease-out"
                                style={{ width: `${percentage}%` }}
                            />
                        </div>
                    </div>
                </div>
            </div>

            {/* Status Logs */}
            <div className="p-5 flex-1 overflow-y-auto custom-scrollbar">
                <div className="flex items-center justify-between mb-4">
                    <h4 className="text-xs font-bold text-gray-400 uppercase tracking-widest">Aktivitas Sesi</h4>
                </div>
                <div className="space-y-4">
                    <div className="flex gap-3">
                        <div className="w-6 h-6 bg-green-100 text-green-600 rounded-full flex items-center justify-center flex-none mt-0.5">
                            <CheckCircle className="w-3.5 h-3.5" />
                        </div>
                        <div>
                            <p className="text-xs font-bold text-gray-700">Status Streaming</p>
                            <p className="text-[10px] text-gray-400 mt-0.5">
                                {connectionStatus?.toLowerCase() === 'connected' ? 'Streaming aktif dan terpantau' : (connectionStatus || 'Menunggu koneksi...')}
                            </p>
                        </div>
                    </div>
                    <div className="flex gap-3">
                        <div className="w-6 h-6 bg-orange-100 text-orange-600 rounded-full flex items-center justify-center flex-none mt-0.5">
                            <RefreshCcw className="w-3.5 h-3.5" />
                        </div>
                        <div>
                            <p className="text-xs font-bold text-gray-700">Auto-sync Aktif</p>
                            <p className="text-[10px] text-gray-400 mt-0.5">Sinkronisasi data otomatis berjalan</p>
                        </div>
                    </div>
                </div>
            </div>

            {/* Sidebar Footer */}
            <div className="p-4 border-t bg-gray-50 flex items-center gap-2">
                <div className="p-2 bg-white rounded-lg border border-gray-200">
                    <Info className="w-4 h-4 text-orange-500" />
                </div>
                <p className="text-[10px] text-gray-500 leading-tight">
                    Keamanan ujian dipantau secara real-time. Pastikan wajah terlihat jelas di kamera.
                </p>
            </div>
        </aside>
    );
};

export default MonitorSidebar;
