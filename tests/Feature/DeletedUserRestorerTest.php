<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\User\UserCompanyRole;
use App\Models\User\UserDetail;
use App\Models\UsrSecKey;
use App\Services\User\DeletedUserRestorer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class DeletedUserRestorerTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_restores_a_deleted_user_and_its_related_records(): void
    {
        $companyId = (string) Str::uuid();
        $user = User::withoutEvents(fn () => User::factory()->create([
            'company_id' => $companyId,
            'email' => 'restored@example.test',
            'username' => 'restored-user',
            'type_user' => 'employee',
        ]));

        $detail = UserDetail::withoutEvents(fn () => UserDetail::create([
            'user_id' => $user->id,
            'lecturer_id' => 'LECTURER-001',
        ]));
        $securityKey = UsrSecKey::withoutEvents(fn () => UsrSecKey::create([
            'company_id' => $companyId,
            'user_id' => $user->id,
            'sec_val' => encrypt('password123'),
        ]));
        $companyRole = UserCompanyRole::withoutEvents(fn () => UserCompanyRole::create([
            'company_id' => $companyId,
            'user_id' => $user->id,
            'role_id' => (string) Str::uuid(),
            'role_company_id' => (string) Str::uuid(),
        ]));

        UserDetail::withoutEvents(fn () => $detail->delete());
        UsrSecKey::withoutEvents(fn () => $securityKey->delete());
        UserCompanyRole::withoutEvents(fn () => $companyRole->delete());
        User::withoutEvents(fn () => $user->delete());

        $restored = app(DeletedUserRestorer::class)->restoreMatching(
            $companyId,
            ['email' => 'restored@example.test'],
            ['lecturer_id' => 'LECTURER-001']
        );

        $this->assertSame($user->id, $restored?->id);
        $this->assertNotNull(User::find($user->id));
        $this->assertNotNull(UserDetail::find($detail->id));
        $this->assertNotNull(UsrSecKey::withoutGlobalScopes()->find($securityKey->id));
        $this->assertNotNull(UserCompanyRole::find($companyRole->id));
    }
}
