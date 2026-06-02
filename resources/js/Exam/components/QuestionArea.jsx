import React, { useState, useEffect } from 'react';
import {
    ChevronLeft, ChevronRight, CheckCircle,
    HelpCircle, Image as ImageIcon, ZoomIn
} from 'lucide-react';
import { motion, AnimatePresence } from 'framer-motion';
import LatexHTML from './LatexHTML';

const QuestionArea = ({ question, index, total, onSave, onNext, onPrev, onFinish }) => {
    const [selectedAnswerId, setSelectedAnswerId] = useState(question?.timetable_answer_id);
    const [essayAnswer, setEssayAnswer] = useState(question?.essay_answer || '');
    const [isMarked, setIsMarked] = useState(question?.is_mark);
    const [viewedImage, setViewedImage] = useState(null);

    // Helper functions for media types
    const getExtension = (filename) => filename?.split('.').pop().toLowerCase();
    const isVideo = (filename) => ['mp4', 'mov', 'avi', 'wmv', 'webm'].includes(getExtension(filename));
    const isAudio = (filename) => ['mp3', 'wav', 'ogg'].includes(getExtension(filename));

    // Refs to track latest state for unmount saving
    const essayValueRef = React.useRef(essayAnswer);
    const isMarkedRef = React.useRef(isMarked);
    const originalEssayValue = question.essay_answer || '';

    React.useEffect(() => {
        setSelectedAnswerId(question?.timetable_answer_id);
        setEssayAnswer(question?.essay_answer || '');
        setIsMarked(question?.is_mark);
    }, [question?.id]);

    const handleAnswerSelect = (id) => {
        setSelectedAnswerId(id);
        onSave(id, isMarked, essayAnswer);
    };

    const handleEssayChange = (e) => {
        const val = e.target.value;
        setEssayAnswer(val);
        essayValueRef.current = val;
    };

    // Debounced save for essay + Save on Unmount
    React.useEffect(() => {
        if (question?.timetable_question?.type !== 'essay') return;

        const timeoutId = setTimeout(() => {
            if (essayAnswer !== originalEssayValue) {
                onSave(selectedAnswerId, isMarked, essayAnswer);
            }
        }, 1000); // Save after 1 second of no typing

        return () => {
            clearTimeout(timeoutId);
            // If the value changed and wasn't saved yet, save it now
            if (essayValueRef.current !== originalEssayValue) {
                onSave(selectedAnswerId, isMarkedRef.current, essayValueRef.current);
            }
        };
    }, [question?.id, essayAnswer]);

    const handleToggleMark = () => {
        const newMark = !isMarked;
        setIsMarked(newMark);
        isMarkedRef.current = newMark;
        onSave(selectedAnswerId, newMark, essayAnswer);
    };

    const handleNext = () => {
        if (question?.timetable_question?.type === 'essay' && essayAnswer !== originalEssayValue) {
            onSave(selectedAnswerId, isMarked, essayAnswer);
        }
        onNext();
    };

    const handlePrev = () => {
        if (question?.timetable_question?.type === 'essay' && essayAnswer !== originalEssayValue) {
            onSave(selectedAnswerId, isMarked, essayAnswer);
        }
        onPrev();
    };

    const alphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";

    return (
        <AnimatePresence mode="wait">
            <motion.div
                key={question.id}
                initial={{ opacity: 0, x: 20 }}
                animate={{ opacity: 1, x: 0 }}
                exit={{ opacity: 0, x: -20 }}
                transition={{ duration: 0.2 }}
                className="flex flex-col h-full bg-white/70 backdrop-blur-xl border border-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] relative overflow-hidden"
            >
                {/* Decorative blob */}
                <div className="absolute top-0 right-0 w-64 h-64 bg-orange-100 rounded-full blur-3xl opacity-50 pointer-events-none"></div>

                {/* Header Question */}
                <div className="p-4 lg:p-6 border-b border-gray-100 bg-white/50 flex items-center justify-between relative z-10">
                    <h2 className="text-xl lg:text-2xl font-black text-transparent bg-clip-text bg-gradient-to-r from-orange-600 to-amber-600">Soal No. {index + 1}</h2>
                    <button
                        onClick={handleToggleMark}
                        className={`flex items-center gap-2 px-5 py-2.5 rounded-xl border-2 transition-all duration-300 font-bold active:scale-95 ${isMarked
                            ? 'bg-amber-100 border-amber-400 text-amber-700 shadow-[0_4px_15px_rgba(251,191,36,0.2)]'
                            : 'bg-white border-gray-200 text-gray-500 hover:border-amber-300 hover:bg-amber-50 hover:text-amber-600'
                            }`}
                    >
                        <HelpCircle className={`w-5 h-5 ${isMarked ? 'fill-amber-500' : ''}`} />
                        <span>Ragu-Ragu? 🤔</span>
                    </button>
                </div>

                {/* Content Area */}
                <div className="flex-1 overflow-y-auto p-4 lg:p-10 custom-scrollbar">
                    <div className="max-w-full mx-auto space-y-8">
                        {/* Question Text */}
                        <div className="space-y-6">
                            <div className="prose prose-xl max-w-none text-gray-900 leading-relaxed font-medium text-justify">
                                <LatexHTML html={question?.timetable_question?.question || 'Soal tidak dapat dimuat.'} />
                            </div>
                            {question?.timetable_question?.description && (
                                <div className="prose prose-xl max-w-none text-gray-900 leading-relaxed font-medium text-justify">
                                    <LatexHTML
                                        className="text-orange-600/80 text-sm italic leading-snug font-medium prose-sm prose-orange"
                                        html={question?.timetable_question?.description}
                                    />
                                </div>
                            )}
                        </div>

                        {/* Question Images */}
                        {question?.timetable_question?.images && (
                            (() => {
                                const parseImages = JSON.parse(question?.timetable_question?.images || '[]');
                                if (parseImages.length === 0) return null;

                                return (
                                    <div className="flex flex-col gap-10 w-full py-4 text-left">
                                        {parseImages.map((img, i) => {
                                            const mediaUrl = `/storage/${img}`;
                                            if (isVideo(img)) {
                                                return (
                                                    <div key={i} className="w-full rounded-2xl overflow-hidden shadow-sm border border-gray-200 bg-black">
                                                        <video controls controlsList="nodownload" className="w-full h-auto max-h-[600px] mx-auto">
                                                            <source src={mediaUrl} type={`video/${getExtension(img)}`} />
                                                            Your browser does not support the video tag.
                                                        </video>
                                                    </div>
                                                );
                                            } else if (isAudio(img)) {
                                                return (
                                                    <div key={i} className="w-full rounded-xl overflow-hidden shadow-sm border border-gray-200 bg-gray-50 p-4">
                                                        <audio controls controlsList="nodownload" className="w-full">
                                                            <source src={mediaUrl} type={`audio/${getExtension(img)}`} />
                                                            Your browser does not support the audio tag.
                                                        </audio>
                                                    </div>
                                                );
                                            } else {
                                                return (
                                                    <div
                                                        key={i}
                                                        onClick={() => setViewedImage(mediaUrl)}
                                                        className="group relative rounded-2xl border-2 border-gray-100 overflow-hidden bg-gray-50 shadow-sm hover:shadow-xl transition-all duration-300 cursor-zoom-in"
                                                    >
                                                        <img
                                                            src={mediaUrl}
                                                            alt="Question visual"
                                                            className="w-full h-auto object-contain max-h-[600px] mx-auto mix-blend-multiply"
                                                        />
                                                        <div className="absolute inset-0 bg-gradient-to-t from-black/20 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity flex items-end justify-center p-6">
                                                            <div className="bg-white/90 backdrop-blur px-6 py-3 rounded-full flex items-center gap-3 text-sm font-bold text-gray-700 shadow-xl border border-white/50">
                                                                <ZoomIn className="w-5 h-5 text-orange-600" /> Lihat Gambar Penuh
                                                            </div>
                                                        </div>
                                                    </div>
                                                );
                                            }
                                        })}
                                    </div>
                                );
                            })()
                        )}

                        {/* Latex Preview */}
                        {question?.timetable_question?.latex_preview_png && (
                            <div className="flex justify-start -mt-4">
                                <div
                                    onClick={() => setViewedImage(`/storage/${question?.timetable_question?.latex_preview_png}`)}
                                    className="group relative p-6 bg-white rounded-2xl border-2 border-dashed border-gray-200 shadow-sm transition-all hover:border-orange-400 hover:shadow-xl cursor-zoom-in"
                                >
                                    <img
                                        src={`/storage/${question?.timetable_question?.latex_preview_png}`}
                                        alt="Equation"
                                        className="w-full h-auto object-contain mx-auto mix-blend-multiply"
                                    />
                                    <div className="absolute inset-x-0 bottom-3 opacity-0 group-hover:opacity-100 transition-opacity flex justify-center">
                                        <div className="bg-orange-600/90 backdrop-blur px-3 py-1 rounded-full flex items-center gap-2 text-[10px] font-bold text-white shadow-lg uppercase tracking-wider">
                                            <ZoomIn className="w-3 h-3" /> Lihat Gambar Penuh
                                        </div>
                                    </div>
                                </div>
                            </div>
                        )}

                        {/* Answers / Essay Input */}
                        <div className="pb-20">
                            {question?.timetable_question?.type === 'essay' ? (
                                <div className="space-y-4">
                                    <label className="block text-lg font-bold text-gray-700">Jawaban Anda:</label>
                                    <textarea
                                        value={essayAnswer}
                                        onChange={handleEssayChange}
                                        placeholder="Ketik jawaban Anda di sini..."
                                        className="w-full h-64 p-6 rounded-3xl border-2 border-gray-100 focus:border-orange-600 focus:ring-4 focus:ring-orange-100 transition-all text-lg font-medium resize-none shadow-sm"
                                    />
                                    <p className="text-sm text-gray-400 italic">
                                        Jawaban Anda akan disimpan secara otomatis saat Anda mengetik.
                                    </p>
                                </div>
                            ) : (
                                <div className="grid grid-cols-1 gap-4">
                                    {question?.timetable_question?.answers?.map((answer, i) => (
                                        <label
                                            key={answer.id}
                                            className={`group relative flex items-start gap-4 p-4 rounded-3xl border-2 cursor-pointer transition-all duration-300 ${selectedAnswerId === answer.id
                                                ? 'border-orange-500 bg-orange-50/50 shadow-[0_8px_30px_rgb(249,115,22,0.15)]'
                                                : 'border-white bg-white/60 backdrop-blur hover:border-orange-300 hover:shadow-xl hover:-translate-y-1'
                                                }`}
                                        >
                                            <input
                                                type="radio"
                                                name="answer"
                                                className="hidden"
                                                checked={selectedAnswerId === answer.id}
                                                onChange={() => handleAnswerSelect(answer.id)}
                                            />

                                            {/* Alphabet Circle */}
                                            <div className={`flex-none w-10 h-10 rounded-2xl flex items-center justify-center font-black text-lg transition-all duration-300 shadow-sm border-2 ${selectedAnswerId === answer.id
                                                ? 'bg-gradient-to-br from-orange-500 to-amber-500 border-transparent text-white rotate-12 scale-110 shadow-orange-300/50'
                                                : 'bg-white border-gray-200 text-gray-400 group-hover:border-orange-400 group-hover:text-orange-500'
                                                }`}>
                                                {alphabet[i]}
                                            </div>

                                            {/* Answer Content */}
                                            <div className="flex-1 space-y-3 pt-1">
                                                <LatexHTML
                                                    className="text-gray-800 text-base font-bold leading-relaxed"
                                                    html={answer.context}
                                                />

                                                {/* Answer Images */}
                                                {answer.images && JSON.parse(answer.images || '[]').length > 0 && (
                                                    <div className="flex flex-col gap-6 w-full">
                                                        {JSON.parse(answer.images).map((img, j) => {
                                                            const mediaUrl = `/storage/${img}`;
                                                            if (isVideo(img)) {
                                                                return (
                                                                    <div key={j} onClick={(e) => e.stopPropagation()} className="p-1.5 rounded-2xl border border-gray-100 bg-black w-full xl:w-3/4">
                                                                        <video controls controlsList="nodownload" className="w-full h-auto max-h-48">
                                                                            <source src={mediaUrl} type={`video/${getExtension(img)}`} />
                                                                        </video>
                                                                    </div>
                                                                );
                                                            } else if (isAudio(img)) {
                                                                return (
                                                                    <div key={j} onClick={(e) => e.stopPropagation()} className="p-1.5 rounded-xl border border-gray-100 bg-gray-50 w-full xl:w-3/4">
                                                                        <audio controls controlsList="nodownload" className="w-full">
                                                                            <source src={mediaUrl} type={`audio/${getExtension(img)}`} />
                                                                        </audio>
                                                                    </div>
                                                                );
                                                            } else {
                                                                return (
                                                                    <div
                                                                        key={j}
                                                                        onClick={(e) => {
                                                                            e.preventDefault();
                                                                            e.stopPropagation();
                                                                            setViewedImage(mediaUrl);
                                                                        }}
                                                                        className="p-1.5 rounded-2xl border border-gray-100 bg-gray-50 hover:bg-white transition-all cursor-zoom-in hover:shadow-xl w-full xl:w-3/4"
                                                                    >
                                                                        <img src={mediaUrl} className="rounded-lg max-h-48 w-auto object-contain" alt="Option visual" />
                                                                    </div>
                                                                );
                                                            }
                                                        })}
                                                    </div>
                                                )}

                                                {/* Answer Latex */}
                                                {answer.latex_preview_png && (
                                                    <div
                                                        onClick={(e) => {
                                                            e.preventDefault();
                                                            e.stopPropagation();
                                                            setViewedImage(`/storage/${answer.latex_preview_png}`);
                                                        }}
                                                        className="group inline-block p-4 bg-gray-50 rounded-xl border border-gray-100 hover:border-orange-300 hover:bg-white transition-all cursor-zoom-in relative"
                                                    >
                                                        <img src={`/storage/${answer.latex_preview_png}`} className="max-h-24 object-contain mix-blend-multiply" alt="Option equation" />
                                                        <div className="absolute top-1 right-1 opacity-0 group-hover:opacity-100 transition-opacity">
                                                            <ZoomIn className="w-4 h-4 text-orange-500" />
                                                        </div>
                                                    </div>
                                                )}
                                            </div>

                                            {/* Checkmark Indicator */}
                                            <div className={`flex-none w-8 h-8 rounded-full flex items-center justify-center transition-all duration-300 ${selectedAnswerId === answer.id ? 'opacity-100 scale-100' : 'opacity-0 scale-50'
                                                }`}>
                                                <CheckCircle className="w-8 h-8 fill-orange-500 text-white" />
                                            </div>
                                        </label>
                                    ))}
                                </div>
                            )}
                        </div>
                    </div>
                </div>

                {/* Footer Navigation */}
                <div className="p-4 lg:p-6 border-t border-gray-100 bg-white/50 backdrop-blur relative z-10 rounded-b-3xl">
                    {/* Lightbox Overlay */}
                    <AnimatePresence>
                        {viewedImage && (
                            <motion.div
                                initial={{ opacity: 0 }}
                                animate={{ opacity: 1 }}
                                exit={{ opacity: 0 }}
                                onClick={() => setViewedImage(null)}
                                className="fixed inset-0 z-[999] bg-black/90 backdrop-blur-sm flex items-center justify-center p-4 lg:p-20 cursor-zoom-out"
                            >
                                <motion.div
                                    initial={{ scale: 0.9, opacity: 0 }}
                                    animate={{ scale: 1, opacity: 1 }}
                                    exit={{ scale: 0.9, opacity: 0 }}
                                    className="relative max-w-full max-h-full"
                                >
                                    <img
                                        src={viewedImage}
                                        alt="Viewed image"
                                        className="max-w-full max-h-[90vh] rounded-xl shadow-2xl object-contain border-4 border-white/10"
                                    />
                                    <div className="absolute top-4 right-4 bg-white/20 hover:bg-white/40 backdrop-blur p-2 rounded-full transition-colors">
                                        <ChevronRight className="w-6 h-6 text-white rotate-45" />
                                    </div>
                                </motion.div>
                            </motion.div>
                        )}
                    </AnimatePresence>

                    <div className="max-w-full mx-auto flex items-center justify-between">
                        <button
                            onClick={handlePrev}
                            disabled={index === 0}
                            className={`flex items-center gap-2 px-6 py-3.5 rounded-2xl font-bold transition-all active:scale-95 ${index === 0 ? 'text-gray-400 bg-gray-100 cursor-not-allowed opacity-70' : 'text-slate-700 bg-white border-2 border-slate-200 hover:border-orange-300 hover:text-orange-600 hover:shadow-lg'
                                }`}
                        >
                            <ChevronLeft className="w-5 h-5" /> Sebelumnya
                        </button>

                        <div className="text-sm font-black text-orange-500/50 hidden sm:block bg-orange-50 px-4 py-2 rounded-xl">
                            {index + 1} / {total}
                        </div>

                        {index === total - 1 ? (
                            <button
                                onClick={onFinish}
                                className="flex items-center gap-2 px-8 py-3.5 rounded-2xl font-black text-white transition-all bg-gradient-to-r from-emerald-400 to-teal-500 hover:from-emerald-500 hover:to-teal-600 shadow-[0_8px_20px_rgba(16,185,129,0.3)] hover:-translate-y-1 active:scale-95"
                            >
                                <CheckCircle className="w-5 h-5" /> Selesai! 🎉
                            </button>
                        ) : (
                            <button
                                onClick={handleNext}
                                className="flex items-center gap-2 px-8 py-3.5 rounded-2xl font-black text-white transition-all bg-gradient-to-r from-orange-500 to-amber-500 hover:from-orange-600 hover:to-amber-600 shadow-[0_8px_20px_rgba(249,115,22,0.3)] hover:-translate-y-1 active:scale-95"
                            >
                                Selanjutnya <ChevronRight className="w-5 h-5" />
                            </button>
                        )}
                    </div>
                </div>
            </motion.div>
        </AnimatePresence>
    );
};

export default QuestionArea;
