import React, { useEffect, useRef, useState } from 'react';
import { Track } from 'livekit-client';

const StudentCard = ({ session, room, onDetail }) => {
    const videoRef = useRef(null);
    const [participant, setParticipant] = useState(null);
    const [isSubscribed, setIsSubscribed] = useState(false);

    // 1. Find participant in room by identity
    useEffect(() => {
        if (!room || !session.identity) return;

        const findParticipant = () => {
             if (!room.remoteParticipants) return;
             const p = room.remoteParticipants.get(session.identity);
             if (p) {
                setParticipant(p);
                console.log(`Matched participant for ${session.name}`);
             }
        };

        findParticipant();
        
        // Listen for new participants joining
        room.on('participantConnected', findParticipant);
        return () => {
            room.off('participantConnected', findParticipant);
        };
    }, [room, session.identity, session.name]);

    // 2. Handle Video Track Subscription
    useEffect(() => {
        if (!participant || !videoRef.current) return;

        const handleTrackSubscribed = (track, publication) => {
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

        // Check existing tracks
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

    const hasAlerts = session.alert_count > 0 || session.warning_count > 0;

    return (
        <div className={`bg-white rounded-xl shadow-sm overflow-hidden border transition-all duration-300 ${hasAlerts ? 'border-red-200 ring-1 ring-red-100' : 'border-gray-100'}`}>
            {/* Camera Container */}
            <div className="relative bg-black aspect-video group">
                <video 
                    ref={videoRef} 
                    autoPlay 
                    muted 
                    playsInline 
                    className="w-full h-full object-cover"
                />
                
                {!isSubscribed && (
                    <div className="absolute inset-0 flex flex-col items-center justify-center bg-gray-900 bg-opacity-80 transition-opacity duration-300">
                        <svg className="w-8 h-8 text-gray-500 mb-2 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                        </svg>
                        <span className="text-[10px] text-gray-400 font-medium">
                            {session.connection_status === 'connected' ? 'Menunggu Kamera...' : 'Offline'}
                        </span>
                    </div>
                )}

                {/* Status Badge */}
                <div className={`absolute top-2 right-2 px-2 py-0.5 rounded-full text-[10px] font-bold text-white uppercase tracking-wider ${getStatusColor()}`}>
                    {session.connection_status}
                </div>

                {/* Question Progress Overlay */}
                <div className="absolute bottom-2 left-2 px-2 py-1 bg-black/50 backdrop-blur-sm rounded text-[10px] text-white">
                    Soal: {session.current_question || 0}/{session.total_questions || 0}
                </div>
            </div>

            {/* Student Footer Info */}
            <div className="p-3">
                <div className="flex items-start justify-between gap-1 mb-2">
                    <div className="min-w-0">
                        <h4 className="text-sm font-bold text-gray-900 truncate leading-tight">{session.name}</h4>
                        <span className="text-[10px] text-gray-400 font-mono">{session.identity}</span>
                    </div>
                    {hasAlerts && (
                        <div className="flex gap-1">
                            {session.alert_count > 0 && (
                                <span className="flex items-center justify-center w-5 h-5 bg-red-100 text-red-700 text-[10px] font-bold rounded-full">
                                    {session.alert_count}
                                </span>
                            )}
                            {session.warning_count > 0 && (
                                <span className="flex items-center justify-center w-5 h-5 bg-yellow-100 text-yellow-700 text-[10px] font-bold rounded-full">
                                    {session.warning_count}
                                </span>
                            )}
                        </div>
                    )}
                </div>

                <div className="flex gap-2">
                    <button 
                        onClick={() => onDetail(session)}
                        className="flex-1 px-3 py-1.5 bg-blue-50 text-blue-700 hover:bg-blue-100 text-xs font-bold rounded-lg transition-colors border border-blue-100"
                    >
                        Detail
                    </button>
                </div>
            </div>
        </div>
    );
};

export default StudentCard;
