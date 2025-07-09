document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main-content');
    const innerMainContent = document.querySelector('#main-content .main-content');
    const toggleButton = document.getElementById('toggleSidebar');

    function isMobile() {
        return window.innerWidth < 768;
    }

    function hideSidebar() {
        sidebar.classList.add('-translate-x-full');
        sidebar.classList.remove('translate-x-0');
        mainContent.classList.remove('ml-64');
        mainContent.classList.add('ml-0');
        // innerMainContent.classList.remove('mr-64');
    }

    function showSidebar() {
        sidebar.classList.remove('-translate-x-full');
        sidebar.classList.add('translate-x-0');
        if (!isMobile()) {
            mainContent.classList.remove('ml-0');
            mainContent.classList.add('ml-64');
            // innerMainContent.classList.add('mr-64');
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


window.addEventListener('resize', function () {
    const isMobileNow = window.innerWidth < 768;
    if (isMobileNow) {
        hideSidebar();
    } else {
        if (localStorage.getItem('sidebarHidden') === 'true') {
            hideSidebar();
        } else {
            showSidebar();
        }
    }
});



// Keep track of the currently open submenu
let currentOpenmenu = null;

window.togglemenu = function(id) {
    const submenu = document.getElementById(id);
    const arrow = document.getElementById(id + '-arrow');

    // Close the currently open menu if it's not the one being clicked
    if (currentOpenmenu && currentOpenmenu !== submenu) {
        currentOpenmenu.classList.remove('open');
        const currentOpenArrow = document.getElementById(currentOpenmenu.id + '-arrow');
        if (currentOpenArrow) {
            currentOpenArrow.classList.remove('rotate');
        }
    }

    // Toggle the submenu
    if (submenu) {
        submenu.classList.toggle('open');
    }

    // Toggle the arrow
    if (arrow) {
        arrow.classList.toggle('rotate');
    }

    // Update the currently open submenu
    currentOpenmenu = submenu.classList.contains('open') ? submenu : null;
}


let currentOpenSubmenu = null;

window.toggleSubmenu = function(id) {
    const submenu = document.getElementById(id);
    const arrow = document.getElementById(id + '-arrow');

    // Close the currently open submenu if it's not the one being clicked
    if (currentOpenSubmenu && currentOpenSubmenu !== submenu) {
        currentOpenSubmenu.classList.remove('open');
        const currentOpenArrow = document.getElementById(currentOpenSubmenu.id + '-arrow');
        if (currentOpenArrow) {
            currentOpenArrow.classList.remove('rotate');
        }
    }

    // Toggle the submenu
    if (submenu) {
        submenu.classList.toggle('open');
    }

    // Toggle the arrow
    if (arrow) {
        arrow.classList.toggle('rotate');
    }

    // Update the currently open submenu
    currentOpenSubmenu = submenu.classList.contains('open') ? submenu : null;
}

function convertToRupiah(input) {
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

// Ini penting! Daftarkan ke global scope
window.convertToRupiah = convertToRupiah;

window.addEventListener('open-modal', event => {
    const modal = document.getElementById(event.detail[0].id);
    if (modal) {
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }
});


// Dengar event dari Livewire untuk tutup modal berdasarkan ID yang dikirimkan
window.addEventListener('close-modal', event => {
    const modal = document.getElementById(event.detail[0].id);
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
});

// Tutup modal jika klik luar konten
document.addEventListener('click', function(e) {
    const modals = document.querySelectorAll('.modal');
    modals.forEach(modal => {
        if (!modal.classList.contains('hidden') && e.target === modal) {
            window.livewire.emit('closeModal', modal.id); // Kirim event untuk menutup modal tertentu
        }
    });
});


// Saat DOM sudah siap
document.addEventListener('DOMContentLoaded', function () {
    const allElements = document.querySelectorAll('input, textarea, select');
    allElements.forEach(el => {
        el.setAttribute('autocomplete', 'off');
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar-menu');
    const activeMenu = sidebar.querySelector('.active-menu'); // pakai class, bukan id
    if (sidebar && activeMenu) {
        sidebar.scrollTop = activeMenu.offsetTop - 100; // scroll ke menu aktif
    }
});

document.addEventListener('callPasienAlert', event => {
    const text = event.detail[0];
    let count = 0;

    function playAudioAndSpeak() {
        let audioContext = new(window.AudioContext || window.webkitAudioContext)();
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

        audio.onended = function() {
            setTimeout(() => {
                let speech = new SpeechSynthesisUtterance(text);
                speech.lang = 'id-ID';
                speech.rate = 0.9;
                speech.volume = 1;

                function setFemaleVoice() {
                    window.speechSynthesis.cancel();

                    let voices = window.speechSynthesis.getVoices();
                    let femaleVoice = null;

                    // Cari Microsoft Andika (biasanya lebih natural dan bisa female)
                    let microsoftVoice = voices.find(voice =>
                        voice.name.includes('Microsoft Andika')
                    );

                    let googleVoice = voices.find(voice =>
                        voice.name.includes('Google Bahasa Indonesia')
                    );

                    console.log('=== VOICE COMPARISON ===');
                    console.log('Microsoft Andika found:', microsoftVoice ? 'Yes' : 'No');
                    console.log('Google Bahasa found:', googleVoice ? 'Yes' : 'No');

                    // Prioritas: Microsoft Andika dengan pitch tinggi (lebih natural)
                    if (microsoftVoice) {
                        femaleVoice = microsoftVoice;
                        speech.pitch = 1.4; // Pitch lebih tinggi untuk efek female
                        console.log('✅ Using Microsoft Andika with high pitch for female effect');
                    } else if (googleVoice) {
                        femaleVoice = googleVoice;
                        speech.pitch = 1.6; // Pitch sangat tinggi untuk Google voice
                        console.log('✅ Using Google Bahasa Indonesia with very high pitch');
                    } else {
                        speech.pitch = 1.8; // Fallback dengan pitch maksimal
                        console.log('⚠️ Using default voice with maximum pitch');
                    }

                    if (femaleVoice) {
                        speech.voice = femaleVoice;
                        console.log('=== FINAL SETTINGS ===');
                        console.log('Selected Voice:', femaleVoice.name);
                        console.log('Pitch:', speech.pitch);
                        console.log('Rate:', speech.rate);
                    }

                    speech.onstart = function() {
                        console.log('🔊 Speech started with voice:', speech.voice ? speech.voice.name : 'default');
                    };

                    speech.onerror = function(event) {
                        console.error('❌ Speech error:', event.error);
                    };

                    speech.onend = function() {
                        console.log('✅ Speech completed');
                        count++;
                        if (count < 2) {
                            setTimeout(() => playAudioAndSpeak(), 500);
                        }
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
