// =====================
// SAFE EXAM BROWSER DETECTOR
// =====================

// =====================
// SIDEBAR HANDLER
// =====================
document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');
    const innerMainContent = document.querySelector('#main-content .main-content');
    const toggleButton = document.getElementById('toggleSidebar');

    // Jika halaman tidak memiliki sidebar (misal login page), hentikan eksekusi
    if (!sidebar || !mainContent) return;

    function isMobile() {
        return window.innerWidth < 768;
    }

    function hideSidebar() {
        sidebar.classList.add('-translate-x-full');
        sidebar.classList.remove('translate-x-0');
        mainContent.classList.remove('ml-64');
        mainContent.classList.add('ml-0');
        // innerMainContent?.classList.remove('mr-64');
    }

    function showSidebar() {
        sidebar.classList.remove('-translate-x-full');
        sidebar.classList.add('translate-x-0');
        if (!isMobile()) {
            mainContent.classList.remove('ml-0');
            mainContent.classList.add('ml-64');
            // innerMainContent?.classList.add('mr-64');
        }
    }

    // Initial check
    if (isMobile()) {
        hideSidebar();
    } else {
        if (localStorage.getItem('sidebarHidden') === 'true') {
            hideSidebar();
        } else {
            showSidebar();
        }
    }

    // Toggle button
    if (toggleButton) {
        toggleButton.addEventListener('click', function () {
            const isHidden = sidebar.classList.contains('-translate-x-full');
            if (isHidden) {
                showSidebar();
                if (!isMobile()) {
                    localStorage.setItem('sidebarHidden', 'false');
                }
            } else {
                hideSidebar();
                if (!isMobile()) {
                    localStorage.setItem('sidebarHidden', 'true');
                }
            }
        });
    }

    // Resize behavior
    window.addEventListener('resize', function () {
        if (isMobile()) {
            hideSidebar();
        } else {
            if (localStorage.getItem('sidebarHidden') === 'true') {
                hideSidebar();
            } else {
                showSidebar();
            }
        }
    });
});


// =====================
// MENU TOGGLE (MAIN MENU)
// =====================
let currentOpenmenu = null;

window.togglemenu = function (id) {
    const submenu = document.getElementById(id);
    const arrow = document.getElementById(id + '-arrow');

    // Close the currently open menu if it's not the one being clicked
    if (currentOpenmenu && currentOpenmenu !== submenu) {
        currentOpenmenu.classList.remove('open');
        const currentOpenArrow = document.getElementById(currentOpenmenu.id + '-arrow');
        if (currentOpenArrow) currentOpenArrow.classList.remove('rotate');
    }

    if (submenu) submenu.classList.toggle('open');
    if (arrow) arrow.classList.toggle('rotate');

    currentOpenmenu = submenu && submenu.classList.contains('open') ? submenu : null;
};


// =====================
// MENU TOGGLE (SUBMENU)
// =====================
let currentOpenSubmenu = null;

window.toggleSubmenu = function (id) {
    const submenu = document.getElementById(id);
    const arrow = document.getElementById(id + '-arrow');

    if (currentOpenSubmenu && currentOpenSubmenu !== submenu) {
        currentOpenSubmenu.classList.remove('open');
        const currentOpenArrow = document.getElementById(currentOpenSubmenu.id + '-arrow');
        if (currentOpenArrow) currentOpenArrow.classList.remove('rotate');
    }

    if (submenu) submenu.classList.toggle('open');
    if (arrow) arrow.classList.toggle('rotate');

    currentOpenSubmenu = submenu && submenu.classList.contains('open') ? submenu : null;
};


