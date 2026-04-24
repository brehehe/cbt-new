<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;

class BackupController extends Controller
{
    public function download(Request $request)
    {
        // Check if user is authenticated
        if (! Auth::check()) {
            abort(401, 'Unauthorized. Please login first.');
        }

        try {
            $filePath = base64_decode($request->path);

            if (! file_exists($filePath)) {
                abort(404, 'File backup tidak ditemukan.');
            }

            // Security check - make sure file is within storage directory
            $allowedPaths = [
                storage_path('app/public'),
                storage_path('app/backup'),
            ];

            $realPath = realpath($filePath);
            $isAllowed = false;

            foreach ($allowedPaths as $allowedPath) {
                if (strpos($realPath, $allowedPath) === 0) {
                    $isAllowed = true;
                    break;
                }
            }

            if (! $isAllowed) {
                abort(403, 'Akses ditolak. File harus berada di direktori yang diizinkan.');
            }

            // Log download activity
            Log::info('Backup file downloaded: '.basename($filePath).' by user: '.Auth::user()->username);

            return Response::download($filePath);
        } catch (\Exception $e) {
            Log::error('Error downloading backup: '.$e->getMessage());
            abort(500, 'Terjadi kesalahan saat mengunduh file.');
        }
    }
}
