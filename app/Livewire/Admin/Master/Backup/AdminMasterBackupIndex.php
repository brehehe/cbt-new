<?php

namespace App\Livewire\Admin\Master\Backup;

use App\Helpers\AlertHelper;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class AdminMasterBackupIndex extends Component
{
    public function mount()
    {
        // Add any initial logic if needed
    }

    public function backupDatabase()
    {
        // Check if user is 'procbt'
        if (auth()->user()->username !== 'procbt') {
            return AlertHelper::error('Akses Ditolak', 'Hanya user "procbt" yang dapat melakukan backup database.');
        }

        try {
            // Jalankan artisan command untuk backup database only
            Artisan::call('backup:run', [
                '--only-db' => true,
                '--disable-notifications' => true,
            ]);

            Log::info('Database backup berhasil dilakukan oleh user: '.auth()->user()->username);

            $backupName = config('backup.backup.name');

            return AlertHelper::success('Berhasil', "Backup database berhasil dibuat. File tersimpan di storage/app/public/{$backupName}/");
        } catch (\Exception $e) {
            Log::error('Error saat backup database: '.$e->getMessage());

            return AlertHelper::error('Gagal', 'Terjadi kesalahan saat membuat backup database: '.$e->getMessage());
        }
    }

    public function backupStorage()
    {
        // Check if user is 'procbt'
        if (auth()->user()->username !== 'procbt') {
            return AlertHelper::error('Akses Ditolak', 'Hanya user "procbt" yang dapat melakukan backup storage.');
        }

        try {
            // Jalankan artisan command untuk backup files only
            Artisan::call('backup:run', [
                '--only-files' => true,
                '--disable-notifications' => true,
            ]);

            Log::info('Storage backup berhasil dilakukan oleh user: '.auth()->user()->username);

            $backupName = config('backup.backup.name');

            return AlertHelper::success('Berhasil', "Backup storage berhasil dibuat. File tersimpan di storage/app/public/{$backupName}/");
        } catch (\Exception $e) {
            Log::error('Error saat backup storage: '.$e->getMessage());

            return AlertHelper::error('Gagal', 'Terjadi kesalahan saat membuat backup storage: '.$e->getMessage());
        }
    }

    public function backupFull()
    {
        // Check if user is 'procbt'
        if (auth()->user()->username !== 'procbt') {
            return AlertHelper::error('Akses Ditolak', 'Hanya user "procbt" yang dapat melakukan backup lengkap.');
        }

        try {
            // Jalankan artisan command untuk backup full (database + files)
            Artisan::call('backup:run', [
                '--disable-notifications' => true,
            ]);

            Log::info('Full backup berhasil dilakukan oleh user: '.auth()->user()->username);

            $backupName = config('backup.backup.name');

            return AlertHelper::success('Berhasil', "Backup lengkap berhasil dibuat. File tersimpan di storage/app/public/{$backupName}/");
        } catch (\Exception $e) {
            Log::error('Error saat full backup: '.$e->getMessage());

            return AlertHelper::error('Gagal', 'Terjadi kesalahan saat membuat backup lengkap: '.$e->getMessage());
        }
    }

    public function getBackupFiles()
    {
        try {
            // Lokasi backup sesuai config backup.php (disk public)
            $backupPath = storage_path('app/public');

            if (! file_exists($backupPath)) {
                return [];
            }

            $files = [];

            // Cari semua folder di dalam public storage
            $directories = glob($backupPath.'/*', GLOB_ONLYDIR);

            foreach ($directories as $dir) {
                // Cari semua file .zip di folder tersebut
                $zipFiles = glob($dir.'/*.zip');

                foreach ($zipFiles as $file) {
                    $filename = basename($file);
                    $folderName = basename($dir);

                    // Tentukan tipe backup berdasarkan nama file atau folder
                    $type = 'Full Backup';

                    // Cek dari nama file
                    if (strpos($filename, 'db-only') !== false ||
                        strpos($filename, 'database-only') !== false ||
                        strpos($filename, '-db.zip') !== false) {
                        $type = 'Database';
                    } elseif (strpos($filename, 'files-only') !== false ||
                              strpos($filename, 'storage-only') !== false ||
                              strpos($filename, '-files.zip') !== false) {
                        $type = 'Storage';
                    }

                    $files[] = [
                        'name' => $filename,
                        'path' => $file,
                        'folder' => $folderName,
                        'size' => $this->formatBytes(filesize($file)),
                        'date' => date('d-m-Y H:i:s', filemtime($file)),
                        'timestamp' => filemtime($file),
                        'type' => $type,
                    ];
                }
            }

            // Sort by timestamp descending (newest first)
            usort($files, function ($a, $b) {
                return $b['timestamp'] - $a['timestamp'];
            });

            return $files;
        } catch (\Exception $e) {
            Log::error('Error getting backup files: '.$e->getMessage());

            return [];
        }
    }

    public function deleteBackup($filePath)
    {
        // Check if user is 'procbt'
        if (auth()->user()->username !== 'procbt') {
            return AlertHelper::error('Akses Ditolak', 'Hanya user "procbt" yang dapat menghapus file backup.');
        }

        try {
            if (! file_exists($filePath)) {
                return AlertHelper::error('Gagal', 'File backup tidak ditemukan.');
            }

            unlink($filePath);
            Log::info('Backup file deleted: '.$filePath.' by user: '.auth()->user()->username);

            return AlertHelper::success('Berhasil', 'File backup berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Error deleting backup: '.$e->getMessage());

            return AlertHelper::error('Gagal', 'Terjadi kesalahan saat menghapus backup.');
        }
    }

    public function deleteAllBackups()
    {
        // Check if user is 'procbt'
        if (auth()->user()->username !== 'procbt') {
            return AlertHelper::error('Akses Ditolak', 'Hanya user "procbt" yang dapat menghapus file backup.');
        }

        try {
            $backupPath = storage_path('app/public');

            if (! file_exists($backupPath)) {
                return AlertHelper::error('Gagal', 'Direktori backup tidak ditemukan.');
            }

            // Dapatkan folder backup name (contoh: pro-cbt)
            $backupName = config('backup.backup.name');
            $specificBackupDir = $backupPath.'/'.$backupName;

            if (file_exists($specificBackupDir)) {
                // Delete the specific backup directory entirely, and recreate it
                File::deleteDirectory($specificBackupDir);
                File::makeDirectory($specificBackupDir, 0755, true);

                Log::info('All backup files deleted by user: '.auth()->user()->username);

                return AlertHelper::success('Berhasil', 'Semua file backup berhasil dihapus.');
            } else {
                return AlertHelper::success('Berhasil', 'Tidak ada file backup yang perlu dihapus.');
            }

        } catch (\Exception $e) {
            Log::error('Error deleting all backups: '.$e->getMessage());

            return AlertHelper::error('Gagal', 'Terjadi kesalahan saat menghapus semua backup.');
        }
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $precision).' '.$units[$i];
    }

    public function render()
    {
        return view('livewire.admin.master.backup.admin-master-backup-index')
            ->extends('layout.app')
            ->section('content');
    }
}
