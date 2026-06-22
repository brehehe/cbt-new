import React, { useState, useEffect, useRef } from 'react';
import axios from 'axios';
import {
    AlertTriangle, Clock, Loader2, Shield, Menu, X
} from 'lucide-react';
import QuestionArea from './components/QuestionArea';
import NavigationSidebar from './components/NavigationSidebar';
import MonitorSidebar from './components/MonitorSidebar';
import { useExamRecording } from './hooks/useExamRecording';
import { useProctoring } from './hooks/useProctoring';
import { useLiveSession } from './hooks/useLiveSession';
import Swal from 'sweetalert2';

const ExamContainer = ({ userTimetableId, defaultCompanyColor = '#1e3a5f' }) => {
    const [loading, setLoading] = useState(true);
    const [examData, setExamData] = useState(null);
    const [currentQuestionIndex, setCurrentQuestionIndex] = useState(0);
    const [remainingTime, setRemainingTime] = useState(0);
    const [alertCount, setAlertCount] = useState(0);
    const [isNavOpen, setIsNavOpen] = useState(false);
    const [isMonitorOpen, setIsMonitorOpen] = useState(false);

    // Unified camera stream
    const [cameraStream, setCameraStream] = useState(null);
    const [cameraError, setCameraError] = useState(false);
    const cameraStreamRef = useRef(null);

    const startCamera = async (retryCount = 0) => {
        setCameraError(false);
        try {
            const constraints = { audio: false };
            const savedDeviceId = examData?.liveSession?.camera_device_id;

            if (savedDeviceId) {
                constraints.video = {
                    deviceId: { exact: savedDeviceId },
                    width: { ideal: 640 },
                    height: { ideal: 480 }
                };
            } else {
                constraints.video = {
                    width: { ideal: 640 },
                    height: { ideal: 480 }
                };
            }

            const stream = await navigator.mediaDevices.getUserMedia(constraints);
            cameraStreamRef.current = stream;
            setCameraStream(stream);
        } catch (err) {
            console.warn(`ExamContainer camera error (attempt ${retryCount + 1}):`, err);

            if (retryCount < 2) {
                // Wait 800ms before retrying to allow previous camera locks to release
                await new Promise(resolve => setTimeout(resolve, 800));
                return startCamera(retryCount + 1);
            }

            // Fallback if specific device fails (e.g., ideal constraint rejected or device unplugged)
            try {
                const fallbackStream = await navigator.mediaDevices.getUserMedia({
                    video: { width: { ideal: 640 }, height: { ideal: 480 } },
                    audio: false
                });
                cameraStreamRef.current = fallbackStream;
                setCameraStream(fallbackStream);
                return;
            } catch (fallbackErr) {
                console.warn('ExamContainer fallback camera error:', fallbackErr);
            }
            setCameraError(true);
        }
    };

    const stopCamera = () => {
        if (cameraStreamRef.current) {
            cameraStreamRef.current.getTracks().forEach(t => t.stop());
            cameraStreamRef.current = null;
        }
        setCameraStream(null);
    };

    useEffect(() => {
        if (examData && !cameraStreamRef.current && !cameraError) {
            const needsCamera = examData.isCameraEnabled || examData.isRecordingEnabled || examData.isStreamingEnabled;
            if (needsCamera) {
                startCamera();
            }
        }
    }, [examData, cameraError]);

    useEffect(() => {
        return () => {
            stopCamera();
        };
    }, []);

    // Visited/skipped tracking
    const [visitedIndices, setVisitedIndices] = useState(new Set([0]));
    const [skippedIndices, setSkippedIndices] = useState(new Set());
    const prevIndexRef = useRef(0);

    // Save state
    const [saveStatus, setSaveStatus] = useState('saved');
    const [lastSaved, setLastSaved] = useState('');

    // Hooks
    const { isRecording, stopRecording } = useExamRecording(userTimetableId, examData?.isRecordingEnabled, cameraStream);
    const { isBlackout } = useProctoring(userTimetableId, (count) => setAlertCount(count));
    const { connectionStatus } = useLiveSession(userTimetableId, examData?.isStreamingEnabled, cameraStream);

    const handleFinishExam = async (isAuto = false) => {
        if (isAuto === true) {
            try {
                Swal.fire({
                    title: 'Waktu Habis!',
                    text: 'Menyimpan dan mengirimkan jawaban Anda...',
                    icon: 'info',
                    showConfirmButton: false,
                    allowOutsideClick: false,
                    didOpen: () => Swal.showLoading()
                });
                if (examData?.isRecordingEnabled) await stopRecording();
                const response = await axios.post(`/api/exam/${userTimetableId}/finish`);
                window.isFinishingExam = true;
                window.location.href = response.data.redirect_url || '/admin/exam/timetable';
            } catch (error) {
                console.error('Failed to finish exam', error);
                Swal.fire('Error', 'Gagal memproses penyelesaian ujian.', 'error');
            }
            return;
        }

        const answeredCount = examData.navigation.filter(n => n.isAnswered).length;
        const flaggedCount = examData.navigation.filter(n => n.isMarked).length;
        const totalCount = examData.navigation.length;
        const unansweredCount = totalCount - answeredCount;
        const completionPercentage = Math.round((answeredCount / totalCount) * 100);

        const result = await Swal.fire({
            title: 'Konfirmasi Selesai Ujian',
            html: `
                <div class="text-left text-sm space-y-4">
                    <div class="bg-slate-50 p-4 rounded-xl border space-y-2 font-medium text-slate-700">
                        <div class="flex justify-between"><span>Total Soal:</span> <span class="font-bold">${totalCount}</span></div>
                        <div class="flex justify-between text-green-600"><span>Soal Terjawab:</span> <span class="font-bold">${answeredCount} (${completionPercentage}%)</span></div>
                        <div class="flex justify-between text-red-600"><span>Belum Dijawab:</span> <span class="font-bold">${unansweredCount}</span></div>
                        <div class="flex justify-between text-orange-600"><span>Ragu-ragu:</span> <span class="font-bold">${flaggedCount}</span></div>
                    </div>
                    ${unansweredCount > 0 ? `<p class="text-red-500 font-bold bg-red-50 p-3 rounded-xl border border-red-100">⚠️ Anda memiliki <strong>${unansweredCount}</strong> soal belum dijawab!</p>` : ''}
                    <p class="font-bold text-center text-slate-800 text-sm mt-4">Yakin ingin menyelesaikan ujian? Jawaban tidak dapat diubah setelah dikirim.</p>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#16a34a',
            cancelButtonColor: '#dc2626',
            confirmButtonText: 'Ya, Selesai!',
            cancelButtonText: 'Batal'
        });

        if (result.isConfirmed) {
            try {
                Swal.fire({ title: 'Memproses...', didOpen: () => Swal.showLoading(), allowOutsideClick: false });
                if (examData?.isRecordingEnabled) await stopRecording();
                const response = await axios.post(`/api/exam/${userTimetableId}/finish`);
                window.isFinishingExam = true;
                window.location.href = response.data.redirect_url || '/admin/exam/timetable';
            } catch (error) {
                console.error('Failed to finish exam', error);
                Swal.fire('Error', 'Gagal memproses penyelesaian ujian.', 'error');
            }
        }
    };

    useEffect(() => {
        const fetchData = async () => {
            try {
                const response = await axios.get(`/api/exam/${userTimetableId}/data`);
                setExamData(response.data);
                setRemainingTime(response.data.remainingTime);
                setAlertCount(response.data.alertCount);

                const answered = new Set([0]);
                response.data.navigation.forEach((nav, idx) => {
                    if (nav.isAnswered || nav.isMarked) answered.add(idx);
                });
                setVisitedIndices(answered);
                setLastSaved(new Date().toLocaleTimeString());
                setLoading(false);

                if (response.data.remainingTime <= 0) {
                    handleFinishExam(true);
                }
            } catch (error) {
                console.error('Failed to fetch exam data', error);
                alert('Gagal mengambil data ujian. Silakan refresh halaman.');
            }
        };
        fetchData();
    }, [userTimetableId]);

    // Track visited / skipped
    useEffect(() => {
        if (!loading && examData) {
            const prevIdx = prevIndexRef.current;
            if (prevIdx !== currentQuestionIndex) {
                const prevQ = examData.navigation[prevIdx];
                if (prevQ && !prevQ.isAnswered && !prevQ.isMarked) {
                    setSkippedIndices(prev => { const n = new Set(prev); n.add(prevIdx); return n; });
                }
            }
            setVisitedIndices(prev => { const n = new Set(prev); n.add(currentQuestionIndex); return n; });
            setSkippedIndices(prev => {
                if (prev.has(currentQuestionIndex)) { const n = new Set(prev); n.delete(currentQuestionIndex); return n; }
                return prev;
            });
            prevIndexRef.current = currentQuestionIndex;
        }
    }, [currentQuestionIndex, loading, examData]);

    // Keyboard shortcuts
    useEffect(() => {
        const handleKeyDown = (e) => {
            if (document.activeElement.tagName === 'INPUT' || document.activeElement.tagName === 'TEXTAREA') return;
            if (e.key === 'ArrowLeft') { e.preventDefault(); setCurrentQuestionIndex(p => Math.max(0, p - 1)); }
            else if (e.key === 'ArrowRight') { e.preventDefault(); setCurrentQuestionIndex(p => Math.min(examData.questions.length - 1, p + 1)); }
            else if (e.ctrlKey && e.key.toLowerCase() === 'b') {
                e.preventDefault();
                const q = examData?.questions?.[currentQuestionIndex];
                if (q) handleSaveAnswer(q.timetable_answer_id, !q.is_mark, q.essay_answer);
            }
        };
        if (examData) window.addEventListener('keydown', handleKeyDown);
        return () => window.removeEventListener('keydown', handleKeyDown);
    }, [examData, currentQuestionIndex]);

    // Timer
    useEffect(() => {
        if (!examData || remainingTime <= 0) return;
        const timer = setInterval(() => {
            setRemainingTime(prev => {
                if (prev <= 1) {
                    clearInterval(timer);
                    handleFinishExam(true);
                    return 0;
                }
                return prev - 1;
            });
        }, 1000);
        return () => clearInterval(timer);
    }, [examData, remainingTime]);

    const formatTime = (seconds) => {
        const h = Math.floor(seconds / 3600);
        const m = Math.floor((seconds % 3600) / 60);
        const s = seconds % 60;
        return [h, m, s].map(v => String(v).padStart(2, '0')).join(':');
    };

    const handleSaveAnswer = async (answerId, isMarked, essayAnswer = null) => {
        setSaveStatus('saving');
        const currentQuestion = examData.questions[currentQuestionIndex];
        try {
            await axios.post('/api/exam/save-answer', {
                question_navigation_id: currentQuestion.id,
                timetable_answer_id: answerId,
                essay_answer: essayAnswer,
                is_mark: isMarked
            });
            const newQuestions = [...examData.questions];
            newQuestions[currentQuestionIndex].timetable_answer_id = answerId;
            newQuestions[currentQuestionIndex].essay_answer = essayAnswer;
            newQuestions[currentQuestionIndex].is_mark = isMarked;

            const newNavigation = [...examData.navigation];
            newNavigation[currentQuestionIndex].isAnswered = !!answerId || !!essayAnswer;
            newNavigation[currentQuestionIndex].isMarked = isMarked;

            setExamData({ ...examData, questions: newQuestions, navigation: newNavigation });

            if (answerId || essayAnswer || isMarked) {
                setSkippedIndices(prev => {
                    if (prev.has(currentQuestionIndex)) { const n = new Set(prev); n.delete(currentQuestionIndex); return n; }
                    return prev;
                });
            }
            setSaveStatus('saved');
            setLastSaved(new Date().toLocaleTimeString());
        } catch (error) {
            console.error('Failed to save answer', error);
            setSaveStatus('error');
        }
    };


    if (loading) {
        return (
            <div className="flex items-center justify-center min-h-screen bg-gray-50">
                <Loader2 className="w-10 h-10 animate-spin" style={{ color: defaultCompanyColor }} />
                <span className="ml-3 text-base font-medium text-gray-600">Memproses data ujian...</span>
            </div>
        );
    }

    if (!examData || !examData.questions || examData.questions.length === 0) {
        return (
            <div className="flex flex-col items-center justify-center min-h-screen p-6 text-center bg-gray-50">
                <AlertTriangle className="w-14 h-14 text-red-500 mb-4" />
                <h1 className="text-xl font-bold text-gray-800">Soal Tidak Ditemukan</h1>
                <p className="text-gray-500 mt-2 max-w-md text-sm">Gagal memuat soal. Hubungi admin atau pengawas.</p>
                <button onClick={() => window.location.reload()} className="mt-5 px-5 py-2 text-white rounded-lg font-bold text-sm" style={{ backgroundColor: defaultCompanyColor }}>Refresh</button>
            </div>
        );
    }

    const currentQuestion = examData.questions[currentQuestionIndex];
    const companyColor = examData.userTimetable.company?.color_primary || defaultCompanyColor;
    const answeredCount = examData.navigation.filter(n => n.isAnswered).length;
    const userName = examData.userTimetable.user?.name || 'Admin CBT';

    return (
        <div className="flex flex-col h-screen bg-gray-100 overflow-hidden font-sans text-gray-900">

            {/* ── Top Header ── */}
            <header className="flex-none flex items-center justify-between px-4 py-2.5 bg-white border-b border-gray-200 shadow-sm z-30">
                {/* Left: Logo + Title */}
                <div className="flex items-center gap-3">
                    <div className="w-8 h-8 rounded-lg flex items-center justify-center" style={{ backgroundColor: `${companyColor}18` }}>
                        <Shield className="w-4 h-4" style={{ color: companyColor }} />
                    </div>
                    <div className="leading-tight">
                        <div className="font-bold text-sm text-gray-800">Sesi Ujian</div>
                        <div className="text-[10px] text-gray-400 flex items-center gap-1">
                            <span className="w-1.5 h-1.5 bg-green-500 rounded-full inline-block animate-pulse" />
                            Soal Diawasi Aktif
                        </div>
                    </div>
                </div>

                {/* Center: User + Progress */}
                <div className="hidden md:flex items-center gap-2 text-sm font-medium">
                    <span className="text-gray-700 font-semibold">{userName}</span>
                    <span className="text-gray-300">·</span>
                    <span className="text-gray-500 text-xs">{answeredCount} / {examData.questions.length} soal dijawab</span>
                    {alertCount > 0 && (
                        <div className="ml-2 px-2 py-0.5 bg-red-500 text-white rounded-md flex items-center gap-1 text-xs font-bold animate-pulse">
                            <AlertTriangle className="w-3 h-3" /> {alertCount} Pelanggaran
                        </div>
                    )}
                </div>

                {/* Right: Timer + Mobile toggles */}
                <div className="flex items-center gap-3">
                    <div className="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border" style={{ backgroundColor: `${companyColor}10`, borderColor: `${companyColor}30` }}>
                        <Clock className="w-4 h-4" style={{ color: '#16a34a' }} />
                        <span className="font-mono font-bold text-sm tracking-widest" style={{ color: companyColor }}>{formatTime(remainingTime)}</span>
                    </div>
                    {/* Mobile toggles */}
                    <div className="flex lg:hidden gap-2">
                        <button onClick={() => setIsNavOpen(!isNavOpen)} className="p-1.5 rounded-lg border border-gray-200 text-gray-600"><Menu className="w-4 h-4" /></button>
                        <button onClick={() => setIsMonitorOpen(!isMonitorOpen)} className="p-1.5 rounded-lg border border-gray-200 text-gray-600"><Shield className="w-4 h-4" /></button>
                    </div>
                </div>
            </header>

            {/* ── Main 3-Column Layout ── */}
            <div className="flex flex-1 overflow-hidden">

                {/* ── Left: Navigation Sidebar ── */}
                <NavigationSidebar
                    navigation={examData.navigation}
                    currentIndex={currentQuestionIndex}
                    setCurrentIndex={setCurrentQuestionIndex}
                    isOpen={isNavOpen}
                    setIsOpen={setIsNavOpen}
                    companyColor={companyColor}
                    visitedIndices={visitedIndices}
                    skippedIndices={skippedIndices}
                    onFinish={handleFinishExam}
                />

                {/* ── Center: Question Area ── */}
                <main className="flex-1 flex flex-col overflow-hidden bg-gray-50 min-w-0">
                    {currentQuestion ? (
                        <QuestionArea
                            question={currentQuestion}
                            index={currentQuestionIndex}
                            total={examData.questions.length}
                            onSave={handleSaveAnswer}
                            onNext={() => setCurrentQuestionIndex(p => Math.min(examData.questions.length - 1, p + 1))}
                            onPrev={() => setCurrentQuestionIndex(p => Math.max(0, p - 1))}
                            onFinish={handleFinishExam}
                            setCurrentIndex={setCurrentQuestionIndex}
                            saveStatus={saveStatus}
                            lastSaved={lastSaved}
                            navigation={examData.navigation}
                            companyColor={companyColor}
                        />
                    ) : (
                        <div className="flex items-center justify-center h-full">
                            <p className="text-gray-400 text-sm">Pilih soal untuk melanjutkan</p>
                        </div>
                    )}
                </main>

                {/* ── Right: Monitor Sidebar ── */}
                <MonitorSidebar
                    user={examData.userTimetable.user || { name: 'Student' }}
                    alertCount={alertCount}
                    percentage={(answeredCount / examData.navigation.length) * 100}
                    isOpen={isMonitorOpen}
                    setIsOpen={setIsMonitorOpen}
                    companyColor={companyColor}
                    isRecording={examData.isRecordingEnabled}
                    userTimetableId={userTimetableId}
                    isCameraEnabled={examData.isCameraEnabled}
                    connectionStatus={connectionStatus}
                    cameraStream={cameraStream}
                    cameraError={cameraError}
                    startCamera={startCamera}
                    stopCamera={stopCamera}
                />
            </div>

            {/* Blackout Overlay */}
            {isBlackout && (
                <div className="fixed inset-0 bg-black/95 z-[9999] flex flex-col items-center justify-center text-white text-center p-6">
                    <div className="max-w-md space-y-5">
                        <AlertTriangle className="w-16 h-16 text-red-500 mx-auto animate-bounce" />
                        <h1 className="text-2xl font-extrabold">KONTEN DIALIHKAN</h1>
                        <p className="text-gray-400">Pelanggaran terdeteksi. Harap kembali ke jendela ujian.</p>
                        <button onClick={() => window.focus()} className="px-6 py-2.5 bg-white text-black font-bold rounded-xl hover:bg-gray-100">Kembali ke Ujian</button>
                    </div>
                </div>
            )}

            {/* Mobile overlay */}
            {(isNavOpen || isMonitorOpen) && (
                <div className="fixed inset-0 bg-black/50 z-40 lg:hidden" onClick={() => { setIsNavOpen(false); setIsMonitorOpen(false); }} />
            )}
        </div>
    );
};

export default ExamContainer;
