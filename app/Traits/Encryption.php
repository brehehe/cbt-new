<?php

namespace App\Traits;

use Illuminate\Support\Facades\Crypt;

trait Encryption
{
    //

    public static function encrypted($string)
    {
        if ($string == null) {
            return null;
        }

        return Crypt::encryptString($string);
    }

    public static function decrypted($string)
    {
        if ($string == null) {
            return null;
        }

        return Crypt::decryptString($string);
    }
}
