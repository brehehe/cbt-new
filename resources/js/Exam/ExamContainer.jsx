import React, { useState, useEffect, useCallback } from 'react';
import axios from 'axios';
import {
    Menu, X, User, Camera, AlertTriangle,
    ChevronLeft, ChevronRight, CheckCircle,
    Clock, LogOut, Loader2
} from 'lucide-react';
import QuestionArea from './components/QuestionArea';
import NavigationSidebar from './components/NavigationSidebar';
import MonitorSidebar from './components/MonitorSidebar';
import { useExamRecording } from './hooks/useExamRecording';
import { useProctoring } from './hooks/useProctoring';
import { useLiveSession } from './hooks/useLiveSession';
import Swal from 'sweetalert2';

const ExamContainer = ({ userTimetableId }) => {
    const [loading, setLoading] = useState(true);
    const [examData, setExamData] = useState(null);
    const [currentQuestionIndex, setCurrentQuestionIndex] = useState(0);
    const [remainingTime, setRemainingTime] = useState(0);
    const [alertCount, setAlertCount] = useState(0);
    const [isSidebarOpen, setIsSidebarOpen] = useState(false);
    const [isRightSidebarOpen, setIsRightSidebarOpen] = useState(false);

    // Proctoring & Recording Hooks
    const { isRecording, stopRecording } = useExamRecording(userTimetableId, examData?.isRecordingEnabled);
    const { isBlackout } = useProctoring(userTimetableId, (count) => setAlertCount(count));
    const { connectionStatus } = useLiveSession(userTimetableId, examData?.isStreamingEnabled);

    // Initial Data Fetch
    useEffect(() => {
        const fetchData = async () => {
            try {
                const response = await axios.get(`/api/exam/${userTimetableId}/data`);
                setExamData(response.data);
                setRemainingTime(response.data.remainingTime);
                setAlertCount(response.data.alertCount);
                setLoading(false);
            } catch (error) {
                console.error("Failed to fetch exam data", error);
                alert("Gagal mengambil data ujian. Silakan refresh halaman.");
            }
        };
        fetchData();
    }, [userTimetableId]);

    // Timer Logic
    useEffect(() => {
        if (!examData || remainingTime <= 0) return;

        const timer = setInterval(() => {
            setRemainingTime(prev => {
                if (prev <= 1) {
                    clearInterval(timer);
                    handleFinishExam();
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
        return [h, m, s].map(v => v < 10 ? "0" + v : v).join(":");
    };

    const handleSaveAnswer = async (answerId, isMarked) => {
        const currentQuestion = examData.questions[currentQuestionIndex];
        try {
            await axios.post('/api/exam/save-answer', {
                question_navigation_id: currentQuestion.id,
                timetable_answer_id: answerId,
                is_mark: isMarked
            });

            // Update local state for navigation
            const newQuestions = [...examData.questions];
            newQuestions[currentQuestionIndex].timetable_answer_id = answerId;
            newQuestions[currentQuestionIndex].is_mark = isMarked;

            const newNavigation = [...examData.navigation];
            newNavigation[currentQuestionIndex].isAnswered = !!answerId;
            newNavigation[currentQuestionIndex].isMarked = isMarked;

            setExamData({ ...examData, questions: newQuestions, navigation: newNavigation });
        } catch (error) {
            console.error("Failed to save answer", error);
        }
    };

    const handleFinishExam = async () => {
        const result = await Swal.fire({
            title: 'Akhiri Ujian?',
            text: "Apakah Anda yakin ingin menyelesaikan ujian ini? Jawaban yang sudah disimpan tidak dapat diubah lagi.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#f58634',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Selesai!',
            cancelButtonText: 'Batal'
        });

        if (result.isConfirmed) {
            try {
                Swal.fire({
                    title: 'Memproses Penilaian & Menyimpan Video...',
                    didOpen: () => Swal.showLoading(),
                    allowOutsideClick: false
                });

                if (examData?.isRecordingEnabled) {
                    await stopRecording();
                }

                const response = await axios.post(`/api/exam/${userTimetableId}/finish`);

                // Skip the leave confirmation and the success modal
                window.isFinishingExam = true;

                if (response.data.redirect_url) {
                    window.location.href = response.data.redirect_url;
                } else {
                    window.location.href = "/admin/exam/timetable";
                }
            } catch (error) {
                console.error("Failed to finish exam", error);
                Swal.fire('Error', 'Gagal memproses penyelesaian ujian.', 'error');
            }
        }
    };

    if (loading) {
        return (
            <div className="flex items-center justify-center min-h-screen">
                <Loader2 className="w-12 h-12 animate-spin text-blue-600" />
                <span className="ml-3 text-lg font-medium">Memproses data ujian...</span>
            </div>
        );
    }

    const currentQuestion = examData.questions[currentQuestionIndex];
    const companyColor = examData.userTimetable.company?.color_primary || '#f58634';

    return (
        <div className="flex flex-col min-h-screen bg-white font-sans text-gray-900">
            {/* Header - Non-sticky as requested */}
            <header className="flex-none p-4 text-white shadow-md relative" style={{ backgroundColor: companyColor }}>
                <div className="max-w-full mx-auto flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div className="flex items-center justify-between gap-4">
                        <div className="px-3 py-1 bg-white/20 backdrop-blur-sm rounded-lg">
                            <span className="text-sm font-medium">Modul: {examData.userTimetable.timetable.module.name}</span>
                        </div>
                        {alertCount > 0 && (
                            <div className="px-3 py-1 bg-red-500/90 text-white rounded-lg animate-pulse flex items-center gap-1">
                                <AlertTriangle className="w-4 h-4" />
                                <span className="text-sm font-bold">{alertCount}</span>
                            </div>
                        )}
                    </div>

                    <div className="flex items-center justify-between md:gap-6">
                        <div className="flex items-center gap-2 font-mono font-bold text-xl tracking-wider">
                            <Clock className="w-5 h-5" />
                            <span>{formatTime(remainingTime)}</span>
                        </div>
                        <button
                            onClick={handleFinishExam}
                            className="px-6 py-2 bg-white/20 hover:bg-white/30 border border-white/30 rounded-lg font-bold text-sm transition-all shadow-sm backdrop-blur-sm"
                        >
                            Selesai Ujian
                        </button>
                    </div>
                </div>
            </header>

            {/* Mobile Sidebar Toggles */}
            <div className="lg:hidden flex border-b bg-gray-50">
                <button
                    onClick={() => setIsSidebarOpen(!isSidebarOpen)}
                    className="flex-1 p-4 flex items-center justify-center gap-2 text-blue-600 font-medium"
                >
                    <Menu className="w-5 h-5" /> Navigasi Soal
                </button>
                <div className="w-px bg-gray-200"></div>
                <button
                    onClick={() => setIsRightSidebarOpen(!isRightSidebarOpen)}
                    className="flex-1 p-4 flex items-center justify-center gap-2 text-blue-600 font-medium"
                >
                    Profil & Camera <User className="w-5 h-5" />
                </button>
            </div>

            {/* Main Layout */}
            <div className="flex flex-1 overflow-hidden relative">
                {/* Left Sidebar: Navigation */}
                <NavigationSidebar
                    navigation={examData.navigation}
                    currentIndex={currentQuestionIndex}
                    setCurrentIndex={setCurrentQuestionIndex}
                    isOpen={isSidebarOpen}
                    setIsOpen={setIsSidebarOpen}
                    companyColor={companyColor}
                />

                {/* Center Content: Question Area */}
                <main className="flex-1 flex flex-col overflow-hidden bg-white">
                    <QuestionArea
                        question={currentQuestion}
                        index={currentQuestionIndex}
                        total={examData.questions.length}
                        onSave={handleSaveAnswer}
                        onNext={() => setCurrentQuestionIndex(prev => Math.min(examData.questions.length - 1, prev + 1))}
                        onPrev={() => setCurrentQuestionIndex(prev => Math.max(0, prev - 1))}
                        onFinish={handleFinishExam}
                    />
                </main>

                {/* Right Sidebar: Camera & Profile */}
                <MonitorSidebar
                    user={examData.userTimetable.user || { name: 'Student' }}
                    percentage={(examData.navigation.filter(n => n.isAnswered).length / examData.navigation.length) * 100}
                    isOpen={isRightSidebarOpen}
                    setIsOpen={setIsRightSidebarOpen}
                    companyColor={companyColor}
                    isRecording={examData.isRecordingEnabled}
                    userTimetableId={userTimetableId}
                />
            </div>

            {/* Blackout Overlay */}
            {isBlackout && (
                <div className="fixed inset-0 bg-black z-[9999] flex flex-col items-center justify-center text-white text-center p-6 bg-opacity-95 backdrop-blur-md">
                    <div className="max-w-md space-y-6">
                        <AlertTriangle className="w-20 h-20 text-red-500 mx-auto animate-bounce" />
                        <h1 className="text-3xl font-extrabold tracking-tight">KONTEN DIALIHKAN</h1>
                        <div className="space-y-4 text-gray-400">
                            <p className="text-lg">Deteksi aktivitas mencurigakan: Anda mencoba meninggalkan jendela ujian.</p>
                            <div className="h-1 w-full bg-gray-800 rounded-full overflow-hidden">
                                <div className="h-full bg-red-600 animate-pulse" style={{ width: '60%' }} />
                            </div>
                            <p className="text-sm italic">Pelanggaran ini telah dicatat dan dilaporkan ke pengawas. Silakan kembali fokus ke jendela ini untuk melanjutkan.</p>
                        </div>
                        <button
                            onClick={() => window.focus()}
                            className="px-8 py-3 bg-white text-black font-bold rounded-xl hover:bg-gray-100 transition-all shadow-xl"
                        >
                            Kembali ke Ujian
                        </button>
                    </div>
                </div>
            )}

            {/* Overlay for mobile sidebars */}
            {(isSidebarOpen || isRightSidebarOpen) && (
                <div
                    className="fixed inset-0 bg-black/50 z-40 lg:hidden"
                    onClick={() => { setIsSidebarOpen(false); setIsRightSidebarOpen(false); }}
                />
            )}
        </div>
    );
};

export default ExamContainer;
