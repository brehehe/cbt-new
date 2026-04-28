import React from 'react';
import { X, Search, Info } from 'lucide-react';

const NavigationSidebar = ({
    navigation,
    currentIndex,
    setCurrentIndex,
    isOpen,
    setIsOpen,
    companyColor
}) => {
    const answeredCount = navigation.filter(n => n.isAnswered).length;
    const markedCount = navigation.filter(n => n.isMarked).length;
    const remainingCount = navigation.length - answeredCount;

    return (
        <aside className={`
            fixed lg:relative z-50 h-full lg:h-auto w-80 bg-white/80 backdrop-blur-2xl border-l border-white shadow-[-10px_0_30px_rgba(0,0,0,0.02)] transition-transform duration-300 ease-in-out
            ${isOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'}
        `}>
            {/* Header Mobile */}
            <div className="flex items-center justify-between p-4 border-b bg-gray-50 lg:hidden">
                <h3 className="font-bold text-gray-800">Navigasi Soal</h3>
                <button onClick={() => setIsOpen(false)} className="text-gray-500 hover:text-gray-700">
                    <X className="w-6 h-6" />
                </button>
            </div>

            {/* Stats Overview */}
            <div className="p-6 border-b border-gray-100 bg-white/40">
                <h3 className="hidden lg:block font-black text-xl text-transparent bg-clip-text bg-gradient-to-r from-orange-600 to-amber-500 mb-5">Peta Soal 🗺️</h3>
                <div className="space-y-4">
                    <div className="flex items-center justify-between text-sm bg-white p-3 rounded-xl shadow-sm border border-gray-50">
                        <span className="font-bold text-gray-500">Total Soal</span>
                        <span className="font-black text-orange-600 text-lg">{navigation.length}</span>
                    </div>
                    <div className="grid grid-cols-2 gap-3 text-xs font-bold">
                        <div className="flex items-center justify-center gap-2 p-3 bg-gradient-to-r from-emerald-50 to-teal-50 text-emerald-600 rounded-xl border border-emerald-100 shadow-sm">
                            <div className="w-2.5 h-2.5 bg-emerald-500 rounded-full shadow-sm" />
                            <span>Isi: {answeredCount}</span>
                        </div>
                        <div className="flex items-center justify-center gap-2 p-3 bg-gradient-to-r from-amber-50 to-yellow-50 text-amber-600 rounded-xl border border-amber-100 shadow-sm">
                            <div className="w-2.5 h-2.5 bg-amber-500 rounded-full shadow-sm" />
                            <span>Ragu: {markedCount}</span>
                        </div>
                    </div>
                </div>
            </div>

            {/* Map Legends */}
            <div className="p-5 border-b border-gray-100 bg-white/20">
                <div className="grid grid-cols-4 gap-2 text-[10px] text-slate-500 uppercase tracking-widest font-black">
                    <div className="flex flex-col items-center gap-1.5">
                        <div className="w-6 h-6 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-lg shadow-sm ring-2 ring-blue-200 ring-offset-1" />
                        <span>Now</span>
                    </div>
                    <div className="flex flex-col items-center gap-1.5">
                        <div className="w-6 h-6 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-lg shadow-sm" />
                        <span>Isi</span>
                    </div>
                    <div className="flex flex-col items-center gap-1.5">
                        <div className="w-6 h-6 bg-gradient-to-br from-amber-400 to-orange-400 rounded-lg shadow-sm" />
                        <span>Ragu</span>
                    </div>
                    <div className="flex flex-col items-center gap-1.5">
                        <div className="w-6 h-6 bg-slate-100 rounded-lg shadow-inner border border-slate-200" />
                        <span>Skip</span>
                    </div>
                </div>
            </div>

            {/* Grid Map */}
            <div className="p-5 overflow-y-auto custom-scrollbar" style={{ height: 'calc(100vh - 380px)' }}>
                <div className="grid grid-cols-5 gap-3">
                    {navigation.map((item, idx) => {
                        let bgColor = 'bg-slate-50 text-slate-500 hover:bg-slate-100 border border-slate-200 shadow-sm';
                        let ringColor = '';

                        if (idx === currentIndex) {
                            bgColor = 'bg-gradient-to-br from-blue-500 to-cyan-500 text-white shadow-lg shadow-blue-200 border-none';
                            ringColor = 'ring-4 ring-blue-100 scale-110 z-10';
                        } else if (item.isMarked) {
                            bgColor = 'bg-gradient-to-br from-amber-400 to-orange-400 text-white shadow-md shadow-amber-200 border-none hover:opacity-90';
                        } else if (item.isAnswered) {
                            bgColor = 'bg-gradient-to-br from-emerald-400 to-teal-500 text-white shadow-md shadow-emerald-200 border-none hover:opacity-90';
                        }

                        return (
                            <button
                                key={item.id}
                                onClick={() => {
                                    setCurrentIndex(idx);
                                    if (window.innerWidth < 1024) setIsOpen(false);
                                }}
                                className={`
                                    w-11 h-11 rounded-xl flex items-center justify-center font-black text-sm transition-all duration-300 hover:-translate-y-0.5 active:scale-95
                                    ${bgColor} ${ringColor}
                                `}
                            >
                                {idx + 1}
                            </button>
                        );
                    })}
                </div>
            </div>

            {/* Sidebar Footer Info */}
            <div className="absolute bottom-0 w-full p-4 border-t bg-gray-50">
                <div className="flex items-center gap-2 text-xs text-gray-400">
                    <Info className="w-4 h-4" />
                    <span>Klik nomor soal untuk berpindah navigasi</span>
                </div>
            </div>
        </aside>
    );
};

export default NavigationSidebar;
