import React, { useState, useEffect, useRef } from 'react';
import {
    X, ZoomIn, ZoomOut, RotateCw, RefreshCw,
    FileText, File, Play, Pause, Music, Volume2, Video
} from 'lucide-react';

const MediaViewerModal = ({ isOpen, onClose, url, type, title, companyColor = '#1e3a5f' }) => {
    if (!isOpen || !url) return null;

    const [zoom, setZoom] = useState(1);
    const [rotation, setRotation] = useState(0);
    const [isPlaying, setIsPlaying] = useState(false);
    const [currentTime, setCurrentTime] = useState(0);
    const [duration, setDuration] = useState(0);
    const [volume, setVolume] = useState(1);

    const audioRef = useRef(null);

    // Reset image transform on url change
    useEffect(() => {
        setZoom(1);
        setRotation(0);
        setIsPlaying(false);
        setCurrentTime(0);
        setDuration(0);
    }, [url]);

    // Handle audio controls
    useEffect(() => {
        const audio = audioRef.current;
        if (!audio) return;

        const handleTimeUpdate = () => setCurrentTime(audio.currentTime);
        const handleLoadedMetadata = () => setDuration(audio.duration);
        const handleEnded = () => setIsPlaying(false);

        audio.addEventListener('timeupdate', handleTimeUpdate);
        audio.addEventListener('loadedmetadata', handleLoadedMetadata);
        audio.addEventListener('ended', handleEnded);

        return () => {
            audio.removeEventListener('timeupdate', handleTimeUpdate);
            audio.removeEventListener('loadedmetadata', handleLoadedMetadata);
            audio.removeEventListener('ended', handleEnded);
        };
    }, [url, type]);

    const togglePlay = () => {
        if (!audioRef.current) return;
        if (isPlaying) {
            audioRef.current.pause();
            setIsPlaying(false);
        } else {
            audioRef.current.play()
                .then(() => setIsPlaying(true))
                .catch(err => console.warn('Audio play failed:', err));
        }
    };

    const handleProgressChange = (e) => {
        if (!audioRef.current) return;
        const val = parseFloat(e.target.value);
        audioRef.current.currentTime = val;
        setCurrentTime(val);
    };

    const handleVolumeChange = (e) => {
        if (!audioRef.current) return;
        const val = parseFloat(e.target.value);
        audioRef.current.volume = val;
        setVolume(val);
    };

    const formatAudioTime = (time) => {
        if (isNaN(time)) return '0:00';
        const mins = Math.floor(time / 60);
        const secs = Math.floor(time % 60);
        return `${mins}:${secs < 10 ? '0' : ''}${secs}`;
    };

    // Close on ESC
    useEffect(() => {
        const handleKeyDown = (e) => {
            if (e.key === 'Escape') onClose();
        };
        window.addEventListener('keydown', handleKeyDown);
        return () => window.removeEventListener('keydown', handleKeyDown);
    }, [onClose]);

    return (
        <div 
            className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm transition-all duration-300"
            onClick={onClose}
        >
            <div 
                className="bg-white border border-gray-200 rounded-2xl shadow-2xl flex flex-col w-full max-w-5xl h-[85vh] overflow-hidden transform transition-all duration-300 scale-100"
                onClick={e => e.stopPropagation()}
            >
                {/* ── Header ── */}
                <div className="flex-none flex items-center justify-between px-4 py-3 bg-gray-50 border-b border-gray-200">
                    <div className="flex items-center gap-2 text-gray-800">
                        {type === 'image' && <span className="p-1.5 bg-emerald-50 rounded-lg text-emerald-600"><FileText className="w-4 h-4" /></span>}
                        {type === 'pdf' && <span className="p-1.5 bg-rose-50 rounded-lg text-rose-600"><FileText className="w-4 h-4" /></span>}
                        {type === 'video' && <span className="p-1.5 bg-indigo-50 rounded-lg text-indigo-600"><Video className="w-4 h-4" /></span>}
                        {type === 'audio' && <span className="p-1.5 bg-amber-50 rounded-lg text-amber-600"><Music className="w-4 h-4" /></span>}
                        {type === 'doc' && <span className="p-1.5 bg-blue-50 rounded-lg text-blue-600"><File className="w-4 h-4" /></span>}
                        
                        <div className="flex flex-col min-w-0">
                            <span className="text-sm font-bold truncate max-w-[200px] sm:max-w-md text-gray-900">
                                {title || 'Lampiran Berkas'}
                            </span>
                            <span className="text-[10px] text-gray-500 uppercase tracking-wider font-semibold">
                                {type === 'image' && 'Gambar'}
                                {type === 'pdf' && 'Dokumen PDF'}
                                {type === 'video' && 'Video'}
                                {type === 'audio' && 'Audio / Musik'}
                                {type === 'doc' && 'Dokumen'}
                            </span>
                        </div>
                    </div>

                    <div className="flex items-center gap-2">
                        {/* Close button */}
                        <button 
                            onClick={onClose}
                            className="p-1.5 bg-white hover:bg-red-50 hover:text-red-600 text-gray-500 rounded-lg border border-gray-200 transition-all cursor-pointer"
                            title="Tutup"
                        >
                            <X className="w-4 h-4" />
                        </button>
                    </div>
                </div>

                {/* ── Main Viewport ── */}
                <div className="flex-1 overflow-hidden flex items-center justify-center p-4 bg-white">
                    
                    {/* 1. IMAGE VIEWER WITH TRANSFORMS */}
                    {type === 'image' && (
                        <div className="relative w-full h-full flex flex-col items-center justify-center">
                            {/* Controls floating overlay */}
                            <div className="absolute top-2 z-10 flex items-center gap-1 bg-white/95 backdrop-blur-sm px-3 py-1.5 rounded-full border border-gray-200 shadow-md">
                                <button 
                                    onClick={() => setZoom(z => Math.min(3, z + 0.25))}
                                    className="p-1.5 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-full transition-all cursor-pointer"
                                    title="Perbesar"
                                >
                                    <ZoomIn className="w-4 h-4" />
                                </button>
                                <button 
                                    onClick={() => setZoom(z => Math.max(0.5, z - 0.25))}
                                    className="p-1.5 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-full transition-all cursor-pointer"
                                    title="Perkecil"
                                >
                                    <ZoomOut className="w-4 h-4" />
                                </button>
                                <button 
                                    onClick={() => setRotation(r => r + 90)}
                                    className="p-1.5 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-full transition-all cursor-pointer"
                                    title="Putar 90°"
                                >
                                    <RotateCw className="w-4 h-4" />
                                </button>
                                <div className="h-4 w-px bg-gray-200 mx-1" />
                                <button 
                                    onClick={() => { setZoom(1); setRotation(0); }}
                                    className="p-1.5 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-full transition-all cursor-pointer"
                                    title="Reset Posisi"
                                >
                                    <RefreshCw className="w-4 h-4" />
                                </button>
                            </div>

                            <div className="w-full h-full flex items-center justify-center overflow-auto p-4 select-none">
                                <img 
                                    src={url} 
                                    alt={title}
                                    style={{
                                        transform: `scale(${zoom}) rotate(${rotation}deg)`,
                                        transition: 'transform 0.2s ease-out'
                                    }}
                                    className="max-h-[90%] max-w-[90%] object-contain rounded-lg shadow-md"
                                />
                            </div>
                        </div>
                    )}

                    {/* 2. PDF VIEWER */}
                    {type === 'pdf' && (
                        <div className="w-full h-full flex flex-col">
                            <iframe 
                                src={`${url}#toolbar=0`}
                                className="w-full flex-1 rounded-xl border border-gray-200 bg-white"
                                title="Pratinjau PDF"
                            />
                        </div>
                    )}

                    {/* 3. VIDEO VIEWER */}
                    {type === 'video' && (
                        <div className="max-w-4xl w-full flex items-center justify-center">
                            <video 
                                src={url} 
                                className="w-full max-h-[70vh] rounded-xl border border-gray-200 bg-black shadow-lg" 
                                controls 
                                autoPlay 
                            />
                        </div>
                    )}

                    {/* 4. PREMIUM AUDIO/MUSIC PLAYER */}
                    {type === 'audio' && (
                        <div className="max-w-md w-full p-6 bg-white border border-gray-200 rounded-3xl shadow-lg flex flex-col items-center gap-6 relative overflow-hidden">
                            {/* Decorative background lights */}
                            <div className="absolute -top-24 -left-24 w-48 h-48 rounded-full blur-[100px] pointer-events-none opacity-20" style={{ backgroundColor: companyColor }} />
                            <div className="absolute -bottom-24 -right-24 w-48 h-48 rounded-full blur-[100px] pointer-events-none opacity-20" style={{ backgroundColor: companyColor }} />
                            
                            {/* Audio file loading */}
                            <audio ref={audioRef} src={url} preload="metadata" />

                            {/* Vinyl record spinning */}
                            <div className="relative group mt-4">
                                <div 
                                    className="w-40 h-40 sm:w-48 sm:h-48 rounded-full bg-slate-900 border-4 border-gray-200 flex items-center justify-center shadow-md relative overflow-hidden transition-all duration-300"
                                    style={{
                                        boxShadow: isPlaying ? `0 0 20px ${companyColor}30` : ''
                                    }}
                                >
                                    {/* Record lines pattern */}
                                    <div className="absolute inset-2 rounded-full border border-slate-950/20 bg-[radial-gradient(ellipse_at_center,_var(--tw-gradient-stops))] from-slate-800 via-slate-900 to-slate-900" />
                                    
                                    {/* Center cover */}
                                    <div 
                                        className={`w-14 h-14 sm:w-16 sm:h-16 rounded-full bg-cover bg-center flex items-center justify-center z-10 border-4 border-slate-900 ${isPlaying ? 'animate-spin [animation-duration:10s]' : ''}`}
                                        style={{ backgroundColor: companyColor }}
                                    >
                                        <Music className="w-6 h-6 text-white/80" />
                                    </div>
                                </div>
                            </div>

                            {/* Info */}
                            <div className="text-center w-full min-w-0 z-10">
                                <h3 className="text-base font-bold text-gray-800 truncate px-2">{title || 'Lampiran Audio'}</h3>
                                <p className="text-xs text-gray-500 mt-1 font-semibold tracking-wider uppercase">Memutar Audio Lampiran</p>
                            </div>

                            {/* Custom Controls */}
                            <div className="w-full flex flex-col gap-4 z-10">
                                {/* Time Slider */}
                                <div className="flex flex-col gap-1">
                                    <input 
                                        type="range" 
                                        min={0}
                                        max={duration || 0}
                                        value={currentTime}
                                        onChange={handleProgressChange}
                                        className="w-full h-1.5 bg-gray-100 rounded-lg appearance-none cursor-pointer accent-emerald-500 hover:accent-emerald-400 transition-colors"
                                        style={{
                                            background: `linear-gradient(to right, ${companyColor} ${duration ? (currentTime/duration)*100 : 0}%, #e2e8f0 ${duration ? (currentTime/duration)*100 : 0}%)`
                                        }}
                                    />
                                    <div className="flex justify-between text-[11px] text-gray-500 font-mono font-bold mt-1">
                                        <span>{formatAudioTime(currentTime)}</span>
                                        <span>{formatAudioTime(duration)}</span>
                                    </div>
                                </div>

                                {/* Buttons bar */}
                                <div className="flex items-center justify-center gap-6">
                                    {/* Volume control */}
                                    <div className="flex items-center gap-2 group/vol">
                                        <Volume2 className="w-4 h-4 text-gray-450 group-hover/vol:text-gray-750" />
                                        <input 
                                            type="range"
                                            min={0}
                                            max={1}
                                            step={0.05}
                                            value={volume}
                                            onChange={handleVolumeChange}
                                            className="w-16 sm:w-20 h-1 bg-gray-250 rounded-lg appearance-none cursor-pointer accent-gray-600"
                                        />
                                    </div>

                                    {/* Play/Pause center */}
                                    <button 
                                        onClick={togglePlay}
                                        className="w-12 h-12 rounded-full flex items-center justify-center text-white shadow-md hover:scale-105 active:scale-95 transition-all cursor-pointer"
                                        style={{ backgroundColor: companyColor }}
                                    >
                                        {isPlaying ? <Pause className="w-5 h-5" /> : <Play className="w-5 h-5 ml-0.5" />}
                                    </button>

                                    <div className="w-16 sm:w-20" /> {/* Spacer alignment */}
                                </div>
                            </div>
                        </div>
                    )}

                    {/* 5. SECURE NON-DOWNLOADABLE DOCUMENT DETAILS CARD */}
                    {type === 'doc' && (
                        <div className="max-w-md w-full p-6 bg-white border border-gray-200 rounded-2xl shadow-lg flex flex-col items-center gap-5 text-center">
                            <div className="w-16 h-16 rounded-2xl bg-blue-50 flex items-center justify-center border border-blue-100">
                                <File className="w-8 h-8 text-blue-500" />
                            </div>
                            <div className="space-y-2">
                                <h3 className="text-base font-bold text-gray-800 truncate max-w-xs">{title || 'Berkas Lampiran'}</h3>
                                <p className="text-xs text-gray-555 leading-relaxed">
                                    Format berkas dokumen ini tidak didukung untuk dibuka langsung atau diunduh selama sesi ujian berlangsung demi menjaga keamanan ujian.
                                </p>
                            </div>
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
};

export default MediaViewerModal;
