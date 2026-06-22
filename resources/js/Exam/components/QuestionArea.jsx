import React, { useState, useEffect, useRef } from 'react';
import {
    ChevronLeft, ChevronRight, CheckCircle,
    HelpCircle, Bookmark, Menu, Save
} from 'lucide-react';
import LatexHTML from './LatexHTML';

const ALPHA = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

const getMediaUrl = (path) => {
    if (!path) return '';
    if (path.startsWith('http://') || path.startsWith('https://')) return path;
    const cleanPath = path.replace(/^\/?storage\/?/, '').replace(/^\//, '');
    return `/storage/${cleanPath}`;
};

/* ─── Save Status Badge ─── */
const SaveBadge = ({ saveStatus, lastSaved, companyColor }) => {
    const isSync = saveStatus === 'syncing';
    const cfg = {
        saving:  { dot: 'bg-amber-400 animate-ping',  txt: 'Menyimpan...',   cls: 'text-amber-600' },
        syncing: { dot: '',                           txt: 'Sinkronisasi...', cls: '' },
        saved:   { dot: 'bg-green-500',               txt: 'Tersimpan',       cls: 'text-green-600' },
        error:   { dot: 'bg-red-500 animate-bounce',  txt: 'Gagal Simpan',    cls: 'text-red-600' },
    }[saveStatus] || { dot: 'bg-green-500', txt: 'Tersimpan', cls: 'text-green-600' };

    return (
        <div className="flex items-center gap-1.5 px-2 py-1 bg-white border border-gray-200 rounded-full text-[11px] font-semibold shadow-sm whitespace-nowrap">
            <span className="relative flex h-2 w-2 flex-none">
                <span 
                    className={`absolute inline-flex h-full w-full rounded-full opacity-75 ${isSync ? 'animate-ping' : cfg.dot}`} 
                    style={isSync ? { backgroundColor: companyColor } : {}}
                />
                <span 
                    className={`relative inline-flex rounded-full h-2 w-2 ${isSync ? '' : cfg.dot.split(' ')[0]}`} 
                    style={isSync ? { backgroundColor: companyColor } : {}}
                />
            </span>
            <span className={isSync ? '' : cfg.cls} style={isSync ? { color: companyColor } : {}}>{cfg.txt}</span>
            {saveStatus === 'saved' && lastSaved && (
                <span className="text-gray-400 font-mono text-[10px] hidden sm:inline">{lastSaved}</span>
            )}
        </div>
    );
};

/* ─── Main Component ─── */
const QuestionArea = ({
    question, index, total, onSave, onNext, onPrev, onFinish,
    setCurrentIndex, saveStatus, lastSaved, navigation = [], companyColor = '#1e3a5f'
}) => {
    const [selectedAnswerId, setSelectedAnswerId] = useState(question?.timetable_answer_id ?? null);
    const [essayAnswer, setEssayAnswer]           = useState(question?.essay_answer || '');
    const [isMarked, setIsMarked]                 = useState(!!question?.is_mark);

    const [fontSize, setFontSize] = useState(() => {
        return localStorage.getItem('exam_font_size') || 'medium';
    });

    const changeFontSize = (size) => {
        setFontSize(size);
        localStorage.setItem('exam_font_size', size);
    };

    const fontSizes = {
        small: {
            question: 'text-xs sm:text-sm leading-relaxed text-gray-800 font-medium',
            description: 'text-xs text-gray-600 bg-gray-50 rounded-xl p-3 border border-gray-100',
            answers: 'flex-1 text-xs text-gray-800 leading-relaxed pt-0.5 space-y-2',
            textarea: 'w-full p-3 border-2 border-gray-200 rounded-xl text-xs text-gray-800 resize-none focus:outline-none transition-colors'
        },
        medium: {
            question: 'text-sm sm:text-base leading-relaxed text-gray-800 font-medium',
            description: 'text-sm text-gray-600 bg-gray-50 rounded-xl p-3 border border-gray-100',
            answers: 'flex-1 text-sm text-gray-800 leading-relaxed pt-0.5 space-y-2',
            textarea: 'w-full p-3 border-2 border-gray-200 rounded-xl text-sm text-gray-800 resize-none focus:outline-none transition-colors'
        },
        large: {
            question: 'text-base sm:text-lg leading-relaxed text-gray-800 font-medium',
            description: 'text-base text-gray-600 bg-gray-50 rounded-xl p-3 border border-gray-100',
            answers: 'flex-1 text-base text-gray-800 leading-relaxed pt-0.5 space-y-2',
            textarea: 'w-full p-3 border-2 border-gray-200 rounded-xl text-base text-gray-800 resize-none focus:outline-none transition-colors'
        }
    };

    const essayRef    = useRef(essayAnswer);
    const markedRef   = useRef(isMarked);
    const origEssay   = question?.essay_answer || '';

    /* sync when question changes */
    useEffect(() => {
        setSelectedAnswerId(question?.timetable_answer_id ?? null);
        setEssayAnswer(question?.essay_answer || '');
        setIsMarked(!!question?.is_mark);
        essayRef.current  = question?.essay_answer || '';
        markedRef.current = !!question?.is_mark;
    }, [question?.id]);

    /* debounced essay save */
    useEffect(() => {
        if (question?.timetable_question?.type !== 'essay') return;
        const t = setTimeout(() => {
            if (essayRef.current !== origEssay) onSave(selectedAnswerId, markedRef.current, essayRef.current);
        }, 1000);
        return () => {
            clearTimeout(t);
            if (essayRef.current !== origEssay) onSave(selectedAnswerId, markedRef.current, essayRef.current);
        };
    }, [question?.id, essayAnswer]);

    const handleAnswerSelect = (id) => {
        setSelectedAnswerId(id);
        onSave(id, isMarked, essayAnswer);
    };

    const handleToggleMark = () => {
        const next = !isMarked;
        setIsMarked(next);
        markedRef.current = next;
        onSave(selectedAnswerId, next, essayAnswer);
    };

    const handleEssayChange = (e) => {
        const v = e.target.value;
        setEssayAnswer(v);
        essayRef.current = v;
    };

    const saveBeforeNav = () => {
        if (question?.timetable_question?.type === 'essay' && essayRef.current !== origEssay) {
            onSave(selectedAnswerId, markedRef.current, essayRef.current);
        }
    };

    /* ── Data ── */
    // API returns: question.timetable_question.answers[].context
    const questionType = question?.timetable_question?.type;
    const answers      = question?.timetable_question?.answers
                      ?? question?.timetable_question?.timetable_answers
                      ?? [];

    const answeredCount = navigation.filter(n => n.isAnswered).length;

    /* ── Page strip ── */
    const RANGE = 2;
    const pageNums = [];
    for (let i = Math.max(0, index - RANGE); i <= Math.min(total - 1, index + RANGE); i++) {
        pageNums.push(i);
    }
    const showFirstEllipsis = index > RANGE + 1;
    const showLastEllipsis  = index < total - RANGE - 2;

    return (
        <div className="flex flex-col h-full overflow-hidden bg-white">

            {/* ── Top Bar ── */}
            <div className="flex-none flex flex-wrap items-center gap-1.5 px-3 py-2 bg-white border-b border-gray-200 shadow-sm z-10">
                {/* Nav label */}
                <div className="hidden sm:flex items-center gap-1 text-xs font-semibold text-gray-400 pr-2 border-r border-gray-200 mr-1">
                    <Menu className="w-3.5 h-3.5" />
                    <span>Navigasi Soal</span>
                </div>

                {/* Question # */}
                <span className="text-xs font-bold text-gray-700 mr-2">Soal {index + 1} dari {total}</span>

                {/* Font Size Selector */}
                <div className="flex items-center gap-1 bg-gray-100 rounded-lg p-1 border border-gray-200">
                    <button
                        onClick={() => changeFontSize('small')}
                        className={`px-2 py-1 rounded text-[10px] font-bold transition-all ${
                            fontSize === 'small' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-800'
                        }`}
                        title="Ukuran Huruf Kecil"
                    >
                        Kecil
                    </button>
                    <button
                        onClick={() => changeFontSize('medium')}
                        className={`px-2 py-1 rounded text-[10px] font-bold transition-all ${
                            fontSize === 'medium' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-800'
                        }`}
                        title="Ukuran Huruf Sedang"
                    >
                        Sedang
                    </button>
                    <button
                        onClick={() => changeFontSize('large')}
                        className={`px-2 py-1 rounded text-[10px] font-bold transition-all ${
                            fontSize === 'large' ? 'bg-white text-gray-800 shadow-sm' : 'text-gray-500 hover:text-gray-800'
                        }`}
                        title="Ukuran Huruf Besar"
                    >
                        Besar
                    </button>
                </div>

                <div className="flex-1" />

                {/* Ragu-Ragu */}
                <button
                    onClick={handleToggleMark}
                    className="flex items-center gap-1.5 px-3 py-1.5 rounded-lg border text-xs font-semibold transition-all shadow-sm active:scale-[0.98]"
                    style={isMarked
                        ? { backgroundColor: '#fef9c3', borderColor: '#f59e0b', color: '#92400e' }
                        : { backgroundColor: '#f8fafc', borderColor: '#e2e8f0', color: '#64748b' }
                    }
                >
                    <HelpCircle className="w-3.5 h-3.5" />
                    <span>Ragu-Ragu</span>
                </button>

                {/* Save badge */}
                <SaveBadge saveStatus={saveStatus} lastSaved={lastSaved} companyColor={companyColor} />
            </div>

            {/* ── Question Body (scrollable) ── */}
            <div className="flex-1 overflow-y-auto">
                <div className="max-w-3xl mx-auto px-4 py-5 space-y-5">

                    {/* Question label */}
                    <p className="text-xs font-semibold text-gray-400">Soal ke-{index + 1}:</p>

                    {/* Question text */}
                    <div className={fontSizes[fontSize].question}>
                        <LatexHTML html={question?.timetable_question?.question || 'Soal tidak dapat dimuat.'} />
                    </div>

                    {/* Question description / text content */}
                    {question?.timetable_question?.description && (
                        <div className={fontSizes[fontSize].description}>
                            <LatexHTML html={question.timetable_question.description} />
                        </div>
                    )}

                    {/* Media files (images, audio, video) */}
                    {(() => {
                        let mediaFiles = [];
                        const imagesVal = question?.timetable_question?.images;
                        if (imagesVal) {
                            if (Array.isArray(imagesVal)) {
                                mediaFiles = imagesVal;
                            } else if (typeof imagesVal === 'string') {
                                try {
                                    mediaFiles = JSON.parse(imagesVal);
                                } catch (e) {
                                    if (imagesVal.trim().startsWith('[')) {
                                        mediaFiles = [];
                                    } else {
                                        mediaFiles = [imagesVal];
                                    }
                                }
                            }
                        }

                        if (mediaFiles.length > 0) {
                            return (
                                <div className="mt-4 space-y-4">
                                    {mediaFiles.map((file, idx) => {
                                        const url = getMediaUrl(file);
                                        const isVideo = /\.(mp4|mov|avi|wmv|webm)$/i.test(file);
                                        const isAudio = /\.(mp3|wav|ogg|m4a)$/i.test(file);
                                        const isPdf = /\.pdf$/i.test(file);
                                        const isDoc = /\.(docx?|xlsx?|txt|zip|rar)$/i.test(file);

                                        if (isVideo) {
                                            return (
                                                <div key={idx} className="rounded-xl overflow-hidden border border-gray-200 bg-slate-900 shadow-sm max-w-xl mx-auto">
                                                    <video src={url} className="w-full max-h-[360px] object-contain" controls />
                                                </div>
                                            );
                                        } else if (isAudio) {
                                            return (
                                                <div key={idx} className="p-4 rounded-xl border border-gray-200 bg-slate-50 shadow-sm max-w-md mx-auto flex flex-col gap-2">
                                                    <span className="text-xs text-gray-500 font-semibold truncate flex items-center gap-1.5">
                                                        🎵 Audio Lampiran {mediaFiles.length > 1 ? `#${idx + 1}` : ''}
                                                    </span>
                                                    <audio src={url} className="w-full focus:outline-none" controls />
                                                </div>
                                            );
                                        } else if (isPdf) {
                                            return (
                                                <div key={idx} className="p-4 rounded-xl border border-gray-200 bg-red-50 shadow-sm max-w-md mx-auto flex flex-col gap-2 items-center text-center">
                                                    <span className="text-red-500 font-semibold flex items-center gap-1">📄 PDF Lampiran {mediaFiles.length > 1 ? `#${idx + 1}` : ''}</span>
                                                    <a href={url} target="_blank" rel="noopener noreferrer" className="text-xs text-blue-600 underline font-medium hover:text-blue-800 break-all">
                                                        Lihat / Unduh Lampiran PDF
                                                    </a>
                                                </div>
                                            );
                                        } else if (isDoc) {
                                            return (
                                                <div key={idx} className="p-4 rounded-xl border border-gray-200 bg-blue-50 shadow-sm max-w-md mx-auto flex flex-col gap-2 items-center text-center">
                                                    <span className="text-blue-500 font-semibold flex items-center gap-1">📁 Dokumen Lampiran {mediaFiles.length > 1 ? `#${idx + 1}` : ''}</span>
                                                    <a href={url} target="_blank" rel="noopener noreferrer" className="text-xs text-blue-600 underline font-medium hover:text-blue-800 break-all">
                                                        Unduh Lampiran Dokumen
                                                    </a>
                                                </div>
                                            );
                                        } else {
                                            return (
                                                <div key={idx} className="rounded-xl overflow-hidden border border-gray-200 bg-white shadow-sm inline-block max-w-full hover:shadow-md transition-shadow">
                                                    <img src={url} alt={`Lampiran Soal ${idx + 1}`} className="max-h-[300px] object-contain cursor-zoom-in" onClick={() => window.open(url, '_blank')} />
                                                </div>
                                            );
                                        }
                                    })}
                                </div>
                            );
                        }
                        return null;
                    })()}

                    {/* ── Multiple Choice Answers ── */}
                    {questionType !== 'essay' && answers.length > 0 && (
                        <div className="space-y-2.5 pt-1">
                            {answers.map((answer, i) => {
                                const isSelected = selectedAnswerId === answer.id;
                                // answer text lives in `context` field
                                const answerText = answer.context ?? answer.answer ?? '';
                                return (
                                    <button
                                        key={answer.id}
                                        onClick={() => handleAnswerSelect(answer.id)}
                                        className="w-full flex items-start gap-3 p-3 sm:p-3.5 rounded-xl border-2 text-left transition-all duration-150 hover:shadow-sm active:scale-[0.99]"
                                        style={isSelected
                                            ? { borderColor: companyColor, backgroundColor: `${companyColor}0f` }
                                            : { borderColor: '#e5e7eb', backgroundColor: '#fff' }
                                        }
                                    >
                                        {/* Letter circle */}
                                        <div
                                            className="flex-none w-7 h-7 sm:w-8 sm:h-8 rounded-full flex items-center justify-center text-xs sm:text-sm font-black transition-all"
                                            style={isSelected
                                                ? { backgroundColor: companyColor, color: '#fff' }
                                                : { backgroundColor: '#f1f5f9', color: '#64748b' }
                                            }
                                        >
                                            {ALPHA[i]}
                                        </div>
                                        {/* Answer text & media */}
                                        <div className={fontSizes[fontSize].answers}>
                                            <LatexHTML html={answerText} />
                                            {/* Answer images/media */}
                                            {(() => {
                                                let answerMedia = [];
                                                const ansImages = answer.images;
                                                if (ansImages) {
                                                    if (Array.isArray(ansImages)) {
                                                        answerMedia = ansImages;
                                                    } else if (typeof ansImages === 'string') {
                                                        try {
                                                            answerMedia = JSON.parse(ansImages);
                                                        } catch (e) {
                                                            answerMedia = [ansImages];
                                                        }
                                                    }
                                                }
                                                if (answerMedia.length > 0) {
                                                    return (
                                                        <div className="grid grid-cols-2 sm:grid-cols-3 gap-2 mt-2">
                                                            {answerMedia.map((file, aIdx) => {
                                                                const aUrl = getMediaUrl(file);
                                                                return (
                                                                    <div key={aIdx} className="rounded-lg overflow-hidden border border-gray-200 bg-gray-50 aspect-video flex items-center justify-center hover:opacity-90 transition-opacity">
                                                                        <img src={aUrl} alt="Gambar Jawaban" className="max-h-full max-w-full object-contain cursor-pointer" onClick={(e) => { e.stopPropagation(); window.open(aUrl, '_blank'); }} />
                                                                    </div>
                                                                );
                                                            })}
                                                        </div>
                                                    );
                                                }
                                                return null;
                                            })()}
                                        </div>
                                        {/* Check icon */}
                                        {isSelected && (
                                            <CheckCircle
                                                className="flex-none w-4 h-4 mt-1"
                                                style={{ color: companyColor }}
                                            />
                                        )}
                                    </button>
                                );
                            })}
                        </div>
                    )}

                    {/* ── Essay ── */}
                    {questionType === 'essay' && (
                        <div className="space-y-2">
                            <label className="text-xs font-semibold text-gray-500">Jawaban Anda:</label>
                            <textarea
                                value={essayAnswer}
                                onChange={handleEssayChange}
                                rows={7}
                                placeholder="Ketik jawaban Anda di sini..."
                                className={fontSizes[fontSize].textarea}
                                style={{ '--tw-ring-color': companyColor }}
                                onFocus={e => e.target.style.borderColor = companyColor}
                                onBlur={e => e.target.style.borderColor = '#e5e7eb'}
                            />
                        </div>
                    )}

                    {/* No answers placeholder */}
                    {questionType !== 'essay' && answers.length === 0 && (
                        <div className="py-6 text-center text-sm text-gray-400 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                            Pilihan jawaban tidak tersedia
                        </div>
                    )}
                </div>
            </div>

            {/* ── Bottom Navigation ── */}
            <div className="flex-none border-t border-gray-200 bg-white px-3 py-2.5 flex items-center gap-2 flex-wrap sm:flex-nowrap">
                {/* Prev */}
                <button
                    onClick={() => { saveBeforeNav(); onPrev(); }}
                    disabled={index === 0}
                    className="flex items-center gap-1 px-3 py-2 rounded-lg border border-gray-200 text-xs font-bold text-gray-600 hover:bg-gray-50 disabled:opacity-40 disabled:cursor-not-allowed transition-all whitespace-nowrap"
                >
                    <ChevronLeft className="w-3.5 h-3.5" />
                    <span>Sebelumnya</span>
                </button>

                {/* Page strip */}
                <div className="flex-1 flex items-center justify-center gap-1 overflow-hidden">
                    {/* First page */}
                    {showFirstEllipsis && <>
                        <button onClick={() => { saveBeforeNav(); setCurrentIndex(0); }} className="w-7 h-7 rounded-md text-xs font-bold text-gray-500 hover:bg-gray-100">1</button>
                        <span className="text-gray-300 text-xs px-0.5">…</span>
                    </>}

                    {pageNums.map(n => (
                        <button
                            key={n}
                            onClick={() => { saveBeforeNav(); setCurrentIndex(n); }}
                            className="w-7 h-7 rounded-md text-xs font-bold transition-all hover:opacity-80"
                            style={n === index
                                ? { backgroundColor: '#1e3a5f', color: '#fff' }
                                : { backgroundColor: '#f1f5f9', color: '#475569' }
                            }
                        >
                            {n + 1}
                        </button>
                    ))}

                    {/* Last page */}
                    {showLastEllipsis && <>
                        <span className="text-gray-300 text-xs px-0.5">…</span>
                        <button onClick={() => { saveBeforeNav(); setCurrentIndex(total - 1); }} className="w-7 h-7 rounded-md text-xs font-bold text-gray-500 hover:bg-gray-100">{total}</button>
                    </>}
                </div>

                {/* Next / Finish */}
                {index < total - 1 ? (
                    <button
                        onClick={() => { saveBeforeNav(); onNext(); }}
                        className="flex items-center gap-1 px-4 py-2 rounded-lg text-xs font-bold text-white transition-all whitespace-nowrap"
                        style={{ backgroundColor: companyColor }}
                    >
                        <span>Selanjutnya</span>
                        <ChevronRight className="w-3.5 h-3.5" />
                    </button>
                ) : (
                    <button
                        onClick={() => onFinish(false)}
                        className="flex items-center gap-1 px-4 py-2 rounded-lg text-xs font-bold text-white bg-green-600 hover:bg-green-700 transition-all whitespace-nowrap"
                    >
                        <CheckCircle className="w-3.5 h-3.5" />
                        <span>Selesai</span>
                    </button>
                )}
            </div>
        </div>
    );
};

export default QuestionArea;
