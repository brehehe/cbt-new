import React, { useState, useEffect, useRef } from 'react';
import { 
  ChevronLeft, ChevronRight, CheckCircle, HelpCircle, 
  Search, BookOpen, AlertTriangle, Clock, RefreshCw, 
  Menu, X, Check, Filter, Shield, Settings, Key, 
  AlertCircle, ArrowRight, ArrowLeft, Bookmark
} from 'lucide-react';
import { motion, AnimatePresence } from 'framer-motion';

// Pre-defined university themes
const UNIVERSITY_THEMES = [
  { name: 'Default Blue', primary: '#2563eb', hover: '#1d4ed8', light: '#eff6ff', accent: 'blue' },
  { name: 'Univ. Indonesia (UI)', primary: '#eab308', hover: '#ca8a04', light: '#fef9c3', accent: 'yellow' },
  { name: 'Univ. Gadjah Mada (UGM)', primary: '#1e3a8a', hover: '#172554', light: '#dbeafe', accent: 'navy' },
  { name: 'Univ. Airlangga (UNAIR)', primary: '#0ea5e9', hover: '#0369a1', light: '#e0f2fe', accent: 'sky' },
  { name: 'Univ. Diponegoro (UNDIP)', primary: '#0f172a', hover: '#020617', light: '#f1f5f9', accent: 'slate' },
  { name: 'Univ. Padjadjaran (UNPAD)', primary: '#f97316', hover: '#ea580c', light: '#ffedd5', accent: 'orange' },
];

