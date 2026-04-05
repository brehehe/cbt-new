import React from 'react';
import StudentCard from './StudentCard';

const StudentGrid = ({ sessions, room }) => {
    if (!sessions || sessions.length === 0) {
        return (
            <div className="flex flex-col items-center justify-center p-12 bg-white rounded-xl shadow-sm border border-gray-100 min-h-[300px]">
                <svg className="w-12 h-12 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                </svg>
                <p className="text-gray-500 font-medium text-center">
                    Tidak ada peserta yang aktif saat ini.<br />
                    <span className="text-sm font-normal">Kamera peserta akan muncul di sini otomatis ketika mereka mulai ujian.</span>
                </p>
            </div>
        );
    }

    return (
        <div className="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            {sessions.map(session => (
                <StudentCard 
                    key={session.id} 
                    session={session} 
                    room={room} 
                />
            ))}
        </div>
    );
};

export default StudentGrid;
