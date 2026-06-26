import React, { useState, useEffect, useRef } from 'react';
import axios from 'axios';

export default function AdminDashboard({ userProfile = {} }) {
    const [loadingStats, setLoadingStats] = useState(true);
    const [statsData, setStatsData] = useState(null);
    const [realtimeData, setRealtimeData] = useState(null);
    const [errorMsg, setErrorMsg] = useState('');
    const [refreshing, setRefreshing] = useState(false);
    const [lastUpdated, setLastUpdated] = useState('');

    const chartRef = useRef(null);
    const chartInstance = useRef(null);

    // Read branding colors dynamically from global CSS custom variables registered by parent Blade layout
    const getBrandingColors = () => {
        const docStyle = getComputedStyle(document.documentElement);
        const primary = docStyle.getPropertyValue('--primary').trim() || '#f58634';
        const secondary = docStyle.getPropertyValue('--secondary').trim() || '#c3d4ec';
        return { primary, secondary };
    };

    // Fetch static overview statistics
    const fetchOverviewStats = async (isManual = false) => {
        if (isManual) setRefreshing(true);
        try {
            const res = await axios.get('/api/admin/dashboard/stats');
            if (res.data && res.data.status === 'success') {
                setStatsData(res.data.data);
                setErrorMsg('');
            }
        } catch (err) {
            console.error('Failed to load dashboard stats', err);
            setErrorMsg('Gagal menyegarkan data ikhtisar dashboard.');
        } finally {
            setLoadingStats(false);
            setRefreshing(false);
            const now = new Date();
            setLastUpdated(now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' }));
        }
    };

    // Fetch real-time active session information
    const fetchRealtimeData = async () => {
        try {
            const res = await axios.get('/api/admin/dashboard/realtime');
            if (res.data && res.data.status === 'success') {
                setRealtimeData(res.data.data);
            }
        } catch (err) {
            console.warn('Real-time polling failed', err);
        }
    };

    // Initialize stats and set up polling interval (every 10s)
    useEffect(() => {
        fetchOverviewStats();
        fetchRealtimeData();

        const pollTimer = setInterval(fetchRealtimeData, 10000);
        return () => clearInterval(pollTimer);
    }, []);

    // Effect to mount or update Chart.js line graph
    useEffect(() => {
        if (statsData && statsData.weeklyExamStats && window.Chart && chartRef.current) {
            const ctx = chartRef.current.getContext('2d');
            if (ctx) {
                const colors = getBrandingColors();

                // Clean up existing chart instances to prevent leaks
                if (chartInstance.current) {
                    chartInstance.current.destroy();
                }

                chartInstance.current = new window.Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: statsData.weeklyExamStats.map(item => item.date),
                        datasets: [{
                            label: 'Ujian Dimulai',
                            data: statsData.weeklyExamStats.map(item => item.count),
                            borderColor: colors.primary,
                            backgroundColor: colors.primary.startsWith('#') ? `${colors.primary}12` : 'rgba(245, 134, 52, 0.08)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: colors.primary,
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                padding: 12,
                                cornerRadius: 12,
                                backgroundColor: 'rgba(15, 23, 42, 0.9)',
                                titleFont: { size: 13, weight: 'bold' },
                                bodyFont: { size: 12 }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(241, 245, 249, 1)',
                                    drawBorder: false
                                },
                                ticks: {
                                    font: { size: 11, family: 'sans-serif' },
                                    color: '#64748b'
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                ticks: {
                                    font: { size: 11, family: 'sans-serif' },
                                    color: '#64748b'
                                }
                            }
                        }
                    }
                });
            }
        }

        return () => {
            if (chartInstance.current) {
                chartInstance.current.destroy();
                chartInstance.current = null;
            }
        };
    }, [statsData]);

    const handleManualRefresh = () => {
        fetchOverviewStats(true);
        fetchRealtimeData();
        if (window.Swal) {
            window.Swal.fire({
                title: 'Data Diperbarui',
                text: 'Data dashboard berhasil disegarkan!',
                icon: 'success',
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 1500
            });
        }
    };

    // Shimmer/Skeleton component for metric blocks
    const ShimmerSkeleton = () => (
        <div className="animate-pulse bg-slate-100 rounded-2xl h-36 w-full relative overflow-hidden">
            <div className="absolute inset-0 bg-gradient-to-r from-transparent via-white/40 to-transparent -translate-x-full animate-[shimmer_1.5s_infinite]" />
        </div>
    );

    return (
        <div className="w-full font-sans selection:bg-[var(--primary)] selection:text-white">

            {/* Header greeting block */}
            <div className="mb-6">
                <div className="flex flex-col md:flex-row items-center justify-between gap-4">
                    <div className="text-left w-full md:w-auto">
                        <h1 className="text-2xl md:text-3xl font-extrabold text-slate-800 tracking-tight leading-none">
                            Selamat datang kembali, <span className="text-[var(--primary)]">{userProfile.user?.name || 'Administrator'}</span>!
                        </h1>
                        <p className="text-sm font-semibold text-slate-500 mt-2">
                            Berikut adalah status operasional dan analisis data sistem CBT Anda hari ini.
                        </p>
                    </div>

                    <div className="flex items-center gap-3.5 w-full md:w-auto justify-start md:justify-end">
                        {/* Real-time Online Poller indicator */}
                        <div className="inline-flex items-center gap-2 rounded-xl border border-emerald-100 bg-emerald-50/60 px-3.5 py-2 shadow-sm">
                            <div className="relative flex h-2 w-2">
                                <span className="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                <span className="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                            </div>
                            <span className="text-[10px] font-extrabold text-emerald-700 uppercase tracking-widest">Langsung</span>
                        </div>

                        {/* Last refreshed pill */}
                        {lastUpdated && (
                            <span className="hidden sm:inline text-xs font-semibold text-slate-400 bg-slate-50 border border-slate-150 rounded-xl px-3 py-2">
                                Update: {lastUpdated}
                            </span>
                        )}

                        {/* Refresh button with spinner */}
                        <button
                            onClick={handleManualRefresh}
                            disabled={refreshing || loadingStats}
                            style={{ backgroundColor: 'var(--primary)' }}
                            className="inline-flex items-center px-4 py-2.5 hover:brightness-95 active:scale-95 disabled:opacity-80 text-white text-xs font-bold rounded-xl shadow-lg transition-all w-full sm:w-auto justify-center cursor-pointer"
                        >
                            <svg className={`w-4 h-4 mr-2 ${refreshing ? 'animate-spin' : ''}`} fill="none" stroke="currentColor" strokeWidth="2.5" viewBox="0 0 24 24">
                                <path strokeLinecap="round" strokeLinejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                            </svg>
                            Segarkan
                        </button>
                    </div>
                </div>
            </div>

            {errorMsg && (
                <div className="mb-6 rounded-xl border border-red-200 bg-red-50 p-4 text-xs font-bold text-red-800 shadow-sm flex items-center gap-2">
                    <svg className="h-5 w-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fillRule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clipRule="evenodd" />
                    </svg>
                    <span>{errorMsg}</span>
                </div>
            )}

            {/* Loading Stats Shimmers */}
            {loadingStats ? (
                <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <ShimmerSkeleton /><ShimmerSkeleton /><ShimmerSkeleton /><ShimmerSkeleton />
                </div>
            ) : (
                <>
                    {/* Primary statistics grid */}
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

                        {/* Card 1: Total Users */}
                        <div className="bg-white/90 rounded-2xl p-6 shadow-sm border border-slate-100 transition-all duration-300 hover:shadow-md hover:-translate-y-0.5 border-l-4 border-l-[var(--primary)]">
                            <div className="flex items-center justify-between">
                                <div>
                                    <p className="text-xs font-bold text-slate-400 uppercase tracking-widest">Total Pengguna</p>
                                    <h3 className="text-3xl font-black text-slate-800 mt-2">
                                        {statsData?.totalUsers ? statsData.totalUsers.toLocaleString('id-ID') : 0}
                                    </h3>
                                    <div className="flex items-center mt-2.5">
                                        <span className="text-[10px] font-extrabold text-[var(--primary)] bg-[var(--primary)]/10 px-2.5 py-1 rounded-lg">
                                            {statsData?.monthlyStats?.new_users_this_month > 0
                                                ? `+${statsData.monthlyStats.new_users_this_month} Bulan Ini`
                                                : 'Pengguna Terdaftar'}
                                        </span>
                                    </div>
                                </div>
                                <div className="p-3.5 rounded-2xl text-[var(--primary)] bg-[var(--primary)]/10">
                                    <svg className="w-7 h-7" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {/* Card 2: Today's Exams */}
                        <div className="bg-white/90 rounded-2xl p-6 shadow-sm border border-slate-100 transition-all duration-300 hover:shadow-md hover:-translate-y-0.5 border-l-4 border-l-sky-500">
                            <div className="flex items-center justify-between">
                                <div>
                                    <p className="text-xs font-bold text-slate-400 uppercase tracking-widest">Ujian Hari Ini</p>
                                    <h3 className="text-3xl font-black text-slate-800 mt-2">
                                        {statsData?.todayExams || 0}
                                    </h3>
                                    <div className="flex items-center mt-2.5">
                                        <span className="inline-flex items-center gap-1.5 text-[10px] font-extrabold text-sky-600 bg-sky-50 px-2.5 py-1 rounded-lg">
                                            <span className="w-1.5 h-1.5 bg-sky-500 rounded-full animate-pulse"></span>
                                            Ujian Baru
                                        </span>
                                    </div>
                                </div>
                                <div className="bg-sky-50/70 p-3.5 rounded-2xl text-sky-500">
                                    <svg className="w-7 h-7" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {/* Card 3: Active Exams */}
                        <div className="bg-white/90 rounded-2xl p-6 shadow-sm border border-slate-100 transition-all duration-300 hover:shadow-md hover:-translate-y-0.5 border-l-4 border-l-amber-500">
                            <div className="flex items-center justify-between">
                                <div>
                                    <p className="text-xs font-bold text-slate-400 uppercase tracking-widest">Ujian Aktif</p>
                                    <h3 className="text-3xl font-black text-slate-800 mt-2">
                                        {statsData?.activeExams || 0}
                                    </h3>
                                    <div className="flex items-center mt-2.5">
                                        <span className={`text-[10px] font-extrabold px-2.5 py-1 rounded-lg ${statsData?.activeExams > 0 ? 'text-amber-700 bg-amber-50' : 'text-slate-500 bg-slate-100'}`}>
                                            {statsData?.activeExams > 0 ? 'Sedang Berlangsung' : 'Kondisi Tenang'}
                                        </span>
                                    </div>
                                </div>
                                <div className="bg-amber-50/70 p-3.5 rounded-2xl text-amber-600">
                                    <svg className="w-7 h-7" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                        {/* Card 4: Security Alerts */}
                        <div className="bg-white/90 rounded-2xl p-6 shadow-sm border border-slate-100 transition-all duration-300 hover:shadow-md hover:-translate-y-0.5 border-l-4 border-l-red-500">
                            <div className="flex items-center justify-between">
                                <div>
                                    <p className="text-xs font-bold text-slate-400 uppercase tracking-widest">Aktivitas Mencurigakan</p>
                                    <h3 className={`text-3xl font-black mt-2 ${statsData?.examAlerts > 0 ? 'text-red-600' : 'text-slate-800'}`}>
                                        {statsData?.examAlerts || 0}
                                    </h3>
                                    <div className="flex items-center mt-2.5">
                                        <span className={`text-[10px] font-extrabold px-2.5 py-1 rounded-lg ${statsData?.examAlerts > 0 ? 'text-red-700 bg-red-50 animate-pulse' : 'text-emerald-700 bg-emerald-50'}`}>
                                            {statsData?.examAlerts > 0 ? 'Butuh Peninjauan' : 'Kondisi Aman'}
                                        </span>
                                    </div>
                                </div>
                                <div className={`p-3.5 rounded-2xl ${statsData?.examAlerts > 0 ? 'bg-red-50 text-red-500' : 'bg-slate-50 text-slate-400'}`}>
                                    <svg className="w-7 h-7" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                                        <path strokeLinecap="round" strokeLinejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                </div>
                            </div>
                        </div>

                    </div>

                    {/* Secondary statistics grid */}
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

                        {/* Metric: Completed Exams */}
                        <div className="bg-white/80 rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center justify-between">
                            <div>
                                <p className="text-xs font-bold text-slate-400 uppercase tracking-widest">Ujian Selesai</p>
                                <h4 className="text-2xl font-black text-slate-800 mt-1">
                                    {statsData?.completedExams ? statsData.completedExams.toLocaleString('id-ID') : 0}
                                </h4>
                                {statsData?.monthlyStats?.completed_this_month !== undefined && (
                                    <span className="text-[10px] font-bold text-emerald-600 bg-emerald-50 px-2 py-0.5 rounded-md mt-1.5 inline-block">
                                        +{statsData.monthlyStats.completed_this_month} Bulan Ini
                                    </span>
                                )}
                            </div>
                            <div className="bg-emerald-50 p-2.5 rounded-xl text-emerald-500">
                                <svg className="w-6 h-6" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                        </div>

                        {/* Metric: Exam Categories */}
                        <div className="bg-white/80 rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center justify-between">
                            <div>
                                <p className="text-xs font-bold text-slate-400 uppercase tracking-widest">Kategori Ujian</p>
                                <h4 className="text-2xl font-black text-slate-800 mt-1">
                                    {statsData?.totalExamTypes || 0}
                                </h4>
                                <span className="text-[10px] font-bold text-indigo-600 bg-indigo-50 px-2 py-0.5 rounded-md mt-1.5 inline-block">
                                    Jenis Tersedia
                                </span>
                            </div>
                            <div className="bg-indigo-50 p-2.5 rounded-xl text-indigo-500">
                                <svg className="w-6 h-6" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                </svg>
                            </div>
                        </div>

                        {/* Metric: Completion Rate */}
                        <div className="bg-white/80 rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center justify-between">
                            <div>
                                <p className="text-xs font-bold text-slate-400 uppercase tracking-widest">Tingkat Kelulusan</p>
                                <h4 className="text-2xl font-black text-slate-800 mt-1">
                                    {statsData?.monthlyStats?.avg_completion_rate || 0}%
                                </h4>
                                <span className="text-[10px] font-bold text-sky-600 bg-sky-50 px-2 py-0.5 rounded-md mt-1.5 inline-block">
                                    Rata-rata Bulan Ini
                                </span>
                            </div>
                            <div className="bg-sky-50 p-2.5 rounded-xl text-sky-500">
                                <svg className="w-6 h-6" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                            </div>
                        </div>

                        {/* Metric: Active Live Sessions */}
                        <div className="bg-white/80 rounded-2xl p-5 shadow-sm border border-slate-100 flex items-center justify-between">
                            <div>
                                <p className="text-xs font-bold text-slate-400 uppercase tracking-widest">Sesi Kamera Langsung</p>
                                <h4 className="text-2xl font-black text-slate-800 mt-1">
                                    {realtimeData?.liveSessionStats?.active_sessions || 0}
                                </h4>
                                <span className="inline-flex items-center gap-1 text-[10px] font-bold text-rose-600 bg-rose-50 px-2 py-0.5 rounded-md mt-1.5">
                                    <span className="w-1.5 h-1.5 bg-rose-500 rounded-full animate-ping"></span>
                                    Aktif
                                </span>
                            </div>
                            <div className="bg-rose-50 p-2.5 rounded-xl text-rose-500">
                                <svg className="w-6 h-6" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                </svg>
                            </div>
                        </div>

                    </div>

                    {/* Analytics Graphics Panel */}
                    <div className="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

                        {/* Graph Canvas */}
                        <div className="lg:col-span-2 bg-white/90 rounded-2xl p-6 shadow-sm border border-slate-100 flex flex-col justify-between">
                            <div className="flex items-center justify-between mb-4">
                                <h3 className="text-sm font-bold text-slate-800 uppercase tracking-wide">Tren Mingguan Pengerjaan Ujian</h3>
                                <div className="inline-flex items-center gap-1.5 px-3 py-1 bg-slate-100 rounded-lg text-slate-500 text-[10px] font-bold uppercase tracking-wider">
                                    Volume Harian
                                </div>
                            </div>
                            <div className="h-72 w-full relative">
                                <canvas ref={chartRef} id="weeklyChartReact" />
                            </div>
                        </div>

                        {/* Status Distributions */}
                        <div className="bg-white/90 rounded-2xl p-6 shadow-sm border border-slate-100">
                            <h3 className="text-sm font-bold text-slate-800 uppercase tracking-wide mb-5">Pembagian Status Ujian</h3>

                            <div className="space-y-4">
                                {statsData?.examStatistics ? (
                                    Object.entries(statsData.examStatistics).map(([status, count]) => {
                                        const sum = Object.values(statsData.examStatistics).reduce((a, b) => a + b, 0);
                                        const pct = sum > 0 ? ((count / sum) * 100).toFixed(1) : 0;

                                        const colorMap = {
                                            done: { text: 'text-emerald-700', bg: 'bg-emerald-500', pill: 'bg-emerald-50' },
                                            exam: { text: 'text-blue-700', bg: 'bg-blue-500', pill: 'bg-blue-50' },
                                            warning: { text: 'text-amber-700', bg: 'bg-amber-500', pill: 'bg-amber-50' },
                                            blocked: { text: 'text-red-700', bg: 'bg-red-500', pill: 'bg-red-50' }
                                        };
                                        const styles = colorMap[status] || { text: 'text-slate-700', bg: 'bg-slate-500', pill: 'bg-slate-50' };

                                        return (
                                            <div key={status} className={`p-4 rounded-2xl flex flex-col gap-2.5 ${styles.pill}`}>
                                                <div className="flex items-center justify-between">
                                                    <span className={`text-xs font-black uppercase tracking-wider ${styles.text}`}>
                                                        {status === 'done' ? 'Selesai' : status === 'exam' ? 'Sedang Ujian' : status === 'warning' ? 'Peringatan' : 'Diblokir'}
                                                    </span>
                                                    <div className="text-right">
                                                        <span className={`text-sm font-black ${styles.text}`}>{count}</span>
                                                        <span className="text-[10px] text-slate-400 font-bold ml-1.5">({pct}%)</span>
                                                    </div>
                                                </div>

                                                {/* Percentage bar */}
                                                <div className="h-2 w-full bg-slate-200/50 rounded-full overflow-hidden">
                                                    <div
                                                        className={`h-full rounded-full ${styles.bg}`}
                                                        style={{ width: `${pct}%`, transition: 'width 1s ease-in-out' }}
                                                    />
                                                </div>
                                            </div>
                                        );
                                    })
                                ) : (
                                    <div className="text-center text-slate-400 py-10">
                                        <p className="text-xs font-semibold">Tidak ada statistik status saat ini.</p>
                                    </div>
                                )}
                            </div>
                        </div>

                    </div>

                    {/* Live Monitoring and Alerts Section */}
                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

                        {/* Live Session Details */}
                        <div className="bg-white/90 rounded-2xl p-6 shadow-sm border border-slate-100 flex flex-col justify-between">
                            <div>
                                <div className="flex items-center justify-between mb-5">
                                    <h3 className="text-sm font-bold text-slate-800 uppercase tracking-wide">Analisis Pemantauan Sesi Aktif</h3>
                                    <a
                                        href="/admin/exam/live-stream"
                                        className="text-xs font-bold text-blue-500 hover:text-blue-600 transition-colors"
                                    >
                                        Live Stream →
                                    </a>
                                </div>

                                <div className="grid grid-cols-2 gap-4 mb-5">
                                    <div className="p-4 bg-sky-50/50 border border-sky-100 rounded-2xl text-center">
                                        <div className="text-3xl font-black text-sky-700">
                                            {realtimeData?.liveSessionStats?.active_sessions || 0}
                                        </div>
                                        <div className="text-[10px] font-bold text-sky-600 uppercase tracking-wider mt-1">Sesi Kamera</div>
                                    </div>
                                    <div className="p-4 bg-rose-50/50 border border-rose-100 rounded-2xl text-center">
                                        <div className="text-3xl font-black text-rose-700">
                                            {realtimeData?.liveSessionStats?.high_risk || 0}
                                        </div>
                                        <div className="text-[10px] font-bold text-rose-600 uppercase tracking-wider mt-1">Risiko Tinggi</div>
                                    </div>
                                </div>
                            </div>

                            <div className="space-y-3 pt-3 border-t border-slate-100">
                                <div className="flex items-center justify-between text-xs font-bold text-slate-600">
                                    <span>Gagal Deteksi Kamera (Simulated)</span>
                                    <span className="text-rose-600 bg-rose-50 px-2 py-0.5 rounded">{realtimeData?.liveSessionStats?.camera_issues || 0}</span>
                                </div>
                                <div className="flex items-center justify-between text-xs font-bold text-slate-600">
                                    <span>Masalah Sambungan (Simulated)</span>
                                    <span className="text-amber-600 bg-amber-50 px-2 py-0.5 rounded">{realtimeData?.liveSessionStats?.connection_issues || 0}</span>
                                </div>
                                <div className="flex items-center justify-between text-xs font-bold text-slate-600 pt-1.5">
                                    <span>Server Load</span>
                                    <span className="text-emerald-700 font-black">{realtimeData?.serverLoad || '35%'}</span>
                                </div>
                                <div className="flex items-center justify-between text-xs font-bold text-slate-600">
                                    <span>System Uptime</span>
                                    <span className="text-blue-600 font-black">{realtimeData?.systemUptime || '99.5%'}</span>
                                </div>
                            </div>
                        </div>

                        {/* Critical Alerts Timeline */}
                        <div className="bg-white/90 rounded-2xl p-6 shadow-sm border border-slate-100">
                            <div className="flex items-center justify-between mb-5">
                                <h3 className="text-sm font-bold text-slate-800 uppercase tracking-wide">Peringatan Kritis Keamanan</h3>
                                <a
                                    href="/admin/exam/monitor"
                                    className="text-xs font-bold text-blue-500 hover:text-blue-600 transition-colors"
                                >
                                    Log Monitor →
                                </a>
                            </div>

                            <div className="space-y-3.5 max-h-[17.5rem] overflow-y-auto pr-1.5 custom-scrollbar">
                                {realtimeData?.criticalAlerts && realtimeData.criticalAlerts.length > 0 ? (
                                    realtimeData.criticalAlerts.map((alert) => (
                                        <div key={alert.id} className="p-3 bg-red-50/50 border border-red-100 rounded-xl flex items-start gap-3 transition-transform hover:scale-[1.01]">
                                            <div className="w-1.5 h-1.5 bg-red-500 rounded-full mt-2 animate-pulse flex-shrink-0" />
                                            <div className="flex-1">
                                                <div className="flex items-center justify-between gap-2">
                                                    <span className="text-xs font-black text-red-900 leading-tight">
                                                        {alert.user_timetable?.user?.name || 'Mahasiswa'}
                                                    </span>
                                                    <span className="text-[9px] font-extrabold uppercase tracking-wider text-red-500 bg-red-100/70 px-2 py-0.5 rounded-md">
                                                        {alert.alert_type || 'Keamanan'}
                                                    </span>
                                                </div>
                                                <p className="text-[10px] text-slate-500 font-bold mt-1">
                                                    Waktu: {new Date(alert.created_at).toLocaleTimeString('id-ID')} ({alert.alert_count || 1}x pemicu)
                                                </p>
                                            </div>
                                        </div>
                                    ))
                                ) : (
                                    <div className="text-center text-slate-400 py-12 flex flex-col items-center justify-center gap-3">
                                        <div className="h-12 w-12 rounded-full bg-emerald-50 flex items-center justify-center text-emerald-500">
                                            <svg className="h-6 w-6" fill="none" stroke="currentColor" strokeWidth="2.5" viewBox="0 0 24 24">
                                                <path strokeLinecap="round" strokeLinejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                            </svg>
                                        </div>
                                        <div>
                                            <p className="text-xs font-black text-emerald-700">Semua Terkendali</p>
                                            <p className="text-[10px] font-semibold text-slate-400 mt-0.5">Tidak ada peringatan keamanan dalam 24 jam terakhir.</p>
                                        </div>
                                    </div>
                                )}
                            </div>
                        </div>

                    </div>

                    {/* Upcoming and Recent Data Tables Grid */}
                    <div className="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">

                        {/* Upcoming exams */}
                        <div className="bg-white/90 rounded-2xl p-6 shadow-sm border border-slate-100 flex flex-col justify-between">
                            <div>
                                <h3 className="text-sm font-bold text-slate-800 uppercase tracking-wide mb-4.5">Ujian Mendatang (7 Hari Terakhir)</h3>
                                <div className="overflow-x-auto">
                                    <table className="w-full text-left text-xs font-semibold text-slate-600">
                                        <thead>
                                            <tr className="border-b border-slate-150 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">
                                                <th className="pb-3">Nama Ujian</th>
                                                <th className="pb-3">Mata Pelajaran</th>
                                                <th className="pb-3 text-right">Mulai</th>
                                            </tr>
                                        </thead>
                                        <tbody className="divide-y divide-slate-100">
                                            {statsData?.upcomingExams && statsData.upcomingExams.length > 0 ? (
                                                statsData.upcomingExams.map((exam) => (
                                                    <tr key={exam.id} className="hover:bg-slate-50/50">
                                                        <td className="py-3 font-bold text-slate-800">{exam.name}</td>
                                                        <td className="py-3">{exam.module?.name || 'Materi'}</td>
                                                        <td className="py-3 text-right text-blue-600 font-bold">
                                                            {new Date(exam.start_time).toLocaleDateString('id-ID', { day: 'numeric', month: 'short' })} {new Date(exam.start_time).toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' })}
                                                        </td>
                                                    </tr>
                                                ))
                                            ) : (
                                                <tr>
                                                    <td colSpan="3" className="py-10 text-center text-slate-400 text-[11px]">
                                                        Tidak ada jadwal ujian mendatang.
                                                    </td>
                                                </tr>
                                            )}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        {/* Recent Results */}
                        <div className="bg-white/90 rounded-2xl p-6 shadow-sm border border-slate-100 flex flex-col justify-between">
                            <div>
                                <h3 className="text-sm font-bold text-slate-800 uppercase tracking-wide mb-4.5">Hasil Ujian Terbaru</h3>
                                <div className="overflow-x-auto">
                                    <table className="w-full text-left text-xs font-semibold text-slate-600">
                                        <thead>
                                            <tr className="border-b border-slate-150 text-[10px] font-extrabold text-slate-400 uppercase tracking-widest">
                                                <th className="pb-3">Mahasiswa</th>
                                                <th className="pb-3">Kategori</th>
                                                <th className="pb-3 text-right">Nilai / Status</th>
                                            </tr>
                                        </thead>
                                        <tbody className="divide-y divide-slate-100">
                                            {statsData?.recentExamResults && statsData.recentExamResults.length > 0 ? (
                                                statsData.recentExamResults.map((result) => (
                                                    <tr key={result.id} className="hover:bg-slate-50/50">
                                                        <td className="py-3 font-bold text-slate-800">
                                                            {result.user?.name || 'Mahasiswa'}
                                                        </td>
                                                        <td className="py-3">{result.timetable?.module?.name || 'Ujian'}</td>
                                                        <td className="py-3 text-right">
                                                            <span className="inline-block px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-lg font-black text-[10px]">
                                                                {result.score !== null ? `${result.score} Poin` : 'Selesai'}
                                                            </span>
                                                        </td>
                                                    </tr>
                                                ))
                                            ) : (
                                                <tr>
                                                    <td colSpan="3" className="py-10 text-center text-slate-400 text-[11px]">
                                                        Tidak ada hasil pengerjaan terbaru.
                                                    </td>
                                                </tr>
                                            )}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                    </div>
                </>
            )}
        </div>
    );
}
