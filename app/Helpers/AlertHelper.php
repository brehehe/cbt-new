<?php

namespace App\Helpers;

use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;

class AlertHelper
{
    /**
     * Palet warna standar untuk semua jenis alert
     */
    private static array $colors = [
        'primary'   => '#4F46E5', // Indigo-600
        'secondary' => '#6B7280', // Gray-500
        'success'   => '#10B981', // Emerald-500
        'error'     => '#EF4444', // Red-500
        'warning'   => '#F59E0B', // Amber-500
        'info'      => '#3B82F6', // Blue-500
        'question'  => '#6366F1', // Indigo-500
        'dark'      => '#1F2937', // Gray-800
    ];

    /**
     * Konfigurasi default untuk tiap jenis alert
     */
    private static array $alertConfig = [
        'success' => [
            'icon' => 'success',
            'timer' => 3000,
            'title' => 'Berhasil',
            'confirmText' => 'OK'
        ],
        'error' => [
            'icon' => 'error',
            'timer' => 3000,
            'title' => 'Gagal',
            'confirmText' => 'OK'
        ],
        'warning' => [
            'icon' => 'warning',
            'timer' => 3000,
            'title' => 'Perhatian',
            'confirmText' => 'OK'
        ],
        'info' => [
            'icon' => 'info',
            'timer' => 3000,
            'title' => 'Informasi',
            'confirmText' => 'OK'
        ],
        'question' => [
            'icon' => 'question',
            'timer' => 3000,
            'title' => 'Konfirmasi',
            'confirmText' => 'OK'
        ],
    ];

