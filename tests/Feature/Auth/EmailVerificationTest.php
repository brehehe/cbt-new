<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmailVerificationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Rute verifikasi email standar tidak digunakan di aplikasi ini.
     * Fitur verifikasi dikelola secara custom — test ini dilewati.
     */
    public function test_email_verification_screen_can_be_rendered(): void
    {
        $this->markTestSkipped('Route /verify-email tidak digunakan di aplikasi ini.');
    }

    public function test_email_can_be_verified(): void
    {
        $this->markTestSkipped('Route verification.verify tidak terdaftar di aplikasi ini.');
    }

    public function test_email_is_not_verified_with_invalid_hash(): void
    {
        $this->markTestSkipped('Route verification.verify tidak terdaftar di aplikasi ini.');
    }
}