export default function QuestionNavDemo() {
  // Theme Color State
  const [selectedTheme, setSelectedTheme] = useState(UNIVERSITY_THEMES[0]);
  const [customPrimaryColor, setCustomPrimaryColor] = useState('#2563eb');
  const [isThemeOpen, setIsThemeOpen] = useState(false);

  // CBT State
  const [questions, setQuestions] = useState([]);
  const [currentIdx, setCurrentIdx] = useState(0);
  const [searchQuery, setSearchQuery] = useState('');
  const [activeFilter, setActiveFilter] = useState('all'); // all, answered, unanswered, flagged, bookmarked
  const [isSubmitModalOpen, setIsSubmitModalOpen] = useState(false);
  const [isReviewMode, setIsReviewMode] = useState(false);
  const [isRightSidebarOpen, setIsRightSidebarOpen] = useState(true); // Tablet/Desktop toggle
  const [isMobileDrawerOpen, setIsMobileDrawerOpen] = useState(false); // Mobile bottom drawer
  
  // Timer & Auto-Save
  const [timeLeft, setTimeLeft] = useState(7200); // 2 hours in seconds
  const [saveStatus, setSaveStatus] = useState('Saved'); // Saved, Saving..., Syncing...
  const [lastSavedTime, setLastSavedTime] = useState('');
  const autoSaveTimeoutRef = useRef(null);

  // Initialize questions
  useEffect(() => {
    // Generate 60 mock questions
    const generated = Array.from({ length: 60 }, (_, i) => {
      const id = i + 1;
      let section = 'A';
      if (id > 20 && id <= 40) section = 'B';
      else if (id > 40) section = 'C';

      let type = 'mcq';
      let text = '';
      let options = [];
      
      // Determine question type & content
      if (section === 'A') {
        type = 'mcq';
        text = `Di bawah ini yang merupakan struktur data yang menggunakan prinsip FIFO (First In First Out) adalah... (Soal Section A - No. ${id})`;
        options = [
          { id: 'a', text: 'Stack (Tumpukan)' },
          { id: 'b', text: 'Queue (Antrean)' },
          { id: 'c', text: 'Tree (Pohon)' },
          { id: 'd', text: 'Graph (Graf)' }
        ];
      } else if (section === 'B') {
        type = 'mcq';
        text = `Identify the correct usage of conditional formatting or structural markers in database integrity schema. (Section B - English Comprehension - No. ${id})`;
        options = [
          { id: 'a', text: 'Foreign key constraint definitions' },
          { id: 'b', text: 'Dynamic trigger procedures on update cascade' },
          { id: 'c', text: 'Index optimization configurations' },
          { id: 'd', text: 'Normal form level 3 schemas' }
        ];
      } else {
        type = id % 2 === 0 ? 'essay' : 'mcq';
        if (type === 'mcq') {
          text = `Selesaikan persamaan integral berikut untuk mencari nilai konstan C dari limit fungsi: $$\\int_{0}^{2} (3x^2 - 2x + 5) \\, dx$$ (Section C - Matematika - No. ${id})`;
          options = [
            { id: 'a', text: '12' },
            { id: 'b', text: '14' },
            { id: 'c', text: '16' },
            { id: 'd', text: '18' }
          ];
        } else {
          text = `Jelaskan secara detail perbedaan mendasar antara enkripsi simetris (Symmetric Encryption) dengan enkripsi asimetris (Asymmetric Encryption) dalam konteks keamanan Computer Based Test (CBT)! (Section C - Essay - No. ${id})`;
          options = [];
        }
      }

      // Pre-seed some states to make the dashboard look realistically populated on load
      let answer = '';
      let visited = false;
      let flagged = false;
      let status = 'unvisited'; // unvisited, visited, answered, flagged, unanswered

      if (id === 1) {
        visited = true;
        status = 'visited'; // Starting question
      } else if (id % 7 === 0) {
        answer = 'b';
        visited = true;
        status = 'answered';
      } else if (id % 9 === 0) {
        visited = true;
        flagged = true;
        status = 'flagged';
      } else if (id % 13 === 0) {
        visited = true;
        status = 'unanswered'; // Skipped
      }

      return {
        id,
        section,
        type,
        text,
        options,
        answer,
        visited,
        flagged,
        status
      };
    });

    setQuestions(generated);

    // Initial save timestamp
    const now = new Date();
    setLastSavedTime(now.toTimeString().split(' ')[0]);
  }, []);

  // Update dynamic primary colors in document root
  useEffect(() => {
    const root = document.documentElement;
    root.style.setProperty('--primary-color', customPrimaryColor);
    
    // Simple helper to calculate hover color (darker version of custom primary)
    // For hex values we can generate slightly darker one, or just use css opacity layers.
    root.style.setProperty('--primary-hover', customPrimaryColor + 'e6'); // 90% opacity
    root.style.setProperty('--primary-light', customPrimaryColor + '1a'); // 10% opacity
  }, [customPrimaryColor]);

  // Handle University Theme Selection
  const handleThemeSelect = (theme) => {
    setSelectedTheme(theme);
    setCustomPrimaryColor(theme.primary);
  };

  // Timer countdown
  useEffect(() => {
    const timer = setInterval(() => {
      setTimeLeft((prev) => {
        if (prev <= 1) {
          clearInterval(timer);
          triggerAutoSubmit();
          return 0;
        }
        return prev - 1;
      });
    }, 1000);
    return () => clearInterval(timer);
  }, []);

  // Format Time
  const formatTime = (seconds) => {
    const h = Math.floor(seconds / 3600);
    const m = Math.floor((seconds % 3600) / 60);
    const s = seconds % 60;
    return `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;
  };

  // Trigger auto save effect
  const triggerAutoSave = () => {
    setSaveStatus('Saving...');
    
    if (autoSaveTimeoutRef.current) {
      clearTimeout(autoSaveTimeoutRef.current);
    }

    autoSaveTimeoutRef.current = setTimeout(() => {
      setSaveStatus('Syncing...');
      
      setTimeout(() => {
        setSaveStatus('Answer Saved');
        const now = new Date();
        setLastSavedTime(now.toTimeString().split(' ')[0]);
      }, 500);

    }, 800);
  };

  // Handle Answer selection
  const handleAnswerSelect = (optionId) => {
    const updated = [...questions];
    const q = updated[currentIdx];
    q.answer = optionId;
    q.visited = true;
    q.status = 'answered';
    setQuestions(updated);
    triggerAutoSave();
  };

  // Handle Essay change
  const handleEssayChange = (value) => {
    const updated = [...questions];
    const q = updated[currentIdx];
    q.answer = value;
    q.visited = true;
    q.status = value.trim() ? 'answered' : 'unanswered';
    setQuestions(updated);
    triggerAutoSave();
  };

  // Navigate to index
  const navigateTo = (index) => {
    if (index < 0 || index >= questions.length) return;
    
    const updated = [...questions];
    
    // Mark current question as visited/unanswered if they leave it empty
    const currentQ = updated[currentIdx];
    if (!currentQ.visited) {
      currentQ.visited = true;
    }
    
    // Update status logic
    if (currentQ.flagged) {
      currentQ.status = 'flagged';
    } else if (currentQ.answer) {
      currentQ.status = 'answered';
    } else {
      currentQ.status = 'unanswered'; // Skip
    }

    // Now set the target question as visited (in progress)
    const targetQ = updated[index];
    targetQ.visited = true;
    
    setQuestions(updated);
    setCurrentIdx(index);
    
    // Auto-close mobile bottom drawer when jumping
    setIsMobileDrawerOpen(false);
  };

  // Bookmark Toggle
  const toggleBookmark = () => {
    const updated = [...questions];
    const q = updated[currentIdx];
    q.flagged = !q.flagged;
    
    if (q.flagged) {
      q.status = 'flagged';
    } else if (q.answer) {
      q.status = 'answered';
    } else {
      q.status = 'visited';
    }

    setQuestions(updated);
    triggerAutoSave();
  };

  // Summary Metrics calculations
  const totalQuestions = questions.length;
  const answeredCount = questions.filter(q => q.answer).length;
  const flaggedCount = questions.filter(q => q.flagged).length;
  const unansweredCount = questions.filter(q => q.visited && !q.answer && !q.flagged).length;
  const remainingCount = totalQuestions - answeredCount;
  const completionPercentage = totalQuestions ? Math.round((answeredCount / totalQuestions) * 100) : 0;

  // Jump to specific number from search
  const handleSearchJump = (e) => {
    e.preventDefault();
    const num = parseInt(searchQuery, 10);
    if (!isNaN(num) && num >= 1 && num <= totalQuestions) {
      navigateTo(num - 1);
      setSearchQuery('');
    }
  };

  // Keyboard shortcuts
  useEffect(() => {
    const handleKeyDown = (e) => {
      // Avoid shortcuts triggering while typing in essay textarea or search box
      if (document.activeElement.tagName === 'TEXTAREA' || document.activeElement.tagName === 'INPUT') {
        // Allow Arrow keys to slide only if not inside text input
        if (document.activeElement.tagName === 'INPUT' && e.key !== 'Enter') return;
        if (document.activeElement.tagName === 'TEXTAREA') return;
      }

      if (e.key === 'ArrowRight') {
        e.preventDefault();
        navigateTo(Math.min(totalQuestions - 1, currentIdx + 1));
      } else if (e.key === 'ArrowLeft') {
        e.preventDefault();
        navigateTo(Math.max(0, currentIdx - 1));
      } else if (e.ctrlKey && e.key.toLowerCase() === 'b') {
        e.preventDefault();
        toggleBookmark();
      }
    };

    window.addEventListener('keydown', handleKeyDown);
    return () => window.removeEventListener('keydown', handleKeyDown);
  }, [currentIdx, questions]);

  // Auto submit when time runs out
  const triggerAutoSubmit = () => {
    alert("Waktu ujian telah habis! Jawaban Anda akan diserahkan secara otomatis.");
    window.location.reload();
  };

  // Question sections mapping
  const getSectionTitle = (sec) => {
    switch (sec) {
      case 'A': return 'Section A: Pengetahuan Umum (No. 1-20)';
      case 'B': return 'Section B: English Comprehension (No. 21-40)';
      case 'C': return 'Section C: Quantitative & Essay (No. 41-60)';
      default: return '';
    }
  };

  // Filtered Questions list helper
  const getFilteredQuestions = () => {
    return questions.filter(q => {
      if (activeFilter === 'all') return true;
      if (activeFilter === 'answered') return !!q.answer;
      if (activeFilter === 'unanswered') return !q.answer;
      if (activeFilter === 'flagged') return q.flagged;
      if (activeFilter === 'bookmarked') return q.flagged;
      return true;
    });
  };

  const currentQuestion = questions[currentIdx] || null;

  return (
    <div className="flex flex-col min-h-screen bg-slate-50 text-slate-800 font-sans" style={{ '--primary-color': customPrimaryColor }}>
      {/* Top Header */}
      <header className="sticky top-0 z-30 flex items-center justify-between px-4 lg:px-8 py-3 bg-white border-b border-slate-200/80 shadow-sm backdrop-blur-md bg-white/95">
        <div className="flex items-center gap-3">
          <div className="p-2 text-white rounded-xl shadow-md" style={{ backgroundColor: 'var(--primary-color)' }}>
            <BookOpen className="w-6 h-6" />
          </div>
          <div>
            <h1 className="text-lg lg:text-xl font-bold tracking-tight text-slate-900 flex items-center gap-2">
              PROCBT <span className="text-xs px-2 py-0.5 rounded-full font-semibold border" style={{ borderColor: 'var(--primary-color)', color: 'var(--primary-color)', backgroundColor: 'var(--primary-light)' }}>PORTAL UJIAN</span>
            </h1>
            <p className="text-xs text-slate-500 hidden sm:block">Sistem Navigasi Soal Enterprise Kelas Universitas</p>
          </div>
        </div>

        {/* Sync & Auto Save Status */}
        <div className="flex items-center gap-4 lg:gap-6">
          <div className="flex items-center gap-2 px-3 py-1.5 bg-slate-100 rounded-lg text-xs font-semibold text-slate-600 border border-slate-200">
            <RefreshCw className={`w-3.5 h-3.5 ${saveStatus === 'Saving...' || saveStatus === 'Syncing...' ? 'animate-spin text-amber-500' : 'text-emerald-500'}`} />
            <span className="hidden xs:inline">{saveStatus}</span>
            <span className="text-[10px] text-slate-400 font-normal">({lastSavedTime})</span>
          </div>

          {/* Time Counter */}
          <div className="flex items-center gap-2 font-mono font-bold text-base lg:text-lg px-4 py-1.5 bg-rose-50 text-rose-600 rounded-xl border border-rose-100 shadow-sm">
            <Clock className="w-4 h-4 text-rose-500" />
            <span>{formatTime(timeLeft)}</span>
          </div>

          {/* Theme Settings Toggle */}
          <button 
            onClick={() => setIsThemeOpen(!isThemeOpen)}
            className="p-2 hover:bg-slate-100 rounded-xl border border-slate-200 text-slate-600 hover:text-slate-900 transition-all active:scale-95"
            title="Pilih Warna Almamater Universitas"
          >
            <Settings className="w-5 h-5 animate-spin-slow" />
          </button>
        </div>
      </header>

      {/* Floating Color Customizer Panel */}
      <AnimatePresence>
        {isThemeOpen && (
          <motion.div 
            initial={{ opacity: 0, y: -20 }}
            animate={{ opacity: 1, y: 0 }}
            exit={{ opacity: 0, y: -20 }}
            className="absolute top-16 right-4 lg:right-8 z-50 w-80 p-5 bg-white border border-slate-200 rounded-2xl shadow-xl"
          >
            <div className="flex items-center justify-between mb-4 pb-2 border-b">
              <h3 className="font-bold text-slate-950 flex items-center gap-2 text-sm uppercase tracking-wide">
                <Settings className="w-4 h-4 text-slate-500" /> Atur Warna Universitas
              </h3>
              <button onClick={() => setIsThemeOpen(false)} className="text-slate-400 hover:text-slate-600">
                <X className="w-4 h-4" />
              </button>
            </div>
            
            <p className="text-xs text-slate-500 mb-3">Pilih warna primer almamater universitas untuk menyesuaikan seluruh tema visual ujian.</p>
            
            <div className="grid grid-cols-2 gap-2 mb-4">
              {UNIVERSITY_THEMES.map((theme) => (
                <button
                  key={theme.name}
                  onClick={() => handleThemeSelect(theme)}
                  className={`flex items-center gap-2 p-2 rounded-lg text-left text-xs font-semibold transition-all border ${selectedTheme.name === theme.name ? 'border-slate-900 bg-slate-50' : 'border-slate-200 hover:bg-slate-50'}`}
                >
                  <span className="w-3.5 h-3.5 rounded-full border border-black/10 flex-none" style={{ backgroundColor: theme.primary }} />
                  <span className="truncate">{theme.name}</span>
                </button>
              ))}
            </div>

            <div className="pt-3 border-t">
              <label className="block text-xs font-bold text-slate-700 mb-1.5">Warna Custom HEX Picker:</label>
              <div className="flex items-center gap-2">
                <input 
                  type="color" 
                  value={customPrimaryColor}
                  onChange={(e) => {
                    setCustomPrimaryColor(e.target.value);
                    setSelectedTheme({ name: 'Custom Hex', primary: e.target.value });
                  }}
                  className="w-10 h-8 rounded border border-slate-300 cursor-pointer p-0 bg-transparent"
                />
                <input 
                  type="text" 
                  value={customPrimaryColor}
                  onChange={(e) => {
                    if (e.target.value.startsWith('#') && e.target.value.length <= 7) {
                      setCustomPrimaryColor(e.target.value);
                      setSelectedTheme({ name: 'Custom Hex', primary: e.target.value });
                    }
                  }}
                  placeholder="#ffffff"
                  className="flex-1 px-3 py-1 text-xs border border-slate-300 rounded-lg text-slate-700 font-mono focus:outline-none focus:border-slate-500"
                />
              </div>
            </div>
          </motion.div>
        )}
      </AnimatePresence>

      {/* Main CBT Container */}
      <div className="flex flex-1 relative overflow-hidden">
        {/* Left Side: Question Display Area */}
        <main className="flex-1 p-4 lg:p-6 flex flex-col gap-4 overflow-y-auto max-h-[calc(100vh-65px)]">
          
          {/* Question Banner & Navigation Status */}
          <div className="flex flex-col sm:flex-row sm:items-center justify-between gap-3 bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
            <div className="flex items-center gap-2.5">
              <span className="px-3.5 py-1.5 rounded-xl text-xs font-black text-white uppercase tracking-wider" style={{ backgroundColor: 'var(--primary-color)' }}>
                Section {currentQuestion?.section}
              </span>
              <span className="text-sm font-bold text-slate-500">
                Soal {currentIdx + 1} dari {totalQuestions}
              </span>
            </div>
            
            {/* Top Navigation Fast Controls */}
            <div className="flex items-center gap-1.5 self-end sm:self-auto">
              <button 
                onClick={() => navigateTo(0)} 
                disabled={currentIdx === 0}
                className="p-2 bg-slate-50 hover:bg-slate-100 disabled:opacity-50 text-slate-600 disabled:cursor-not-allowed border border-slate-200 rounded-lg transition-all text-xs font-semibold flex items-center gap-1"
                title="Go to First Question"
              >
                Awal
              </button>
              <button 
                onClick={() => navigateTo(currentIdx - 1)} 
                disabled={currentIdx === 0}
                className="p-2 bg-slate-50 hover:bg-slate-100 disabled:opacity-50 text-slate-600 disabled:cursor-not-allowed border border-slate-200 rounded-lg transition-all text-xs font-semibold flex items-center gap-1"
                title="Previous Question (Arrow Left)"
              >
                <ChevronLeft className="w-4 h-4" /> Prev
              </button>
              <button 
                onClick={() => navigateTo(currentIdx + 1)} 
                disabled={currentIdx === totalQuestions - 1}
                className="p-2 bg-slate-50 hover:bg-slate-100 disabled:opacity-50 text-slate-600 disabled:cursor-not-allowed border border-slate-200 rounded-lg transition-all text-xs font-semibold flex items-center gap-1"
                title="Next Question (Arrow Right)"
              >
                Next <ChevronRight className="w-4 h-4" />
              </button>
              <button 
                onClick={() => navigateTo(totalQuestions - 1)} 
                disabled={currentIdx === totalQuestions - 1}
                className="p-2 bg-slate-50 hover:bg-slate-100 disabled:opacity-50 text-slate-600 disabled:cursor-not-allowed border border-slate-200 rounded-lg transition-all text-xs font-semibold flex items-center gap-1"
                title="Go to Last Question"
              >
                Akhir
              </button>
            </div>
          </div>

          {/* Core Question Layout */}
          <div className="flex-1 bg-white border border-slate-200 rounded-3xl shadow-sm flex flex-col min-h-[400px]">
            
            {/* Question Header Actions */}
            <div className="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50 rounded-t-3xl">
              <span className="text-sm font-black text-slate-900 uppercase tracking-wide">
                {getSectionTitle(currentQuestion?.section)}
              </span>
              
              {/* Bookmark/Flag Action Button */}
              <button
                onClick={toggleBookmark}
                className={`flex items-center gap-2 px-4 py-2 rounded-xl border text-xs font-bold transition-all duration-200 ${
                  currentQuestion?.flagged
                    ? 'bg-amber-500 border-amber-500 text-white shadow-md shadow-amber-500/20'
                    : 'bg-white border-slate-200 hover:border-amber-500 hover:bg-amber-50/50 text-slate-600 hover:text-amber-600'
                }`}
              >
                <Bookmark className={`w-4 h-4 ${currentQuestion?.flagged ? 'fill-white' : ''}`} />
                <span>{currentQuestion?.flagged ? 'Ditandai Ragu' : 'Tandai Ragu-Ragu'}</span>
              </button>
            </div>

            {/* Question Text */}
            <div className="p-6 lg:p-8 flex-1">
              <div className="prose max-w-none text-slate-800 text-base lg:text-lg leading-relaxed font-semibold mb-8 whitespace-pre-line text-justify">
                {currentQuestion?.text}
              </div>

              {/* Render MCQ Answers */}
              {currentQuestion?.type === 'mcq' ? (
                <div className="grid grid-cols-1 gap-3 max-w-3xl">
                  {currentQuestion?.options.map((opt) => (
                    <button
                      key={opt.id}
                      onClick={() => handleAnswerSelect(opt.id)}
                      className={`group relative flex items-center gap-4 p-4 rounded-2xl border-2 text-left transition-all duration-200 cursor-pointer ${
                        currentQuestion?.answer === opt.id
                          ? 'border-slate-800 bg-slate-50 shadow-md'
                          : 'border-slate-100 hover:border-slate-300 bg-white hover:bg-slate-50/30'
                      }`}
                    >
                      {/* Option Alphabet Indicator */}
                      <div 
                        className={`w-9 h-9 rounded-xl border flex items-center justify-center font-bold text-sm transition-all ${
                          currentQuestion?.answer === opt.id
                            ? 'text-white border-transparent'
                            : 'text-slate-500 border-slate-200 group-hover:border-slate-400 group-hover:text-slate-700 bg-slate-50'
                        }`}
                        style={{ backgroundColor: currentQuestion?.answer === opt.id ? 'var(--primary-color)' : '' }}
                      >
                        {opt.id.toUpperCase()}
                      </div>
                      
                      <span className="text-sm font-semibold text-slate-700 flex-1">{opt.text}</span>
                      
                      {currentQuestion?.answer === opt.id && (
                        <div className="w-5 h-5 rounded-full flex items-center justify-center text-white" style={{ backgroundColor: 'var(--primary-color)' }}>
                          <Check className="w-3.5 h-3.5 stroke-[3]" />
                        </div>
                      )}
                    </button>
                  ))}
                </div>
              ) : (
                // Render Essay Input
                <div className="space-y-3 max-w-4xl">
                  <label className="block text-sm font-bold text-slate-700">Ketikkan lembar jawaban essay Anda di bawah:</label>
                  <textarea
                    value={currentQuestion?.answer || ''}
                    onChange={(e) => handleEssayChange(e.target.value)}
                    placeholder="Tuliskan jawaban lengkap dengan argumen pendukung..."
                    className="w-full h-64 p-5 rounded-2xl border-2 border-slate-200 focus:border-slate-500 focus:outline-none transition-all text-sm font-medium resize-none shadow-inner bg-slate-50/50"
                  />
                  <div className="flex justify-between text-xs text-slate-400 font-semibold italic">
                    <span>* Karakter tersimpan otomatis saat mengetik.</span>
                    <span>{currentQuestion?.answer?.length || 0} karakter</span>
                  </div>
                </div>
              )}
            </div>

            {/* Bottom Actions footer */}
            <div className="p-4 lg:p-6 border-t border-slate-100 flex items-center justify-between rounded-b-3xl bg-slate-50/20">
              <button
                onClick={() => navigateTo(currentIdx - 1)}
                disabled={currentIdx === 0}
                className="flex items-center gap-1.5 px-5 py-3 border border-slate-200 text-slate-600 font-bold hover:text-slate-900 bg-white hover:bg-slate-50 disabled:opacity-50 disabled:cursor-not-allowed rounded-xl transition-all text-xs"
              >
                <ChevronLeft className="w-4 h-4" /> Soal Sebelumnya
              </button>

              <div className="hidden sm:flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 bg-slate-100 text-slate-500 rounded-lg">
                <Shield className="w-3.5 h-3.5" /> PROCBT Secure Lock
              </div>

              {currentIdx === totalQuestions - 1 ? (
                <button
                  onClick={() => setIsSubmitModalOpen(true)}
                  className="flex items-center gap-1.5 px-6 py-3 font-bold text-white shadow-lg shadow-emerald-500/20 rounded-xl hover:-translate-y-0.5 transition-all text-xs bg-emerald-600 hover:bg-emerald-700"
                >
                  <CheckCircle className="w-4 h-4" /> Selesaikan Ujian
                </button>
              ) : (
                <button
                  onClick={() => navigateTo(currentIdx + 1)}
                  className="flex items-center gap-1.5 px-6 py-3 font-bold text-white shadow-lg transition-all text-xs hover:-translate-y-0.5 rounded-xl"
                  style={{ backgroundColor: 'var(--primary-color)' }}
                >
                  Soal Selanjutnya <ChevronRight className="w-4 h-4" />
                </button>
              )}
            </div>
          </div>

          {/* Mobile Bottom Navigation Bar (Toggle Bottom Sheet Drawer) */}
          <div className="lg:hidden sticky bottom-0 left-0 right-0 bg-white border border-slate-200 rounded-2xl shadow-xl flex items-center justify-between p-3 gap-2 z-20">
            <div className="flex flex-col">
              <span className="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Progres Ujian</span>
              <span className="text-xs font-black text-slate-800">{answeredCount}/{totalQuestions} Soal Selesai</span>
            </div>
            <button
              onClick={() => setIsMobileDrawerOpen(true)}
              className="px-4 py-2 text-white font-bold rounded-xl text-xs flex items-center gap-1.5 shadow-md"
              style={{ backgroundColor: 'var(--primary-color)' }}
            >
              <Menu className="w-4 h-4" /> Buka Peta Soal
            </button>
          </div>
        </main>

        {/* Right Side: Desktop Navigation Sidebar */}
        <aside className={`
          hidden lg:flex flex-col w-80 bg-white border-l border-slate-200/80 shadow-[-10px_0_30px_rgba(0,0,0,0.01)] transition-all duration-300 z-10
          ${isRightSidebarOpen ? 'relative' : 'absolute right-0 h-full translate-x-full'}
        `}>
          {/* Sidebar Toggle Handle for clean workspace */}
          <button
            onClick={() => setIsRightSidebarOpen(!isRightSidebarOpen)}
            className="absolute top-1/2 -left-3.5 z-20 w-7 h-10 bg-white border border-y-slate-200 border-l-slate-200 shadow-md flex items-center justify-center rounded-l-lg text-slate-500 hover:text-slate-800"
          >
            {isRightSidebarOpen ? <ChevronRight className="w-4 h-4" /> : <ChevronLeft className="w-4 h-4" />}
          </button>

          {/* Sidebar Header Section */}
          <div className="p-5 border-b border-slate-100 bg-slate-50/50">
            <h3 className="font-black text-slate-900 text-sm uppercase tracking-wider mb-4">Navigasi & Ringkasan Soal</h3>
            
            {/* Quick Progress Bar */}
            <div className="bg-white p-3 rounded-2xl border border-slate-200/80 shadow-sm flex flex-col gap-2">
              <div className="flex items-center justify-between text-xs font-bold">
                <span className="text-slate-400">Peta Ujian</span>
                <span className="text-slate-800" style={{ color: 'var(--primary-color)' }}>{completionPercentage}% Selesai</span>
              </div>
              <div className="w-full h-2.5 bg-slate-100 rounded-full overflow-hidden shadow-inner">
                <div 
                  className="h-full rounded-full transition-all duration-500" 
                  style={{ width: `${completionPercentage}%`, backgroundColor: 'var(--primary-color)' }}
                />
              </div>
              <div className="grid grid-cols-2 gap-2 text-[10px] font-bold text-slate-500 mt-1 pt-1 border-t">
                <div>Terjawab: <span className="text-slate-800 font-extrabold">{answeredCount}</span></div>
                <div>Sisa: <span className="text-slate-800 font-extrabold">{remainingCount}</span></div>
              </div>
            </div>
          </div>

          {/* Quick Filters */}
          <div className="px-5 py-3 border-b border-slate-100 bg-white">
            <div className="flex items-center gap-1.5 overflow-x-auto pb-1.5 custom-scrollbar">
              {[
                { id: 'all', label: 'Semua' },
                { id: 'answered', label: 'Terisi' },
                { id: 'unanswered', label: 'Skip' },
                { id: 'flagged', label: 'Ragu' }
              ].map((f) => (
                <button
                  key={f.id}
                  onClick={() => setActiveFilter(f.id)}
                  className={`px-3 py-1.5 rounded-lg text-xs font-bold border transition-all whitespace-nowrap ${
                    activeFilter === f.id
                      ? 'text-white border-transparent shadow-sm'
                      : 'bg-slate-50 border-slate-200 text-slate-500 hover:text-slate-800 hover:border-slate-300'
                  }`}
                  style={{ backgroundColor: activeFilter === f.id ? 'var(--primary-color)' : '' }}
                >
                  {f.label}
                </button>
              ))}
            </div>
          </div>

          {/* Question Palette Number Grid */}
          <div className="flex-1 p-5 overflow-y-auto custom-scrollbar bg-slate-50/10">
            <div className="grid grid-cols-5 gap-2.5">
              {questions.map((q, idx) => {
                const isCurrent = idx === currentIdx;
                const isFiltered = 
                  activeFilter === 'all' ||
                  (activeFilter === 'answered' && !!q.answer) ||
                  (activeFilter === 'unanswered' && !q.answer) ||
                  (activeFilter === 'flagged' && q.flagged);

                // Styling logic based on status
                let stateStyle = 'bg-slate-100 hover:bg-slate-200 text-slate-500 border border-slate-200/60 shadow-sm';
                let stateDot = null;
                
                if (q.flagged) {
                  // Orange
                  stateStyle = 'bg-amber-100 hover:bg-amber-200 text-amber-800 border-2 border-amber-300 shadow-sm';
                } else if (q.answer) {
                  // Green
                  stateStyle = 'bg-emerald-100 hover:bg-emerald-200 text-emerald-800 border-2 border-emerald-300 shadow-sm';
                } else if (q.status === 'unanswered') {
                  // Red (Skipped)
                  stateStyle = 'bg-rose-100 hover:bg-rose-200 text-rose-800 border-2 border-rose-300 shadow-sm';
                } else if (q.visited) {
                  // Blue
                  stateStyle = 'bg-blue-100 hover:bg-blue-200 text-blue-800 border-2 border-blue-300 shadow-sm';
                }

                // Override if it is current active question
                if (isCurrent) {
                  stateStyle = 'text-white scale-110 shadow-md ring-4 z-10 border-transparent';
                }

                return (
                  <button
                    key={q.id}
                    onClick={() => navigateTo(idx)}
                    className={`
                      relative w-11 h-11 rounded-xl flex items-center justify-center font-extrabold text-xs transition-all active:scale-95 duration-200
                      ${stateStyle} ${!isFiltered ? 'opacity-30' : 'opacity-100'}
                    `}
                    style={{ 
                      backgroundColor: isCurrent ? 'var(--primary-color)' : '',
                      '--tw-ring-color': isCurrent ? 'var(--primary-light)' : 'transparent'
                    }}
                  >
                    {q.id}
                  </button>
                );
              })}
            </div>
          </div>

          {/* Quick Search & Bottom Sidebar Widget */}
          <div className="p-4 border-t border-slate-100 bg-white">
            <form onSubmit={handleSearchJump} className="flex items-center gap-2 mb-3">
              <div className="relative flex-1">
                <Search className="absolute left-2.5 top-2.5 w-4 h-4 text-slate-400" />
                <input 
                  type="text" 
                  value={searchQuery}
                  onChange={(e) => setSearchQuery(e.target.value)}
                  placeholder="Cari No. Soal..."
                  className="w-full pl-8 pr-3 py-2 border border-slate-200 rounded-lg text-xs font-semibold focus:outline-none focus:border-slate-400"
                />
              </div>
              <button 
                type="submit"
                className="px-3.5 py-2 text-white font-bold rounded-lg text-xs hover:-translate-y-0.5 transition-all shadow-sm"
                style={{ backgroundColor: 'var(--primary-color)' }}
              >
                Go
              </button>
            </form>

            <button
              onClick={() => setIsSubmitModalOpen(true)}
              className="w-full py-3 bg-slate-900 hover:bg-slate-950 text-white font-bold rounded-xl text-xs flex items-center justify-center gap-2 shadow-lg transition-all"
            >
              <CheckCircle className="w-4 h-4 text-emerald-400" /> Kumpulkan Ujian Sekarang
            </button>
          </div>
        </aside>
      </div>

      {/* Colors Legend panel on desktop */}
      <footer className="hidden lg:flex items-center gap-6 px-8 py-3 bg-white border-t border-slate-200/80 text-[10px] text-slate-500 font-black uppercase tracking-wider">
        <span className="mr-2">LEGENDA STATUS:</span>
        <div className="flex items-center gap-1.5">
          <div className="w-3.5 h-3.5 rounded-full" style={{ backgroundColor: 'var(--primary-color)' }} />
          <span>Sedang Dibuka</span>
        </div>
        <div className="flex items-center gap-1.5">
          <div className="w-3.5 h-3.5 rounded-full bg-emerald-500" />
          <span>Sudah Dijawab</span>
        </div>
        <div className="flex items-center gap-1.5">
          <div className="w-3.5 h-3.5 rounded-full bg-amber-500" />
          <span>Ragu-Ragu</span>
        </div>
        <div className="flex items-center gap-1.5">
          <div className="w-3.5 h-3.5 rounded-full bg-rose-500" />
          <span>Belum Dijawab (Skipped)</span>
        </div>
        <div className="flex items-center gap-1.5">
          <div className="w-3.5 h-3.5 rounded-full bg-blue-500" />
          <span>Dikunjungi (Belum Diisi)</span>
        </div>
        <div className="flex items-center gap-1.5">
          <div className="w-3.5 h-3.5 rounded-full bg-slate-300" />
          <span>Belum Dibuka</span>
        </div>
      </footer>

      {/* Mobile Drawer (Bottom Sheet) */}
      <AnimatePresence>
        {isMobileDrawerOpen && (
          <>
            {/* Backdrop */}
            <motion.div
              initial={{ opacity: 0 }}
              animate={{ opacity: 0.5 }}
              exit={{ opacity: 0 }}
              onClick={() => setIsMobileDrawerOpen(false)}
              className="fixed inset-0 bg-black z-40 lg:hidden"
            />
            {/* Drawer */}
            <motion.div
              initial={{ y: '100%' }}
              animate={{ y: 0 }}
              exit={{ y: '100%' }}
              transition={{ type: 'spring', damping: 25, stiffness: 200 }}
              className="fixed bottom-0 left-0 right-0 max-h-[85vh] bg-white rounded-t-3xl border-t border-slate-200/80 z-50 flex flex-col shadow-2xl lg:hidden"
            >
              {/* Drag Handle */}
              <div className="w-12 h-1.5 bg-slate-300 rounded-full mx-auto my-3 cursor-pointer" onClick={() => setIsMobileDrawerOpen(false)} />
              
              <div className="flex items-center justify-between px-5 pb-3 border-b border-slate-100">
                <div>
                  <h3 className="font-extrabold text-slate-900 text-sm uppercase">Peta Navigasi Soal</h3>
                  <p className="text-[10px] text-slate-400 font-bold uppercase mt-0.5">{answeredCount} dari {totalQuestions} Soal Selesai</p>
                </div>
                <button onClick={() => setIsMobileDrawerOpen(false)} className="p-1.5 hover:bg-slate-100 rounded-full text-slate-500">
                  <X className="w-5 h-5" />
                </button>
              </div>

              {/* Grid content inside Mobile Drawer */}
              <div className="flex-1 p-5 overflow-y-auto custom-scrollbar">
                <div className="grid grid-cols-5 gap-2.5 max-w-sm mx-auto">
                  {questions.map((q, idx) => {
                    const isCurrent = idx === currentIdx;
                    let stateStyle = 'bg-slate-100 text-slate-500 border border-slate-200/60';
                    
                    if (q.flagged) {
                      stateStyle = 'bg-amber-100 text-amber-800 border-2 border-amber-300';
                    } else if (q.answer) {
                      stateStyle = 'bg-emerald-100 text-emerald-800 border-2 border-emerald-300';
                    } else if (q.status === 'unanswered') {
                      stateStyle = 'bg-rose-100 text-rose-800 border-2 border-rose-300';
                    } else if (q.visited) {
                      stateStyle = 'bg-blue-100 text-blue-800 border-2 border-blue-300';
                    }

                    if (isCurrent) {
                      stateStyle = 'text-white scale-110 shadow-md ring-4';
                    }

                    return (
                      <button
                        key={q.id}
                        onClick={() => navigateTo(idx)}
                        className={`w-11 h-11 rounded-xl flex items-center justify-center font-extrabold text-xs transition-all active:scale-95 duration-200 ${stateStyle}`}
                        style={{ 
                          backgroundColor: isCurrent ? 'var(--primary-color)' : '',
                          '--tw-ring-color': isCurrent ? 'var(--primary-light)' : 'transparent'
                        }}
                      >
                        {q.id}
                      </button>
                    );
                  })}
                </div>
              </div>

              {/* Search & Actions inside Mobile Drawer */}
              <div className="p-4 border-t border-slate-100 bg-slate-50">
                <button
                  onClick={() => {
                    setIsMobileDrawerOpen(false);
                    setIsSubmitModalOpen(true);
                  }}
                  className="w-full py-3 bg-slate-900 hover:bg-slate-950 text-white font-bold rounded-xl text-xs flex items-center justify-center gap-2 shadow-lg transition-all"
                >
                  <CheckCircle className="w-4 h-4 text-emerald-400" /> Kumpulkan Ujian Sekarang
                </button>
              </div>
            </motion.div>
          </>
        )}
      </AnimatePresence>

      {/* Before Submit Summary / Submit Warning Confirmation Modal */}
      <AnimatePresence>
        {isSubmitModalOpen && (
          <>
            {/* Backdrop */}
            <motion.div
              initial={{ opacity: 0 }}
              animate={{ opacity: 0.5 }}
              exit={{ opacity: 0 }}
              onClick={() => setIsSubmitModalOpen(false)}
              className="fixed inset-0 bg-black/60 z-50 backdrop-blur-sm"
            />
            {/* Modal */}
            <motion.div
              initial={{ scale: 0.95, opacity: 0 }}
              animate={{ scale: 1, opacity: 1 }}
              exit={{ scale: 0.95, opacity: 0 }}
              className="fixed top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[90%] max-w-lg bg-white rounded-3xl border border-slate-200 shadow-2xl z-50 p-6 flex flex-col gap-5"
            >
              <div className="flex items-center justify-between pb-3 border-b">
                <h3 className="text-base lg:text-lg font-black text-slate-900 uppercase tracking-wide flex items-center gap-2">
                  <AlertTriangle className="w-5 h-5 text-amber-500" /> Konfirmasi Penyerahan Ujian
                </h3>
                <button onClick={() => setIsSubmitModalOpen(false)} className="p-1 hover:bg-slate-100 rounded-full text-slate-500">
                  <X className="w-5 h-5" />
                </button>
              </div>

              {/* Warning box if there are unanswered or flagged questions */}
              {(unansweredCount > 0 || flaggedCount > 0) && (
                <div className="p-4 bg-amber-50 border border-amber-200 rounded-2xl flex gap-3 text-amber-800">
                  <AlertCircle className="w-5 h-5 text-amber-500 flex-none self-start mt-0.5" />
                  <div className="text-xs font-semibold leading-relaxed">
                    <p className="font-bold text-sm mb-1 text-amber-900">Perhatian! Anda Masih Memiliki:</p>
                    <ul className="list-disc pl-4 space-y-0.5">
                      {unansweredCount > 0 && <li>{unansweredCount} soal yang belum dijawab / dilewati</li>}
                      {flaggedCount > 0 && <li>{flaggedCount} soal yang masih ditandai ragu-ragu</li>}
                    </ul>
                    <p className="mt-2 text-amber-700 italic">Harap periksa kembali jawaban Anda sebelum mengakhiri ujian.</p>
                  </div>
                </div>
              )}

              {/* Complete metrics breakdown */}
              <div>
                <h4 className="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2.5">Ringkasan Ujian Anda:</h4>
                <div className="grid grid-cols-2 gap-3">
                  <div className="p-3 bg-slate-50 rounded-xl border border-slate-100 flex flex-col">
                    <span className="text-[10px] text-slate-400 font-bold uppercase">Total Soal</span>
                    <span className="text-lg font-black text-slate-800">{totalQuestions} Soal</span>
                  </div>
                  <div className="p-3 bg-emerald-50 rounded-xl border border-emerald-100 flex flex-col text-emerald-800">
                    <span className="text-[10px] text-emerald-400 font-bold uppercase">Sudah Dijawab</span>
                    <span className="text-lg font-black text-emerald-600">{answeredCount} Soal</span>
                  </div>
                  <div className="p-3 bg-rose-50 rounded-xl border border-rose-100 flex flex-col text-rose-800">
                    <span className="text-[10px] text-rose-400 font-bold uppercase">Belum Dijawab</span>
                    <span className="text-lg font-black text-rose-600">{totalQuestions - answeredCount} Soal</span>
                  </div>
                  <div className="p-3 bg-amber-50 rounded-xl border border-amber-100 flex flex-col text-amber-800">
                    <span className="text-[10px] text-amber-400 font-bold uppercase">Ragu-Ragu</span>
                    <span className="text-lg font-black text-amber-600">{flaggedCount} Soal</span>
                  </div>
                </div>
              </div>

              <div className="p-4 bg-slate-50 rounded-2xl border border-slate-150 text-xs text-slate-500 font-medium leading-relaxed">
                Menyerahkan lembar jawaban berarti Anda mengakhiri ujian ini secara permanen. Tindakan ini tidak dapat dibatalkan, dan nilai Anda akan segera diproses.
              </div>

              {/* Confirm Actions */}
              <div className="flex items-center justify-end gap-3 pt-3 border-t">
                <button
                  onClick={() => setIsSubmitModalOpen(false)}
                  className="px-5 py-3 border border-slate-200 text-slate-600 hover:text-slate-800 font-bold hover:bg-slate-50 rounded-xl text-xs transition-all"
                >
                  Kembali Periksa
                </button>
                <button
                  onClick={() => {
                    alert("Lembar jawaban berhasil dikirim! Mengalihkan halaman ke hasil ujian.");
                    window.location.reload();
                  }}
                  className="px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl text-xs hover:-translate-y-0.5 transition-all shadow-lg shadow-emerald-500/20"
                >
                  Ya, Kirim Sekarang
                </button>
              </div>
            </motion.div>
          </>
        )}
      </AnimatePresence>
    </div>
  );
}