    /**
     * Menampilkan toast notification
     */
    public static function show(?string $title = null, ?string $text = null, string $type = 'success', ?int $timer = null): void
    {
        $type = strtolower($type);
        $config = self::$alertConfig[$type] ?? self::$alertConfig['success'];
        $iconColor = self::$colors[$type] ?? self::$colors['success'];
        $timer = $timer ?? $config['timer'];

        LivewireAlert::title($title)
            ->text($text)
            ->{$type}()
            ->position('top-end')
            ->toast()
            ->timer($timer)
            ->withOptions([
                'width' => '500px',
                'padding' => '14px',
                'background' => '#ffffff',
                'color' => $iconColor,
                'customClass' => [
                    'popup' => 'animate__animated animate__fadeInRight rounded-lg shadow-xl',
                    'title' => 'text-base font-bold',
                    'content' => 'text-sm',
                    'timerProgressBar' => 'progress-bar-' . $type,
                ],
                'showConfirmButton' => false,
                'timerProgressBar' => $timer > 0,
                'didOpen' => "(toast) => {
                    const progressBar = toast.querySelector('.swal2-timer-progress-bar');
                    if (progressBar) {
                        progressBar.style.backgroundColor = '{$iconColor}';
                    }
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }",
            ])
            ->show();
    }

    /**
     * Menampilkan dialog konfirmasi kustom
     *
     * @param string $title Judul dialog
     * @param string $text Text dialog
     * @param string|null $confirmText Text tombol konfirmasi
     * @param string $cancelText Text tombol batal
     * @param string|null $confirmColor Warna tombol konfirmasi
     * @param string|null $cancelColor Warna tombol batal
     * @param string|array $confirmAction Nama action atau array [action, params]
     * @param string $type Tipe konfirmasi (question, warning, dll)
     */
    public static function confirm(
        ?string $title,
        ?string $text,
        ?string $confirmText = null,
        ?string $cancelText = 'Batalkan',
        ?string $confirmColor = null,
        ?string $cancelColor = null,
        $confirmAction = 'save',
        ?string $type = 'question'
    ): void
    {
        $type = strtolower($type);
        $config = self::$alertConfig[$type] ?? self::$alertConfig['question'];

        $confirmText = $confirmText ?? $config['confirmText'];
        $confirmColor = $confirmColor ?? self::$colors['primary'];
        $cancelColor = $cancelColor ?? self::$colors['secondary'];

        $alert = LivewireAlert::title($title)
            ->text($text)
            ->withConfirmButton($confirmText, $confirmColor)
            ->withCancelButton($cancelText)
            ->confirmButtonColor($confirmColor)
            ->denyButtonColor($cancelColor)
            ->withOptions([
                'icon' => $config['icon'],
                'iconColor' => $confirmColor,
                'showClass' => [
                    'popup' => 'animate__animated animate__fadeInDown animate__faster'
                ],
                'hideClass' => [
                    'popup' => 'animate__animated animate__fadeOutUp animate__faster'
                ],
                // 'backdrop' => 'rgba(0,0,0,0.7)',
                'customClass' => [
                    'title' => 'text-xl font-bold text-gray-800',
                    'content' => 'text-gray-600 text-base',
                    'confirmButton' => 'rounded-lg px-6 py-3 font-medium text-white',
                    'cancelButton' => 'rounded-lg px-6 py-3 font-medium text-white',
                    'popup' => 'rounded-xl shadow-2xl border-0'
                ],
                'buttonsStyling' => true,
            ]);

        // Handle action dengan atau tanpa parameter
        if (is_array($confirmAction)) {
            // Format: ['actionName', ['param1', 'param2', ...]]
            if (count($confirmAction) == 2 && is_array($confirmAction[1])) {
                $alert->onConfirm($confirmAction[0], $confirmAction[1]);
            }
            // Format: ['actionName', 'param1']
            else if (count($confirmAction) == 2) {
                $alert->onConfirm($confirmAction[0], [$confirmAction[1]]);
            }
            // Format: ['actionName']
            else {
                $alert->onConfirm($confirmAction[0]);
            }
        } else {
            $alert->onConfirm($confirmAction);
        }

        $alert->show();
    }

    /**
     * Menampilkan dialog konfirmasi hapus data
     *
     * @param string $text Pesan konfirmasi
     * @param mixed $id ID data yang akan dihapus atau parameter lainnya
     * @param string $action Nama action Livewire
     */
    public static function confirmDelete(?string $action = 'delete', ?string $text = 'Apakah Anda yakin ingin menghapus data ini?', $id = null): void
    {
        $confirmAction = $id !== null ? [$action, [$id]] : $action;

        self::confirm(
            'Hapus Data',
            $text,
            'Ya, Hapus',
            'Batalkan',
            self::$colors['error'],
            self::$colors['secondary'],
            $confirmAction,
            'warning'
        );
    }

    /**
     * Menampilkan dialog konfirmasi simpan data
     *
     * @param string $text Pesan konfirmasi
     * @param mixed $params Parameter tambahan (opsional)
     * @param string $action Nama action Livewire
     */
    public static function confirmSave(?string $action = 'save', ?string $text = 'Apakah Anda yakin ingin menyimpan perubahan ini?', $params = null): void
    {
        $confirmAction = $params !== null ? [$action, is_array($params) ? $params : [$params]] : $action;

        self::confirm(
            'Simpan Data',
            $text,
            'Ya, Simpan',
            'Batalkan',
            self::$colors['primary'],
            self::$colors['secondary'],
            $confirmAction,
            'success'
        );
    }

    /**
     * Menampilkan dialog konfirmasi simpan dan keluar
     *
     * @param string $text Pesan konfirmasi
     * @param mixed $params Parameter tambahan (opsional)
     * @param string $action Nama action Livewire
     */
    public static function confirmSaveAndExit(?string $action = 'saveAndExit', ?string $text = 'Simpan perubahan dan keluar?', $params = null): void
    {
        $confirmAction = $params !== null ? [$action, is_array($params) ? $params : [$params]] : $action;

        self::confirm(
            'Simpan & Keluar',
            $text,
            'Ya, Simpan & Keluar',
            'Batalkan',
            self::$colors['success'],
            self::$colors['secondary'],
            $confirmAction,
            'question'
        );
    }

    /**
     * Menampilkan dialog konfirmasi publikasi
     *
     * @param string $text Pesan konfirmasi
     * @param mixed $params Parameter tambahan (opsional)
     * @param string $action Nama action Livewire
     */
    public static function confirmPublish(?string $action = 'publish', ?string $text = 'Apakah Anda yakin ingin mempublikasikan data ini?', $params = null): void
    {
        $confirmAction = $params !== null ? [$action, is_array($params) ? $params : [$params]] : $action;

        self::confirm(
            'Publikasi Data',
            $text,
            'Ya, Publikasikan',
            'Batalkan',
            self::$colors['info'],
            self::$colors['secondary'],
            $confirmAction,
            'info'
        );
    }

    public static function confirmWarning(?string $action = 'warning', ?string $text = 'Apakah Anda yakin ingin melanjutkan?', $params = null): void
    {
        $confirmAction = $params !== null ? [$action, is_array($params) ? $params : [$params]] : $action;

        self::confirm(
            'Peringatan',
            $text,
            'Ya, Lanjutkan',
            'Batalkan',
            self::$colors['warning'],
            self::$colors['secondary'],
            $confirmAction,
            'warning'
        );
    }

    public static function confirmInfo(?string $action = 'info', ?string $text = 'Apakah Anda yakin ingin melihat informasi ini?', $params = null): void
    {
        $confirmAction = $params !== null ? [$action, is_array($params) ? $params : [$params]] : $action;

        self::confirm(
            'Informasi',
            $text,
            'Ya, Lihat',
            'Batalkan',
            self::$colors['info'],
            self::$colors['secondary'],
            $confirmAction,
            'info'
        );

    }

    /**
     * Menampilkan notifikasi sukses
     */
    public static function success(?string $title = null, string $text = 'Operasi berhasil dilakukan', ?int $timer = null): void
    {
        $title = $title ?? self::$alertConfig['success']['title'];
        $timer = $timer ?? self::$alertConfig['success']['timer'];
        self::show($title, $text, 'success', $timer);
    }

    /**
     * Menampilkan notifikasi error
     */
    public static function error(?string $title = null, ?string $text = 'Terjadi kesalahan', ?int $timer = null): void
    {
        $title = $title ?? self::$alertConfig['error']['title'];
        $timer = $timer ?? self::$alertConfig['error']['timer'];
        self::show($title, $text, 'error', $timer);
    }

    /**
     * Menampilkan notifikasi warning
     */
    public static function warning(?string $title = null, ?string $text = 'Harap perhatikan', ?int $timer = null): void
    {
        $title = $title ?? self::$alertConfig['warning']['title'];
        $timer = $timer ?? self::$alertConfig['warning']['timer'];
        self::show($title, $text, 'warning', $timer);
    }

    /**
     * Menampilkan notifikasi info
     */
    public static function info(?string $title = null, ?string $text = 'Informasi penting', ?int $timer = null): void
    {
        $title = $title ?? self::$alertConfig['info']['title'];
        $timer = $timer ?? self::$alertConfig['info']['timer'];
        self::show($title, $text, 'info', $timer);
    }

    /**
     * Menampilkan notifikasi pertanyaan
     */
    public static function question(?string $title = null, ?string $text = 'Apakah Anda yakin?', ?int $timer = null): void
    {
        $title = $title ?? self::$alertConfig['question']['title'];
        $timer = $timer ?? self::$alertConfig['question']['timer'];
        self::show($title, $text, 'question', $timer);
    }
}