// =====================
// FORMAT RUPIAH
// =====================
function convertToRupiah(input) {
    if (!input || !input.value) return;
    let angka = input.value.replace(/[^,\d]/g, "");
    let split = angka.split(",");
    let sisa = split[0].length % 3;
    let rupiah = split[0].substr(0, sisa);
    let ribuan = split[0].substr(sisa).match(/\d{3}/g);

    if (ribuan) {
        let separator = sisa ? "." : "";
        rupiah += separator + ribuan.join(".");
    }

    rupiah = split[1] !== undefined ? rupiah + "," + split[1] : rupiah;
    input.value = rupiah;
}
window.convertToRupiah = convertToRupiah;


// =====================
// MODAL HANDLER
// =====================
window.addEventListener('open-modal', event => {
    const modalId = event.detail?.id || event.detail?.[0]?.id;
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
});

window.addEventListener('close-modal', event => {
    const modalId = event.detail?.id || event.detail?.[0]?.id;
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
});

// Klik luar modal
document.addEventListener('click', function (e) {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        if (!modal.classList.contains('hidden') && e.target === modal) {
            window.livewire?.emit('closeModal', modal.id);
        }
    });
});


// =====================
// DISABLE AUTOCOMPLETE
// =====================
document.addEventListener('DOMContentLoaded', function () {
    const allElements = document.querySelectorAll('input, textarea, select');
    allElements.forEach(el => el.setAttribute('autocomplete', 'off'));
});


// =====================
// SIDEBAR ACTIVE SCROLL
// =====================
document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar-menu');
    if (!sidebar) return;

    const activeMenu = sidebar.querySelector('.active-menu');
    if (activeMenu) sidebar.scrollTop = activeMenu.offsetTop - 100;
});


// =====================
// CALL PASIEN ALERT
// =====================
document.addEventListener('callPasienAlert', event => {
    const text = event.detail[0];
    let count = 0;

    function playAudioAndSpeak() {
        let audioContext = new (window.AudioContext || window.webkitAudioContext)();
        let audio = new Audio('/asset/music/nada-suara.mp3');
        let source = audioContext.createMediaElementSource(audio);
        let gainNode = audioContext.createGain();
        gainNode.gain.value = 2;
        source.connect(gainNode);
        gainNode.connect(audioContext.destination);

        if (audioContext.state === 'suspended') {
            audioContext.resume();
        }

        audio.play().catch(e => console.error('Audio play failed:', e));

        audio.onended = function () {
            setTimeout(() => {
                let speech = new SpeechSynthesisUtterance(text);
                speech.lang = 'id-ID';
                speech.rate = 0.9;
                speech.volume = 1;

                function setFemaleVoice() {
                    window.speechSynthesis.cancel();

                    let voices = window.speechSynthesis.getVoices();
                    let femaleVoice = null;

                    let microsoftVoice = voices.find(voice => voice.name.includes('Microsoft Andika'));
                    let googleVoice = voices.find(voice => voice.name.includes('Google Bahasa Indonesia'));

                    console.log('=== VOICE COMPARISON ===');
                    console.log('Microsoft Andika found:', !!microsoftVoice);
                    console.log('Google Bahasa found:', !!googleVoice);

                    if (microsoftVoice) {
                        femaleVoice = microsoftVoice;
                        speech.pitch = 1.4;
                    } else if (googleVoice) {
                        femaleVoice = googleVoice;
                        speech.pitch = 1.6;
                    } else {
                        speech.pitch = 1.8;
                    }

                    if (femaleVoice) speech.voice = femaleVoice;

                    speech.onend = function () {
                        console.log('✅ Speech completed');
                        count++;
                        if (count < 2) setTimeout(playAudioAndSpeak, 500);
                    };

                    try {
                        window.speechSynthesis.speak(speech);
                    } catch (error) {
                        console.error('❌ Speech failed:', error);
                    }
                }

                if (window.speechSynthesis.getVoices().length > 0) {
                    setFemaleVoice();
                } else {
                    window.speechSynthesis.onvoiceschanged = setFemaleVoice;
                }
            }, 300);
        };
    }

    playAudioAndSpeak();
});
