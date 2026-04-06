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
            fixed lg:relative z-50 h-full lg:h-auto w-80 bg-white border-r border-gray-200 transition-transform duration-300 ease-in-out
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
            <div className="p-5 border-b bg-gray-50/50">
                <h3 className="hidden lg:block font-bold text-gray-800 mb-4">Navigasi Soal</h3>
                <div className="space-y-3">
                    <div className="flex items-center justify-between text-sm">
                        <span className="text-gray-500">Total Soal</span>
                        <span className="font-bold">{navigation.length}</span>
                    </div>
                    <div className="grid grid-cols-2 gap-2 text-xs">
                        <div className="flex items-center gap-2 p-2 bg-green-50 text-green-700 rounded-lg border border-green-100">
                            <div className="w-2 h-2 bg-green-500 rounded-full" />
                            <span>Dijawab: {answeredCount}</span>
                        </div>
                        <div className="flex items-center gap-2 p-2 bg-yellow-50 text-yellow-700 rounded-lg border border-yellow-100">
                            <div className="w-2 h-2 bg-yellow-500 rounded-full" />
                            <span>Ragu: {markedCount}</span>
                        </div>
                    </div>
                </div>
            </div>

            {/* Map Legends */}
            <div className="p-4 border-b">
                <div className="grid grid-cols-4 gap-2 text-[10px] text-gray-500 uppercase tracking-wider font-bold">
                    <div className="flex flex-col items-center gap-1">
                        <div className="w-5 h-5 bg-blue-600 rounded ring-2 ring-blue-200" />
                        <span>Aktif</span>
                    </div>
                    <div className="flex flex-col items-center gap-1">
                        <div className="w-5 h-5 bg-green-500 rounded" />
                        <span>Isi</span>
                    </div>
                    <div className="flex flex-col items-center gap-1">
                        <div className="w-5 h-5 bg-yellow-500 rounded" />
                        <span>Ragu</span>
                    </div>
                    <div className="flex flex-col items-center gap-1">
                        <div className="w-5 h-5 bg-gray-200 rounded" />
                        <span>Belum</span>
                    </div>
                </div>
            </div>

            {/* Grid Map */}
            <div className="p-4 overflow-y-auto custom-scrollbar" style={{ height: 'calc(100vh - 350px)' }}>
                <div className="grid grid-cols-5 gap-2">
                    {navigation.map((item, idx) => {
                        let bgColor = 'bg-gray-200 text-gray-600 hover:bg-gray-300';
                        let ringColor = '';

                        if (idx === currentIndex) {
                            bgColor = 'bg-blue-600 text-white';
                            ringColor = 'ring-4 ring-blue-100 scale-110 z-10';
                        } else if (item.isMarked) {
                            bgColor = 'bg-yellow-500 text-white hover:bg-yellow-600';
                        } else if (item.isAnswered) {
                            bgColor = 'bg-green-500 text-white hover:bg-green-600';
                        }

                        return (
                            <button
                                key={item.id}
                                onClick={() => {
                                    setCurrentIndex(idx);
                                    if (window.innerWidth < 1024) setIsOpen(false);
                                }}
                                className={`
                                    w-10 h-10 rounded-lg flex items-center justify-center font-bold text-sm transition-all
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
