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
            fixed right-0 lg:relative z-50 h-full lg:h-auto w-80 bg-white/80 backdrop-blur-2xl border-l border-white shadow-[-10px_0_30px_rgba(0,0,0,0.02)] transition-transform duration-300 ease-in-out
            ${isOpen ? 'translate-x-0' : 'translate-x-full lg:translate-x-0'}
        `}>
            {/* Header Mobile */}
            <div className="flex items-center justify-between p-4 border-b border-gray-100 bg-white/50 lg:hidden">
                <h3 className="font-bold text-gray-800">Profile & Monitoring 🚀</h3>
                <button onClick={() => setIsOpen(false)} className="text-gray-500 hover:text-gray-700">
                    <X className="w-6 h-6" />
                </button>
            </div>

            {/* Profile Section */}
            <div className="p-6 border-b border-gray-100 bg-white/50 flex flex-col items-center relative overflow-hidden">
                <div className="absolute -top-10 -right-10 w-32 h-32 bg-orange-100 rounded-full blur-2xl opacity-60"></div>
                <div className="relative w-24 h-24 rounded-full flex items-center justify-center text-white text-3xl font-black shadow-xl mb-4 bg-gradient-to-tr from-orange-500 to-amber-500 ring-4 ring-white">
                    {user?.name ? user.name.substring(0, 2).toUpperCase() : 'ST'}
                </div>
                <h3 className="font-black text-gray-800 text-xl text-center line-clamp-2 relative z-10">{user?.name || 'Student'}</h3>
                <p className="text-sm text-orange-500 font-bold tracking-wider mt-1 relative z-10">{user?.nim || 'Peserta Ujian'}</p>
            </div>

            {/* Camera Monitor */}
            <div className="p-5 border-b border-gray-100 bg-white/30">
                <div className="flex items-center justify-between mb-4">
                    <h4 className="font-black text-slate-700 flex items-center gap-2">
                        <Camera className="w-5 h-5 text-orange-500" /> Cam Monitor
                    </h4>
                    <div className="flex items-center gap-2">
                        <button
                            onClick={() => setCameraVisible(!cameraVisible)}
                            className="p-1.5 hover:bg-white rounded-lg transition-colors text-slate-400 hover:text-orange-500 hover:shadow-sm"
                            title={cameraVisible ? "Sembunyikan Kamera" : "Tampilkan Kamera"}
                        >
                            {cameraVisible ? <Eye className="w-4 h-4" /> : <EyeOff className="w-4 h-4" />}
                        </button>
                        <span className={`px-2 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest ${cameraActive ? 'bg-red-100 text-red-600 animate-pulse' : 'bg-slate-100 text-slate-500'}`}>
                            {cameraActive ? 'Live' : 'Off'}
                        </span>
                    </div>
                </div>
                <div className="relative aspect-video bg-slate-900 rounded-3xl overflow-hidden shadow-inner group border-4 border-slate-100">
                    <video
                        ref={videoRef}
                        autoPlay
                        muted
                        playsInline
                        className={`w-full h-full object-cover transition-opacity duration-500 ${cameraActive && cameraVisible ? 'opacity-100' : 'opacity-0'}`}
                    />
                    {(!cameraActive || !cameraVisible) && (
                        <div className="absolute inset-0 flex flex-col items-center justify-center text-slate-500 gap-2 bg-slate-50">
                            {cameraVisible ? (
                                <>
                                    <VideoOff className="w-8 h-8 opacity-20" />
                                    <span className="text-xs font-bold opacity-40 uppercase tracking-widest">Camera Inactive</span>
                                </>
                            ) : (
                                <>
                                    <EyeOff className="w-8 h-8 opacity-20 text-orange-500" />
                                    <span className="text-xs font-bold opacity-40 uppercase tracking-widest">Preview Hidden</span>
                                </>
                            )}
                        </div>
                    )}
                    <div className="absolute bottom-2 left-2 flex items-center gap-2 px-3 py-1.5 bg-black/50 backdrop-blur-md rounded-xl text-[10px] text-white font-bold uppercase border border-white/20 shadow-lg">
                        <div className={`w-2 h-2 rounded-full ${cameraActive ? 'bg-red-500 animate-pulse' : 'bg-gray-500'}`} />
                        REC • {recordingStatus}
                    </div>
                </div>
                <div className="mt-5 p-4 bg-gradient-to-r from-orange-50 to-amber-50 rounded-2xl border border-orange-100/50 flex gap-4 shadow-sm">
                    <div className="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-orange-500 flex-none self-center">
                        <Monitor className="w-5 h-5" />
                    </div>
                    <div className="flex-1">
                        <div className="text-xs text-orange-400 font-black uppercase tracking-wider mb-1">Progres Ujian</div>
                        <div className="flex items-center justify-between mb-2">
                            <span className="text-sm font-black text-orange-600">{Math.round(percentage)}%</span>
                            <span className="text-[10px] text-orange-400/80 font-bold uppercase">Selesai</span>
                        </div>
                        <div className="w-full h-2 bg-orange-100 rounded-full overflow-hidden shadow-inner">
                            <div
                                className="h-full bg-gradient-to-r from-orange-500 to-amber-400 rounded-full transition-all duration-700 ease-out"
                                style={{ width: `${percentage}%` }}
                            />
                        </div>
                    </div>
                </div>
            </div>

            {/* Status Logs */}
            <div className="p-5 flex-1 overflow-y-auto custom-scrollbar">
                <div className="flex items-center justify-between mb-5">
                    <h4 className="text-xs font-black text-slate-400 uppercase tracking-widest">Session Logs 📡</h4>
                </div>
                <div className="space-y-4">
                    <div className="flex gap-3 items-start bg-slate-50 p-3 rounded-xl border border-slate-100">
                        <div className="w-8 h-8 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center flex-none">
                            <CheckCircle className="w-4 h-4" />
                        </div>
                        <div>
                            <p className="text-xs font-bold text-slate-700">Status Streaming</p>
                            <p className="text-[10px] text-slate-500 mt-1 leading-relaxed">
                                {connectionStatus?.toLowerCase() === 'connected' ? 'Streaming aktif & terpantau aman.' : (connectionStatus || 'Streaming aktif dan terpantau')}
                            </p>
                        </div>
                    </div>
                    <div className="flex gap-3 items-start bg-slate-50 p-3 rounded-xl border border-slate-100">
                        <div className="w-8 h-8 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center flex-none">
                            <RefreshCcw className="w-4 h-4" />
                        </div>
                        <div>
                            <p className="text-xs font-bold text-slate-700">Auto-sync Aktif</p>
                            <p className="text-[10px] text-slate-500 mt-1 leading-relaxed">Sinkronisasi data otomatis berjalan lancar.</p>
                        </div>
                    </div>
                </div>
            </div>

            {/* Sidebar Footer */}
            <div className="p-4 border-t border-slate-100 bg-white/50 flex items-start gap-3 backdrop-blur">
                <div className="p-2 bg-gradient-to-br from-orange-50 to-amber-50 rounded-xl border border-orange-100 shadow-sm flex-none">
                    <Info className="w-4 h-4 text-orange-500" />
                </div>
                <p className="text-[10px] text-slate-500 leading-relaxed font-medium">
                    Keamanan ujian dipantau secara real-time. Pastikan wajahmu terlihat jelas di kamera ya! 👀
                </p>
            </div>
        </aside>
    );
};

export default MonitorSidebar;
