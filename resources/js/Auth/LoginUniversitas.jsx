import React, { useState, useEffect, useRef, useCallback } from 'react';
import axios from 'axios';

// A lightweight, secure debounce helper
const useDebounce = (callback, delay) => {
    const timer = useRef();
    useEffect(() => {
        return () => {
            if (timer.current) clearTimeout(timer.current);
        };
    }, []);
    return useCallback((...args) => {
        if (timer.current) clearTimeout(timer.current);
        timer.current = setTimeout(() => {
            callback(...args);
        }, delay);
    }, [callback, delay]);
};

export default function LoginUniversitas({
    company = {},
    isCredentials = false,
    credentials = {},
    appWindows = '',
    appMac = '',
    appAndroid = '',
    appIos = ''
}) {
    const [usernameOrEmail, setUsernameOrEmail] = useState('');
    const [password, setPassword] = useState('');
    const [remember, setRemember] = useState(false);
    
    const [showPassword, setShowPassword] = useState(false);
    const [loading, setLoading] = useState(false);
    const [errorMsg, setErrorMsg] = useState('');
    const [activeSession, setActiveSession] = useState(null);

    // Active session lookup logic
    const checkSessionRequest = useCallback(async (value) => {
        if (!value) {
            setActiveSession(null);
            return;
        }
        try {
            const response = await axios.post('/api/login/check-session', {
                username_or_email: value
            });
            if (response.data && response.data.hasActiveSession) {
                setActiveSession(response.data.activeSessionInfo);
            } else {
                setActiveSession(null);
            }
        } catch (err) {
            console.warn('Session check failed', err);
        }
    }, []);

    const debouncedCheckSession = useDebounce(checkSessionRequest, 500);

    const handleUsernameChange = (e) => {
        const val = e.target.value;
        setUsernameOrEmail(val);
        debouncedCheckSession(val);
    };

    // Prefill role credentials instantly
    const handlePrefill = (role) => {
        if (credentials[role]) {
            setUsernameOrEmail(credentials[role].username_or_email || '');
            setPassword(credentials[role].password || '');
            // Trigger check session directly for the filled username
            checkSessionRequest(credentials[role].username_or_email);
        }
    };

    // Authenticate and Login
    const handleLoginSubmit = async (e) => {
        e.preventDefault();
        if (loading) return;

        setLoading(true);
        setErrorMsg('');

        try {
            const response = await axios.post('/api/login/react', {
                username_or_email: usernameOrEmail,
                password: password,
                remember: remember
            });

            if (response.data && response.data.success) {
                // Flash success notification using SweetAlert2 if loaded
                if (window.Swal) {
                    window.Swal.fire({
                        title: 'Login Berhasil!',
                        text: 'Anda berhasil login ke sistem!',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false,
                        timerProgressBar: true
                    });
                }
                
                // Redirect to dashboard
                setTimeout(() => {
                    window.location.href = response.data.redirect_url;
                }, 1000);
            }
        } catch (err) {
            setLoading(false);
            if (err.response && err.response.data && err.response.data.message) {
                setErrorMsg(err.response.data.message);
                if (window.Swal) {
                    window.Swal.fire({
                        title: 'Gagal',
                        text: err.response.data.message,
                        icon: 'error',
                        timer: 3000,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false
                    });
                }
            } else {
                setErrorMsg('Terjadi kesalahan saat mencoba masuk. Silakan coba lagi.');
            }
        }
    };

    return (
        <div className="relative h-screen w-full overflow-hidden bg-[#dce0f4] font-sans selection:bg-blue-500 selection:text-white">
            
            {/* Advanced Animated Fluid Background */}
            <div className="absolute inset-0 z-0">
                {/* Gradient Mesh Layers */}
                <div className="absolute inset-0 opacity-40">
                    <div 
                        className="absolute left-0 top-0 h-full w-full animate-pulse bg-gradient-to-br from-blue-500/20 via-indigo-500/15 to-cyan-500/20"
                        style={{ animationDuration: '8s' }}
                    />
                    <div 
                        className="absolute inset-0 animate-pulse bg-gradient-to-tl from-blue-600/15 via-transparent to-blue-400/20"
                        style={{ animationDuration: '12s', animationDelay: '2s' }}
                    />
                </div>

                {/* Floating Interactive elements */}
                <div className="absolute left-20 top-20 h-4 w-4 animate-bounce cursor-pointer rounded-full bg-blue-500/40 blur-[1px] transition-all duration-500 hover:scale-150 hover:bg-blue-400" />
                <div 
                    className="absolute right-24 top-1/3 h-8 w-2 animate-pulse cursor-pointer bg-indigo-500/40 rounded transition-all duration-500 hover:h-16 hover:bg-indigo-400"
                    style={{ animationDuration: '4s' }}
                />
                <div 
                    className="absolute bottom-1/4 left-1/4 h-5 w-5 rotate-45 animate-spin cursor-pointer bg-cyan-500/40 transition-all duration-1000 hover:scale-125 hover:bg-cyan-400"
                    style={{ animationDuration: '15s' }}
                />
                <div className="absolute bottom-20 right-20 h-1.5 w-12 animate-pulse cursor-pointer bg-blue-600/40 rounded transition-all duration-500 hover:w-20 hover:bg-blue-500" />
            </div>

            {/* Main Flex Layout Container */}
            <div className="relative z-10 flex h-full w-full">
                
                {/* Left Side: Dynamic Company Showcase Panel (Hidden on Mobile) */}
                <div className="group relative hidden h-full overflow-hidden lg:block lg:w-3/5">
                    {/* Brand Banner Image with custom slanted polygon clip-path */}
                    <div 
                        className="absolute inset-0 bg-gradient-to-br from-blue-600 via-blue-700 to-blue-900 transition-all duration-1000 group-hover:from-blue-500 group-hover:via-blue-600 group-hover:to-blue-800"
                        style={{ 
                            clipPath: 'polygon(0 0, 100% 0, 85% 100%, 0 100%)',
                            backgroundImage: `url(${company.background_login ? `/storage/${company.background_login}` : '/asset/img/auth-pro-cbt.webp'})`,
                            backgroundSize: 'cover',
                            backgroundPosition: 'center'
                        }}
                    />

                    {/* Matrix/Pattern overlay for visual depth */}
                    <div className="absolute inset-0 opacity-10">
                        <div 
                            className="h-full w-full"
                            style={{ 
                                backgroundImage: 'radial-gradient(circle at 2px 2px, rgba(255,255,255,0.3) 1px, transparent 0)',
                                backgroundSize: '40px 40px'
                            }}
                        />
                    </div>

                    {/* Logo Area */}
                    <div className="relative z-20 flex h-full flex-col p-8 text-white xl:p-12">
                        <div className="space-y-6">
                            <div className="group/brand flex items-center space-x-3 transition-transform duration-500 hover:translate-x-2">
                                <div className="relative">
                                    <img 
                                        alt="Company Logo" 
                                        className="object-contain filter drop-shadow-[0_4px_6px_rgba(0,0,0,0.3)]" 
                                        style={{ width: '215px' }} 
                                        src={company.logo ? `/storage/${company.logo}` : '/asset/img/logo-procbt.png'} 
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {/* Right Side: Sleek Login Form Panel */}
                <div className="relative flex h-full w-full items-center justify-center p-4 lg:w-2/5 lg:p-8 xl:p-16">
                    
                    {/* Header bar visible ONLY on mobile screens */}
                    <div className="absolute left-4 top-4 z-20 lg:hidden">
                        <div className="inline-flex items-center space-x-3 rounded-2xl border border-white/40 bg-white/80 px-4 py-2 shadow-lg backdrop-blur-xl transition-all duration-300 hover:scale-105">
                            <div className="flex h-8 w-8 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-blue-600 shadow-md shadow-blue-500/20">
                                <svg className="h-4.5 w-4.5 text-white" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                                    <path strokeLinecap="round" strokeLinejoin="round" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10v11M20 10v11" />
                                </svg>
                            </div>
                            <span className="text-xs font-bold text-gray-700 tracking-wide uppercase">CBT System</span>
                        </div>
                    </div>

                    {/* Glassmorphic Login Card */}
                    <div className="w-full max-w-md">
                        <div className="relative">
                            {/* Colorful background neon blur behind the card */}
                            <div className="absolute -inset-1.5 rounded-[2rem] bg-gradient-to-r from-blue-500/20 via-indigo-500/10 to-cyan-500/20 opacity-70 blur-2xl filter" />

                            {/* Main Card Element */}
                            <div className="relative rounded-[2rem] border border-white/50 bg-white/90 p-6 shadow-[0_20px_50px_rgba(0,0,0,0.08)] backdrop-blur-2xl transition-all duration-500 hover:shadow-[0_30px_70px_rgba(59,130,246,0.12)] lg:p-8">
                                
                                {/* Card Title / Branding */}
                                <div className="mb-6 text-center">
                                    <div className="relative inline-block transition-transform duration-500 hover:scale-105 mb-2">
                                        <img 
                                            src={company.logo_potrait ? `/storage/${company.logo_potrait}` : '/asset/img/logo-procbt.png'} 
                                            className="w-32 h-24 object-contain filter drop-shadow-[0_2px_4px_rgba(0,0,0,0.05)]" 
                                            alt="Logo Portrait" 
                                        />
                                    </div>
                                    <h2 className="text-xl font-extrabold text-gray-800 tracking-tight lg:text-2xl">
                                        {company.name || 'PRO CBT'}
                                    </h2>
                                    <p className="text-sm font-medium text-gray-500 mt-1">
                                        Masuk ke sistem CBT
                                    </p>
                                </div>

                                {/* Active Session Conflict Alert Banner */}
                                {activeSession && (
                                    <div className="mb-5 animate-fadeIn rounded-2xl border border-red-100 bg-red-50/75 p-4 shadow-sm backdrop-blur-sm">
                                        <div className="flex items-start">
                                            <div className="flex-shrink-0 mt-0.5">
                                                <svg className="h-5 w-5 text-red-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fillRule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clipRule="evenodd" />
                                                </svg>
                                            </div>
                                            <div className="ml-3">
                                                <h3 className="text-xs font-bold text-red-800 uppercase tracking-wide">
                                                    Login Tidak Diizinkan
                                                </h3>
                                                <div className="mt-1 text-xs text-red-700 leading-relaxed font-medium">
                                                    <p>Akun <strong>{activeSession.username}</strong> sudah aktif di perangkat lain.</p>
                                                    <p className="mt-1">Silakan keluar dari perangkat lain atau hubungi pengawas untuk bantuan.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                )}

                                {/* Error/Failure Flash Banner */}
                                {errorMsg && (
                                    <div className="mb-5 rounded-xl border border-amber-200 bg-amber-50 p-3.5 text-xs font-semibold text-amber-900 shadow-sm flex items-center gap-2.5 animate-pulse">
                                        <svg className="h-4.5 w-4.5 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                        </svg>
                                        <span>{errorMsg}</span>
                                    </div>
                                )}

                                {/* Form Component */}
                                <form onSubmit={handleLoginSubmit} className="space-y-5">
                                    
                                    {/* Username or Email Input Group */}
                                    <div className="space-y-1.5">
                                        <label className="text-xs font-bold text-gray-700 tracking-wide">Username / Email / NIM</label>
                                        <div className="relative group">
                                            <div className="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 group-focus-within:text-blue-500 transition-colors duration-200">
                                                <svg className="h-5 w-5" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                                                    <path strokeLinecap="round" strokeLinejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                                </svg>
                                            </div>
                                            <input 
                                                type="text" 
                                                required
                                                disabled={loading}
                                                value={usernameOrEmail}
                                                onChange={handleUsernameChange}
                                                placeholder="Masukkan username, email, atau NIM" 
                                                className="block w-full rounded-xl border border-gray-200 bg-gray-50/50 py-3.5 pl-11 pr-4 text-sm font-medium text-gray-800 placeholder-gray-400 shadow-inner outline-none transition-all duration-200 hover:border-gray-300 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 disabled:opacity-50"
                                            />
                                        </div>
                                    </div>

                                    {/* Password Input Group */}
                                    <div className="space-y-1.5">
                                        <label className="text-xs font-bold text-gray-700 tracking-wide">Password</label>
                                        <div className="relative group">
                                            <div className="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400 group-focus-within:text-blue-500 transition-colors duration-200">
                                                <svg className="h-5 w-5" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                                                    <path strokeLinecap="round" strokeLinejoin="round" d="M15 7a2 2 0 012 2m0 0a2 2 0 01-2 2m2-2h3m-3.193 2.858L10 17H7v-3l4.858-4.858M17 7a3 3 0 11-6 0 3 3 0 016 0z" />
                                                </svg>
                                            </div>
                                            <input 
                                                type={showPassword ? "text" : "password"} 
                                                required
                                                disabled={loading}
                                                value={password}
                                                onChange={(e) => setPassword(e.target.value)}
                                                placeholder="Masukkan password Anda" 
                                                className="block w-full rounded-xl border border-gray-200 bg-gray-50/50 py-3.5 pl-11 pr-11 text-sm font-medium text-gray-800 placeholder-gray-400 shadow-inner outline-none transition-all duration-200 hover:border-gray-300 focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10 disabled:opacity-50"
                                            />
                                            {/* Password visibility toggle icon */}
                                            <button 
                                                type="button"
                                                onClick={() => setShowPassword(!showPassword)}
                                                className="absolute inset-y-0 right-0 pr-3.5 flex items-center text-gray-400 hover:text-gray-600 transition-colors"
                                            >
                                                {showPassword ? (
                                                    <svg className="h-5 w-5" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                                                    </svg>
                                                ) : (
                                                    <svg className="h-5 w-5" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path strokeLinecap="round" strokeLinejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                )}
                                            </button>
                                        </div>
                                    </div>

                                    {/* Remember Session Option */}
                                    <div className="flex items-center justify-between pt-1">
                                        <label className="flex items-center gap-2.5 cursor-pointer group">
                                            <input 
                                                type="checkbox" 
                                                checked={remember}
                                                disabled={loading}
                                                onChange={(e) => setRemember(e.target.checked)}
                                                className="h-4.5 w-4.5 rounded-md border-gray-300 text-blue-500 outline-none focus:ring-0 focus:ring-offset-0 cursor-pointer" 
                                            />
                                            <span className="text-xs font-semibold text-gray-600 transition-colors group-hover:text-gray-800">
                                                Ingat saya
                                            </span>
                                        </label>
                                    </div>

                                    {/* Submit Action Button */}
                                    <div className="pt-2">
                                        <button 
                                            type="submit" 
                                            disabled={loading}
                                            className="relative flex w-full items-center justify-center gap-2 rounded-2xl bg-blue-500 py-3.5 px-4 text-sm font-bold text-white shadow-xl shadow-blue-500/20 transition-all duration-300 hover:scale-[1.02] hover:bg-blue-600 hover:shadow-blue-600/30 focus:outline-none focus:ring-4 focus:ring-blue-500/20 disabled:scale-100 disabled:opacity-60 cursor-pointer"
                                        >
                                            {loading ? (
                                                <div className="h-5 w-5 animate-spin rounded-full border-3 border-white border-t-transparent" />
                                            ) : (
                                                <>
                                                    <span>Masuk</span>
                                                    <svg className="h-4.5 w-4.5 transition-transform duration-300 group-hover:translate-x-1" fill="none" stroke="currentColor" strokeWidth="2.5" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                    </svg>
                                                </>
                                            )}
                                        </button>
                                    </div>

                                    {/* Pre-fill Quick Roles Section (If config enables it) */}
                                    {isCredentials && (
                                        <div className="mt-4 pt-3 border-t border-gray-100">
                                            <div className="text-[10px] font-bold text-gray-400 uppercase tracking-widest text-center mb-2.5">
                                                Quick Access Roles (Testing)
                                            </div>
                                            <div className="grid grid-cols-2 gap-2">
                                                {Object.keys(credentials).map((role) => (
                                                    <button 
                                                        key={role}
                                                        type="button" 
                                                        onClick={() => handlePrefill(role)}
                                                        className="flex w-full items-center justify-center rounded-xl border border-gray-150 bg-gray-50 px-3 py-2 text-xs font-semibold text-gray-600 hover:text-blue-600 hover:border-blue-200 hover:bg-blue-50/50 transition-all duration-200 capitalize"
                                                    >
                                                        {role}
                                                    </button>
                                                ))}
                                            </div>
                                        </div>
                                    )}
                                </form>

                                {/* Apps Download Area (If configured) */}
                                {(appWindows || appMac || appAndroid || appIos) && (
                                    <div className="mt-6 border-t border-gray-100 pt-4">
                                        <h3 className="text-[10px] font-bold text-gray-400 text-center mb-3 uppercase tracking-widest">
                                            Download Aplikasi
                                        </h3>
                                        <div className="flex justify-center flex-wrap gap-2">
                                            {appWindows && (
                                                <a 
                                                    href={appWindows}
                                                    className="flex items-center gap-1.5 px-3 py-2 bg-blue-50/60 text-blue-700 hover:bg-blue-100/80 rounded-xl text-xs font-bold transition-all hover:scale-105"
                                                >
                                                    <svg className="h-4 w-4" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                    </svg>
                                                    Windows
                                                </a>
                                            )}
                                            {appMac && (
                                                <a 
                                                    href={appMac}
                                                    className="flex items-center gap-1.5 px-3 py-2 bg-gray-50 text-gray-700 hover:bg-gray-100 rounded-xl text-xs font-bold transition-all hover:scale-105"
                                                >
                                                    <svg className="h-4 w-4" fill="none" stroke="currentColor" strokeWidth="2" viewBox="0 0 24 24">
                                                        <path strokeLinecap="round" strokeLinejoin="round" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                    </svg>
                                                    Mac
                                                </a>
                                            )}
                                            {appAndroid && (
                                                <a 
                                                    href={appAndroid}
                                                    className="flex items-center gap-1.5 px-3 py-2 bg-green-50 text-green-700 hover:bg-green-100 rounded-xl text-xs font-bold transition-all hover:scale-105"
                                                >
                                                    <svg className="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M16.6026 12.0253L13.7118 16.9946L16.2737 21.432C16.5959 20.9126 17.0628 20.4862 17.6186 20.1983C18.1744 19.9103 18.7951 19.7728 19.4211 19.8021C20.0471 19.8315 20.6517 20.0264 21.1628 20.3638L21.8491 20.8166C21.7259 20.5746 21.6575 20.3065 21.6526 20.0353V3.96464C21.6575 3.69345 21.7259 3.42531 21.8491 3.18337L21.1628 3.63618C20.6517 3.97354 20.0471 4.16843 19.4211 4.19782C18.7951 4.2272 18.1744 4.08972 17.6186 3.80173C17.0628 3.51374 16.5959 3.08731 16.2737 2.56793L13.7118 7.00532L16.6026 11.9746V12.0253Z" />
                                                    </svg>
                                                    Android
                                                </a>
                                            )}
                                            {appIos && (
                                                <a 
                                                    href={appIos}
                                                    className="flex items-center gap-1.5 px-3 py-2 bg-gray-100 text-gray-800 hover:bg-gray-200 rounded-xl text-xs font-bold transition-all hover:scale-105"
                                                >
                                                    <svg className="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.79-1.31.02-2.3-1.23-3.14-2.47-1.72-2.5-3.03-7.07-1.26-10.13 0.88-1.5 2.45-2.47 4.16-2.5 1.3 0 2.52.88 3.3.88 0.77 0 2.22-1.09 3.73-0.93 0.64.03 2.43.26 3.58 1.94-0.09.06-2.14 1.25-2.12 3.72 0.03 2.96 2.59 3.96 2.65 4-0.02.06-0.41 1.41-1.37 2.82h0ZM13 3.5c.67-.82 1.13-1.95 1.01-3.09-0.97.04-2.14.65-2.83 1.46-.61.7-1.12 1.83-0.99 3.05 1.08.08 2.18-.59 2.81-1.42h0Z" />
                                                    </svg>
                                                    iOS
                                                </a>
                                            )}
                                        </div>
                                    </div>
                                )}
                            </div>
                        </div>

                        {/* Status Pills Block and Footer */}
                        <div className="mt-6 space-y-4 text-center">
                            
                            {/* Online and Security pills */}
                            <div className="flex justify-center">
                                <div className="inline-flex items-center gap-3.5 rounded-full border border-white/40 bg-white/70 px-4 py-2 shadow-md backdrop-blur-md transition-all duration-300 hover:bg-white/80">
                                    <div className="flex items-center gap-1.5">
                                        <div className="h-2 w-2 animate-ping rounded-full bg-emerald-500 absolute" />
                                        <div className="h-2 w-2 rounded-full bg-emerald-500 relative" />
                                        <span className="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Online</span>
                                    </div>
                                    <div className="h-3 w-px bg-gray-300" />
                                    <div className="flex items-center gap-1.5">
                                        <svg className="h-3.5 w-3.5 text-blue-500" fill="none" stroke="currentColor" strokeWidth="2.5" viewBox="0 0 24 24">
                                            <path strokeLinecap="round" strokeLinejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                        <span className="text-[10px] font-bold text-gray-500 uppercase tracking-wider">Secure</span>
                                    </div>
                                </div>
                            </div>

                            {/* Footer Copy */}
                            <p className="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                                © {new Date().getFullYear()} PRO CBT
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
