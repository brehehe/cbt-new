<?php

namespace App\Livewire\Admin\ChangePassword;

use App\Helpers\AlertHelper;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class AdminChangePasswordIndex extends Component
{
    public $currentPassword;
    public $newPassword;
    public $confirmPassword;

    protected $rules = [
        'currentPassword' => 'required|string',
        'newPassword'     => 'required|string|min:8',
        'confirmPassword' => 'required|string|min:8|same:newPassword',
    ];

    protected $messages = [
        'currentPassword.required' => 'Kata sandi saat ini wajib diisi.',
        'currentPassword.string'   => 'Kata sandi saat ini harus berupa teks.',

        'newPassword.required'     => 'Kata sandi baru wajib diisi.',
        'newPassword.string'       => 'Kata sandi baru harus berupa teks.',
        'newPassword.min'          => 'Kata sandi baru minimal terdiri dari 8 karakter.',
        'newPassword.confirmed'    => 'Kata sandi baru dan konfirmasi tidak sama.',

        'confirmPassword.required' => 'Konfirmasi kata sandi wajib diisi.',
        'confirmPassword.string'   => 'Konfirmasi kata sandi harus berupa teks.',
        'confirmPassword.min'      => 'Konfirmasi kata sandi minimal terdiri dari 8 karakter.',
    ];

    public function changePassword()
    {
        $this->validate();

        try {
            DB::beginTransaction();

            $user = auth()->user();

            if (!Hash::check($this->currentPassword, $user->password)) {
                return AlertHelper::error('Error', 'Password Yang Anda Masukan Salah.');
            }

            $user->password = Hash::make($this->newPassword);
            $user->save();

            UsrSecKey::updateOrCreate(
                ['user_id' => $user->id, 'company_id' => $user->company_id],
                ['sec_val' => encrypt($this->newPassword)]
            );

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal mengubah kata sandi. Silakan coba lagi nanti: ' . $e->getMessage());
            return AlertHelper::error('Error', 'Gagal mengubah kata sandi. Silakan coba lagi nanti.');
        }
        return AlertHelper::success('Berhasil', 'Kata sandi berhasil diubah.');
    }

    public function testPrint()
    {
        $printerPath = "COM5"; // Windows (cek di Device Manager)
        // $printerPath = "/dev/rfcomm0"; // Linux

        $content = "Halo ini struk dari Laravel!\nTotal: Rp 50.000\n\n";

        // ESC/POS commands
        $esc = "\x1B";
        $cut = $esc . "d" . "\x03"; // feed 3 line + cut

        $fp = fopen($printerPath, "w");
        if ($fp) {
            fwrite($fp, $content);
            fwrite($fp, $cut);
            fclose($fp);
            return "✅ Struk berhasil dicetak";
        } else {
            return "❌ Gagal membuka printer $printerPath";
        }
    }

    public function render()
    {
        return view('livewire.admin.change-password.admin-change-password-index')
            ->extends('layout.app')
            ->section('content');
    }
}
