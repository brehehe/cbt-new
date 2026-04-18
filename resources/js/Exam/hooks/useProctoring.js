import { useState, useEffect, useCallback } from 'react';
import axios from 'axios';

export const useProctoring = (userTimetableId, onAlert) => {
    const [isBlackout, setIsBlackout] = useState(false);

    const logAlert = useCallback(async (type, desc) => {
        try {
            const response = await axios.post('/api/exam/log-alert', {
                user_timetable_id: userTimetableId,
                alert_type: type,
                description: desc
            });
            onAlert(response.data.alertCount);
        } catch (error) {
            console.error("Failed to log alert", error);
        }
    }, [userTimetableId, onAlert]);

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
        // Skip confirmation if the exam is being finalized/finished
        if (window.isFinishingExam) return;

        const msg = "Ujian sedang berlangsung. Apakah Anda yakin ingin memuat ulang halaman?";
        e.returnValue = msg;
        return msg;
    }, []);

    useEffect(() => {
        document.addEventListener('visibilitychange', handleVisibilityChange);
        window.addEventListener('blur', handleBlur);
        window.addEventListener('focus', handleFocus);
        window.addEventListener('beforeunload', handleBeforeUnload);

        return () => {
            document.removeEventListener('visibilitychange', handleVisibilityChange);
            window.removeEventListener('blur', handleBlur);
            window.removeEventListener('focus', handleFocus);
            window.removeEventListener('beforeunload', handleBeforeUnload);
        };
    }, [handleVisibilityChange, handleBlur, handleFocus, handleBeforeUnload]);

    return { isBlackout };
};
