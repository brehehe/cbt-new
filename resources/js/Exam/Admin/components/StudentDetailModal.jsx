import React, { useEffect, useRef, useState } from 'react';
import { Track } from 'livekit-client';

const StudentDetailModal = ({ session, room, onClose }) => {
    const videoRef = useRef(null);
    const [participant, setParticipant] = useState(null);
    const [isSubscribed, setIsSubscribed] = useState(false);

    // 1. Find participant in room
    useEffect(() => {
        if (!room || !session.identity) return;

        const findParticipant = () => {
            const p = room.remoteParticipants.get(session.identity);
            if (p) setParticipant(p);
        };

        findParticipant();
        room.on('participantConnected', findParticipant);
        return () => room.off('participantConnected', findParticipant);
    }, [room, session.identity]);

    // 2. Handle Video Track
    useEffect(() => {
        if (!participant || !videoRef.current) return;

        const handleTrackSubscribed = (track) => {
            if (track.kind === Track.Kind.Video) {
                track.attach(videoRef.current);
                setIsSubscribed(true);
            }
        };

        const handleTrackUnsubscribed = (track) => {
            if (track.kind === Track.Kind.Video) {
                track.detach(videoRef.current);
                setIsSubscribed(false);
            }
        };

        participant.videoTrackPublications.forEach(pub => {
            if (pub.track) {
                pub.track.attach(videoRef.current);
                setIsSubscribed(true);
            }
        });

        participant.on('trackSubscribed', handleTrackSubscribed);
        participant.on('trackUnsubscribed', handleTrackUnsubscribed);

        return () => {
            participant.off('trackSubscribed', handleTrackSubscribed);
            participant.off('trackUnsubscribed', handleTrackUnsubscribed);
        };
    }, [participant]);

    const getStatusColor = () => {
        if (session.connection_status === 'connected') return 'bg-green-500';
        if (session.connection_status === 'disconnected') return 'bg-red-500';
        return 'bg-gray-400';
    };

    return (
        <div className="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm animate-in fade-in duration-200">
            <div
                className="bg-white rounded-2xl shadow-2xl w-full max-w-4xl overflow-hidden flex flex-col md:flex-row max-h-[90vh]"
                onClick={(e) => e.stopPropagation()}
            >
                {/* Left: Video Feed */}
                <div className="flex-1 bg-black relative aspect-video md:aspect-auto flex items-center justify-center">
                    <video
                        ref={videoRef}
                        autoPlay
                        muted
                        playsInline
                        className="w-full h-full object-contain"
                    />

                    {!isSubscribed && (
                        <div className="absolute inset-0 flex flex-col items-center justify-center bg-gray-900/80">
                            <div className="animate-spin rounded-full h-10 w-10 border-b-2 border-white mb-4"></div>
                            <span className="text-white text-sm font-medium">Menyambungkan Kamera...</span>
                        </div>
                    )}

                    <div className="absolute top-4 left-4 flex gap-2">
                        <div className={`px-3 py-1 rounded-full text-xs font-bold text-white uppercase tracking-wider ${getStatusColor()}`}>
                            {session.connection_status}
                        </div>
                        <div className="px-3 py-1 bg-black/50 backdrop-blur-md rounded-full text-xs text-white border border-white/10">
                            Soal: {session.current_question || 0}/{session.total_questions || 0}
                        </div>
                    </div>
                </div>

                {/* Right: Info Panel */}
                <div className="w-full md:w-80 flex flex-col border-l border-gray-100 bg-gray-50/30">
                    <div className="p-6 border-b border-gray-100 bg-white">
                        <div className="flex justify-between items-start mb-4">
                            <h3 className="text-lg font-bold text-gray-900 leading-tight">{session.name}</h3>
                            <button
                                onClick={onClose}
                                className="p-1 hover:bg-gray-100 rounded-lg transition-colors text-gray-400 hover:text-gray-600"
                            >
                                <svg className="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <p className="text-xs text-gray-400 font-mono break-all mb-1">ID: {session.identity}</p>
                    </div>

                    <div className="flex-1 overflow-y-auto p-6 space-y-6">
                        {/* Violations/Alerts section */}
                        <div>
                            <h4 className="text-xs font-bold text-gray-400 uppercase tracking-wider mb-3">Status Pelanggaran</h4>
                            <div className="grid grid-cols-2 gap-3">
                                <div className="bg-red-50 p-4 rounded-xl border border-red-100">
                                    <p className="text-red-600 text-2xl font-bold">{session.alert_count || 0}</p>
                                    <p className="text-red-500 text-[10px] font-bold uppercase tracking-wide">Alert</p>
                                </div>
                                <div className="bg-yellow-50 p-4 rounded-xl border border-yellow-100">
                                    <p className="text-yellow-600 text-2xl font-bold">{session.warning_count || 0}</p>
                                    <p className="text-yellow-500 text-[10px] font-bold uppercase tracking-wide">Warning</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div className="p-6 bg-white border-t border-gray-100">
                        <button
                            onClick={onClose}
                            className="w-full py-2.5 bg-gray-900 text-white rounded-xl text-sm font-bold hover:bg-gray-800 transition-colors shadow-lg shadow-gray-200"
                        >
                            Tutup Detail
                        </button>
                    </div>
                </div>
            </div>

            {/* Backdrop click close */}
            <div className="fixed inset-0 -z-10" onClick={onClose}></div>
        </div>
    );
};

export default StudentDetailModal;
