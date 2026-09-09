import Swal from 'sweetalert2';
import axios from 'axios';

export const promptSupervisorMessage = async (session) => {
    if (!session) return;

    const templates = [
        'Harap kembali fokus ke layar ujian.',
        'Wajah tidak terdeteksi di kamera, harap posisikan diri dengan benar.',
        'Dilarang membuka tab atau aplikasi lain selain ujian.',
        'Dilarang berbicara atau meminta bantuan orang lain.',
    ];

    const { value: messageText } = await Swal.fire({
        title: `<div class="text-left font-bold text-gray-900 text-lg">Kirim Pesan ke Peserta</div>`,
        html: `
            <div style="text-align: left; font-size: 13px;">
                <p style="margin-bottom: 6px; font-weight: 600; color: #4b5563;">Peserta: <span style="color: #ea580c;">${session.name || 'Peserta'}</span></p>
                <p style="margin-bottom: 8px; font-size: 12px; font-weight: 600; color: #6b7280; text-transform: uppercase;">Pilih Template Cepat:</p>
                <div style="display: flex; flex-direction: column; gap: 6px; margin-bottom: 14px;">
                    ${templates.map(t => `<button type="button" class="swal-template-btn" style="text-align: left; padding: 7px 10px; font-size: 12px; border: 1px solid #fed7aa; border-radius: 8px; background: #fff7ed; color: #9a3412; cursor: pointer; transition: all 0.2s;">💬 ${t}</button>`).join('')}
                </div>
                <p style="margin-bottom: 6px; font-weight: 600; color: #4b5563;">Teks Pesan Peringatan:</p>
                <textarea id="swal-supervisor-message" style="width: 100%; border: 1px solid #d1d5db; border-radius: 8px; padding: 10px; font-size: 13px; box-sizing: border-box; resize: vertical;" rows="3">Harap kembali fokus ke layar ujian.</textarea>
            </div>
        `,
        focusConfirm: false,
        showCancelButton: true,
        confirmButtonText: '<i class="fa-solid fa-paper-plane mr-1"></i> Kirim Pesan',
        cancelButtonText: 'Batal',
        confirmButtonColor: '#ea580c',
        cancelButtonColor: '#6b7280',
        didOpen: () => {
            const textarea = document.getElementById('swal-supervisor-message');
            document.querySelectorAll('.swal-template-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    const text = e.currentTarget.innerText.replace('💬 ', '').trim();
                    if (textarea) textarea.value = text;
                });
            });
        },
        preConfirm: () => {
            const textarea = document.getElementById('swal-supervisor-message');
            const msg = textarea ? textarea.value.trim() : '';
            if (!msg) {
                Swal.showValidationMessage('Pesan tidak boleh kosong!');
                return false;
            }
            return msg;
        }
    });

    if (messageText) {
        try {
            Swal.fire({
                title: 'Mengirim pesan...',
                allowOutsideClick: false,
                didOpen: () => Swal.showLoading()
            });

            const res = await axios.post('/api/exam/admin/monitoring/message', {
                session_id: session.id,
                user_timetable_id: session.user_timetable_id,
                message: messageText,
            });

            Swal.fire({
                icon: 'success',
                title: 'Terkirim!',
                text: res.data?.message || `Pesan berhasil dikirim ke ${session.name}`,
                timer: 2500,
                showConfirmButton: false,
            });
        } catch (err) {
            Swal.fire({
                icon: 'error',
                title: 'Gagal Mengirim',
                text: err.response?.data?.message || err.response?.data?.error || 'Terjadi kesalahan saat mengirim pesan',
            });
        }
    }
};
