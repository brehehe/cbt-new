import { useState, useEffect, useCallback, useRef } from 'react';
import axios from 'axios';

export const useProctoring = (userTimetableId, onAlert) => {
    const [isBlackout, setIsBlackout] = useState(false);
    const [isFullscreen, setIsFullscreen] = useState(true);
    const [fullscreenWarning, setFullscreenWarning] = useState(false);
    const [taskbarWarning, setTaskbarWarning] = useState(null);

    const lastTaskbarAlertTimeRef = useRef(0);

    const logAlert = useCallback(async (type, desc) => {
        try {
            const response = await axios.post('/api/exam/log-alert', {
                user_timetable_id: userTimetableId,
                alert_type: type,
                description: desc
            });
            if (onAlert && response.data?.alertCount !== undefined) {
                onAlert(response.data.alertCount);
            }
        } catch (error) {
            console.error("Failed to log alert", error);
        }
    }, [userTimetableId, onAlert]);

    const getIsFullscreen = () => {
        return !!(
            document.fullscreenElement ||
            document.webkitFullscreenElement ||
            document.mozFullScreenElement ||
            document.msFullscreenElement
        );
    };

    const requestFullscreen = useCallback(() => {
        const elem = document.documentElement;
        if (elem.requestFullscreen) {
            elem.requestFullscreen().catch(() => {});
        } else if (elem.webkitRequestFullscreen) {
            elem.webkitRequestFullscreen();
        } else if (elem.mozRequestFullScreen) {
            elem.mozRequestFullScreen();
        } else if (elem.msRequestFullscreen) {
            elem.msRequestFullscreen();
        }
    }, []);

    const handleFullscreenChange = useCallback(() => {
        const active = getIsFullscreen();
        setIsFullscreen(active);
        if (!active) {
            logAlert('not_fullscreen', 'Mahasiswa keluar dari mode Fullscreen (Jendela tidak maksimal)');
            setFullscreenWarning(true);
        } else {
            setFullscreenWarning(false);
        }
    }, [logAlert]);

    const handleVisibilityChange = useCallback(() => {
        if (document.hidden) {
            logAlert('tab_switch', 'Mahasiswa berpindah tab / meninggalkan halaman');
            setIsBlackout(true);
        } else {
            setIsBlackout(false);
        }
    }, [logAlert]);

    const handleBlur = useCallback(() => {
        logAlert('window_blur', 'Mahasiswa kehilangan fokus pada jendela browser');
        setIsBlackout(true);
    }, [logAlert]);

    const handleFocus = useCallback(() => {
        setIsBlackout(false);
    }, []);

    const handleBeforeUnload = useCallback((e) => {
        if (window.isFinishingExam) return;

        logAlert('page_reload', 'Mahasiswa melakukan reload / refresh halaman');

        const msg = "Ujian sedang berlangsung. Apakah Anda yakin ingin memuat ulang halaman?";
        e.returnValue = msg;
        return msg;
    }, [logAlert]);

    const handleKeyDown = useCallback((e) => {
        if (
            e.key === 'F5' ||
            ((e.ctrlKey || e.metaKey) && (e.key === 'r' || e.key === 'R'))
        ) {
            logAlert('page_reload', 'Mahasiswa menekan tombol shortcut reload (F5 / Ctrl+R)');
        }
    }, [logAlert]);

    const handleMouseMove = useCallback((e) => {
        const thresholdBottom = window.innerHeight - 10;
        const thresholdTop = 5;

        const isTouchingBottom = e.clientY >= thresholdBottom;
        const isTouchingTop = e.clientY <= thresholdTop;

        if (isTouchingBottom || isTouchingTop) {
            const now = Date.now();
            if (now - lastTaskbarAlertTimeRef.current > 5000) {
                lastTaskbarAlertTimeRef.current = now;
                const desc = isTouchingBottom
                    ? 'Kursor menyentuh area taskbar bawah'
                    : 'Kursor menyentuh area tombol reload / header browser';
                logAlert('cursor_taskbar_touch', desc);
                setTaskbarWarning(desc);
                setTimeout(() => setTaskbarWarning(null), 4000);
            }
        }
    }, [logAlert]);

    const handleMouseLeave = useCallback((e) => {
        if (!e.relatedTarget && (e.clientY >= window.innerHeight - 15 || e.clientY <= 5)) {
            const now = Date.now();
            if (now - lastTaskbarAlertTimeRef.current > 5000) {
                lastTaskbarAlertTimeRef.current = now;
                const desc = e.clientY >= window.innerHeight - 15
                    ? 'Kursor menyentuh dan keluar ke area taskbar bawah'
                    : 'Kursor keluar ke area atas browser / tombol reload';
                logAlert('cursor_taskbar_touch', desc);
                setTaskbarWarning(desc);
                setTimeout(() => setTaskbarWarning(null), 4000);
            }
        }
    }, [logAlert]);

    useEffect(() => {
        document.addEventListener('visibilitychange', handleVisibilityChange);
        window.addEventListener('blur', handleBlur);
        window.addEventListener('focus', handleFocus);
        window.addEventListener('beforeunload', handleBeforeUnload);
        window.addEventListener('keydown', handleKeyDown);
        window.addEventListener('mousemove', handleMouseMove);
        window.addEventListener('mouseleave', handleMouseLeave);

        document.addEventListener('fullscreenchange', handleFullscreenChange);
        document.addEventListener('webkitfullscreenchange', handleFullscreenChange);
        document.addEventListener('mozfullscreenchange', handleFullscreenChange);
        document.addEventListener('MSFullscreenChange', handleFullscreenChange);

        return () => {
            document.removeEventListener('visibilitychange', handleVisibilityChange);
            window.removeEventListener('blur', handleBlur);
            window.removeEventListener('focus', handleFocus);
            window.removeEventListener('beforeunload', handleBeforeUnload);
            window.removeEventListener('keydown', handleKeyDown);
            window.removeEventListener('mousemove', handleMouseMove);
            window.removeEventListener('mouseleave', handleMouseLeave);

            document.removeEventListener('fullscreenchange', handleFullscreenChange);
            document.removeEventListener('webkitfullscreenchange', handleFullscreenChange);
            document.removeEventListener('mozfullscreenchange', handleFullscreenChange);
            document.removeEventListener('MSFullscreenChange', handleFullscreenChange);
        };
    }, [
        handleVisibilityChange,
        handleBlur,
        handleFocus,
        handleBeforeUnload,
        handleKeyDown,
        handleMouseMove,
        handleMouseLeave,
        handleFullscreenChange
    ]);

    return {
        isBlackout,
        isFullscreen,
        fullscreenWarning,
        taskbarWarning,
        requestFullscreen
    };
};
