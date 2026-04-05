import React, { useState, useEffect } from 'react';
import { 
  ChevronLeft, ChevronRight, CheckCircle, 
  HelpCircle, Image as ImageIcon, ZoomIn 
} from 'lucide-react';
import { motion, AnimatePresence } from 'framer-motion';

const QuestionArea = ({ question, index, total, onSave, onNext, onPrev, onFinish }) => {
    const [selectedAnswerId, setSelectedAnswerId] = useState(question.timetable_answer_id);
    const [isMarked, setIsMarked] = useState(question.is_mark);

    useEffect(() => {
        setSelectedAnswerId(question.timetable_answer_id);
        setIsMarked(question.is_mark);
    }, [question]);

    const handleAnswerSelect = (id) => {
        setSelectedAnswerId(id);
        onSave(id, isMarked);
    };

    const handleToggleMark = () => {
        const newMark = !isMarked;
        setIsMarked(newMark);
        onSave(selectedAnswerId, newMark);
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
                className="flex flex-col h-full bg-white relative"
            >
                {/* Header Question */}
                <div className="p-4 lg:p-6 border-b bg-gray-50 flex items-center justify-between relative">
                    <h2 className="text-xl font-bold text-gray-800">Soal No. {index + 1}</h2>
                    <button 
                        onClick={handleToggleMark}
                        className={`flex items-center gap-2 px-4 py-2 rounded-lg border transition-all ${
                            isMarked 
                            ? 'bg-yellow-50 border-yellow-400 text-yellow-700 shadow-sm' 
                            : 'bg-white border-gray-300 text-gray-600 hover:bg-gray-50'
                        }`}
                    >
                        <HelpCircle className={`w-5 h-5 ${isMarked ? 'fill-yellow-500' : ''}`} />
                        <span className="font-medium">Ragu-Ragu</span>
                    </button>
                </div>

                {/* Content Area */}
                <div className="flex-1 overflow-y-auto p-4 lg:p-10 custom-scrollbar">
                    <div className="max-w-full mx-auto space-y-8">
                        {/* Question Text */}
                        <div className="prose prose-lg max-w-none text-gray-800 leading-relaxed">
                            <p dangerouslySetInnerHTML={{ __html: question.timetable_question.question }} />
                            {question.timetable_question.description && (
                                <p className="text-gray-500 text-sm italic mt-2">{question.timetable_question.description}</p>
                            )}
                        </div>

                        {/* Question Images */}
                        {question.timetable_question.images && JSON.parse(question.timetable_question.images || '[]').length > 0 && (
                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                {JSON.parse(question.timetable_question.images).map((img, i) => (
                                    <div key={i} className="group relative rounded-xl border border-gray-200 overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                                        <img src={`/storage/${img}`} alt="Question visual" className="w-full h-auto object-contain max-h-80" />
                                        <div className="absolute inset-0 bg-black/0 group-hover:bg-black/5 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all pointer-events-none">
                                            <ZoomIn className="text-white w-8 h-8" />
                                        </div>
                                    </div>
                                ))}
                            </div>
                        )}

                        {/* Latex Preview */}
                        {question.timetable_question.latex_preview_png && (
                            <div className="p-4 bg-gray-50 rounded-xl border border-dashed border-gray-300 inline-block">
                                <img src={`/storage/${question.timetable_question.latex_preview_png}`} alt="Equation" className="max-h-40" />
                            </div>
                        )}

                        {/* Answers List */}
                        <div className="space-y-4 pb-12">
                            {question.timetable_question.answers.map((answer, i) => (
                                <label 
                                    key={answer.id}
                                    className={`relative flex items-start gap-4 p-5 rounded-2xl border-2 cursor-pointer transition-all hover:translate-x-1 ${
                                        selectedAnswerId === answer.id 
                                        ? 'border-blue-500 bg-blue-50/50 shadow-md ring-1 ring-blue-500' 
                                        : 'border-gray-200 bg-white hover:border-gray-300 hover:bg-gray-50'
                                    }`}
                                >
                                    <input 
                                        type="radio" 
                                        name="answer" 
                                        className="hidden"
                                        checked={selectedAnswerId === answer.id}
                                        onChange={() => handleAnswerSelect(answer.id)}
                                    />
                                    <div className={`flex-none w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm border-2 transition-colors ${
                                        selectedAnswerId === answer.id ? 'bg-blue-600 border-blue-600 text-white' : 'bg-white border-gray-300 text-gray-500'
                                    }`}>
                                        {alphabet[i]}
                                    </div>
                                    <div className="flex-1 space-y-3 pt-0.5">
                                        <div className="text-gray-800 font-medium leading-relaxed" dangerouslySetInnerHTML={{ __html: answer.context }} />
                                        
                                        {/* Answer Images */}
                                        {answer.images && JSON.parse(answer.images || '[]').length > 0 && (
                                            <div className="grid grid-cols-2 lg:grid-cols-3 gap-2">
                                                {JSON.parse(answer.images).map((img, j) => (
                                                    <img key={j} src={`/storage/${img}`} className="rounded-lg border bg-white max-h-32 object-contain" alt="Option visual" />
                                                ))}
                                            </div>
                                        )}
                                        
                                        {/* Answer Latex */}
                                        {answer.latex_preview_png && (
                                            <img src={`/storage/${answer.latex_preview_png}`} className="max-h-20 bg-white rounded p-1 border border-gray-100" alt="Option equation" />
                                        )}
                                    </div>
                                    {selectedAnswerId === answer.id && (
                                        <div className="flex-none text-blue-600 self-center">
                                            <CheckCircle className="w-6 h-6 fill-blue-600 text-white" />
                                        </div>
                                    )}
                                </label>
                            ))}
                        </div>
                    </div>
                </div>

                {/* Footer Navigation */}
                <div className="p-4 lg:p-6 border-t bg-white relative">
                    <div className="max-w-full mx-auto flex items-center justify-between">
                        <button 
                            onClick={onPrev}
                            disabled={index === 0}
                            className={`flex items-center gap-2 px-6 py-3 rounded-xl font-bold transition-all ${
                                index === 0 ? 'text-gray-300 cursor-not-allowed' : 'text-gray-700 bg-gray-100 hover:bg-gray-200'
                            }`}
                        >
                            <ChevronLeft className="w-5 h-5" /> Sebelumnya
                        </button>

                        <div className="text-sm font-bold text-gray-500 hidden sm:block">
                            Soal {index + 1} dari {total}
                        </div>

                        {index === total - 1 ? (
                            <button 
                                onClick={onFinish}
                                className="flex items-center gap-2 px-8 py-3 rounded-xl font-bold text-white transition-all bg-green-600 hover:bg-green-700 shadow-lg shadow-green-200"
                            >
                                <CheckCircle className="w-5 h-5" /> Akhiri Ujian
                            </button>
                        ) : (
                            <button 
                                onClick={onNext}
                                className="flex items-center gap-2 px-8 py-3 rounded-xl font-bold text-white transition-all bg-blue-600 hover:bg-blue-700 shadow-lg shadow-blue-200"
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
