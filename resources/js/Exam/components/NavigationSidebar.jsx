import React, { useState, useMemo } from 'react';
import {
    ChevronLeft, ChevronRight, Flag, CheckCircle,
    HelpCircle, Circle, Eye, X
} from 'lucide-react';

const NavigationSidebar = ({
    navigation = [],
    currentIndex,
    setCurrentIndex,
    isOpen,
    setIsOpen,
    companyColor = '#1e3a5f',
    visitedIndices = new Set(),
    skippedIndices = new Set(),
    onFinish,
}) => {
    const [activeFilter, setActiveFilter] = useState('semua');
    const [isReviewMode, setIsReviewMode] = useState(false);

    const answered  = navigation.filter(n => n.isAnswered).length;
    const marked    = navigation.filter(n => n.isMarked).length;
    const belum     = navigation.length - answered;
    const pct       = navigation.length > 0 ? Math.round((answered / navigation.length) * 100) : 0;

    const filteredIndices = useMemo(() => {
        const list = navigation.map((n, i) => ({ ...n, idx: i }));
        switch (activeFilter) {
            case 'dijawab':  return list.filter(n => n.isAnswered);
            case 'belum':    return list.filter(n => !n.isAnswered);
            case 'ragu':     return list.filter(n => n.isMarked);
            default:         return list;
        }
    }, [navigation, activeFilter]);

    const getStatus = (nav, idx) => {
        if (idx === currentIndex) return 'current';
        if (nav.isMarked) return 'ragu';
        if (nav.isAnswered) return 'answered';
        if (visitedIndices.has(idx)) return 'visited';
        return 'belum';
    };

    const statusStyle = (status) => {
        switch (status) {
            case 'current':  return { bg: '#1e3a5f', color: '#fff', border: 'transparent', fontWeight: 700 };
            case 'answered': return { bg: '#16a34a', color: '#fff', border: 'transparent' };
            case 'ragu':     return { bg: '#f59e0b', color: '#fff', border: 'transparent' };
            case 'visited':  return { bg: '#f1f5f9', color: '#475569', border: '#cbd5e1' };
            default:         return { bg: '#fff', color: '#94a3b8', border: '#e2e8f0' };
        }
    };

    const filters = [
        { key: 'semua',   label: 'Semua' },
        { key: 'dijawab', label: '✓ Dijawab' },
        { key: 'belum',   label: '✗ Belum' },
        { key: 'ragu',    label: '* Ragu-Ragu' },
    ];

    const sidebarContent = (
        <div className="flex flex-col h-full bg-white border-r border-gray-200" style={{ width: 240 }}>

            {/* ── Sidebar Header ── */}
            <div className="flex-none p-3 border-b border-gray-100" style={{ backgroundColor: '#1e3a5f' }}>
                <div className="flex items-center justify-between">
                    <span className="text-white text-xs font-bold tracking-widest uppercase">Navigasi Soal</span>
                    <button
                        onClick={() => setIsOpen(false)}
                        className="lg:hidden text-white/70 hover:text-white"
                    >
                        <X className="w-4 h-4" />
                    </button>
                </div>
                {/* Progress line */}
                <div className="mt-2 flex items-center justify-between text-white/80 text-[11px]">
                    <span>Soal {currentIndex + 1} dari {navigation.length}</span>
                    <span>{pct}%</span>
                </div>
                <div className="mt-1.5 h-1.5 bg-white/20 rounded-full overflow-hidden">
                    <div className="h-full bg-green-400 rounded-full transition-all duration-500" style={{ width: `${pct}%` }} />
                </div>
            </div>

            {/* ── Stats Row ── */}
            <div className="flex-none grid grid-cols-3 border-b border-gray-100">
                {[
                    { count: answered, label: 'DIJAWAB',  color: '#16a34a' },
                    { count: belum,    label: 'BELUM',    color: '#ef4444' },
                    { count: marked,   label: 'RAGU-RAGU', color: '#f59e0b' },
                ].map((s, i) => (
                    <div key={i} className="flex flex-col items-center py-2 border-r border-gray-100 last:border-r-0">
                        <span className="font-black text-base leading-tight" style={{ color: s.color }}>{s.count}</span>
                        <span className="text-[8px] font-bold text-gray-400 tracking-wider">{s.label}</span>
                    </div>
                ))}
            </div>

            {/* ── Filter Tabs ── */}
            <div className="flex-none px-2 py-2 border-b border-gray-100">
                <div className="flex gap-1 flex-wrap">
                    {filters.map(f => (
                        <button
                        key={f.key}
                        onClick={() => setActiveFilter(f.key)}
                        className="text-[10px] font-bold px-2 py-0.5 rounded-full transition-all border"
                        style={activeFilter === f.key
                            ? { backgroundColor: '#1e3a5f', color: '#fff', borderColor: '#1e3a5f' }
                            : { backgroundColor: '#f8fafc', color: '#64748b', borderColor: '#e2e8f0' }
                        }
                    >
                        {f.label}
                    </button>
                    ))}
                </div>
            </div>

            {/* ── Legend ── */}
            <div className="flex-none px-2 py-1.5 border-b border-gray-100 bg-gray-50">
                <div className="flex flex-wrap gap-x-2 gap-y-1">
                    {[
                        { color: '#1e3a5f', label: 'Aktif' },
                        { color: '#16a34a',    label: 'Dijawab' },
                        { color: '#f59e0b',    label: 'Ragu-Ragu' },
                        { color: '#cbd5e1',    label: 'Dibuka' },
                        { color: '#e2e8f0',    label: 'Belum', border: '#cbd5e1' },
                    ].map((l, i) => (
                        <div key={i} className="flex items-center gap-1">
                            <span
                                className="inline-block w-2 h-2 rounded-full border"
                                style={{ backgroundColor: l.color, borderColor: l.border || l.color }}
                            />
                            <span className="text-[9px] text-gray-500 font-medium">{l.label}</span>
                        </div>
                    ))}
                </div>
            </div>

            {/* ── Question Grid ── */}
            <div className="flex-1 overflow-y-auto p-2">
                <div className="grid grid-cols-6 gap-1">
                    {filteredIndices.map((nav) => {
                        const status = getStatus(nav, nav.idx);
                        const style  = statusStyle(status);
                        return (
                            <button
                                key={nav.idx}
                                onClick={() => { setCurrentIndex(nav.idx); setIsOpen(false); }}
                                className="relative flex items-center justify-center rounded-md text-[11px] transition-all duration-150 hover:scale-110 active:scale-95 shadow-sm"
                                style={{
                                    height: 32,
                                    backgroundColor: style.bg,
                                    color: style.color,
                                    border: `1.5px solid ${style.border}`,
                                    fontWeight: style.fontWeight || 500,
                                }}
                                title={`Soal ${nav.idx + 1}`}
                            >
                                {nav.idx + 1}
                                {nav.isMarked && (
                                    <span
                                        className="absolute -top-0.5 -right-0.5 w-1.5 h-1.5 rounded-full"
                                        style={{ backgroundColor: '#3b82f6' }}
                                    />
                                )}
                            </button>
                        );
                    })}
                </div>
            </div>

            {/* ── Bottom Actions ── */}
            <div className="flex-none p-2 border-t border-gray-100 space-y-1.5">
                <button
                    onClick={() => onFinish(false)}
                    className="w-full flex items-center justify-center gap-2 py-2 rounded-lg text-xs font-bold bg-green-600 hover:bg-green-700 text-white transition-all shadow-sm"
                >
                    <CheckCircle className="w-3.5 h-3.5" />
                    Selesai Ujian
                </button>
            </div>
        </div>
    );

    return (
        <>
            {/* Desktop: always visible */}
            <aside className="hidden lg:flex flex-none h-full overflow-hidden">
                {sidebarContent}
            </aside>

            {/* Mobile: slide-in overlay */}
            <aside
                className={`lg:hidden fixed top-0 left-0 h-full z-50 transition-transform duration-300 ${isOpen ? 'translate-x-0' : '-translate-x-full'}`}
                style={{ width: 240 }}
            >
                {sidebarContent}
            </aside>
        </>
    );
};

export default NavigationSidebar;
