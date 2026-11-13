<?php

namespace App\Services\User;

use App\Models\User;
use App\Models\User\UserCompanyRole;
use App\Models\User\UserDetail;
use App\Models\UsrSecKey;

class DeletedUserRestorer
{
    private const USER_IDENTIFIERS = ['email', 'username', 'phone', 'nim'];

    private const DETAIL_IDENTIFIERS = [
        'student_id',
        'lecturer_id',
        'lecturer_nidn',
        'supervisor_id',
    ];

    public function restoreMatching(
        string $companyId,
        array $userIdentifiers,
        array $detailIdentifiers = []
    ): ?User {
        $userIdentifiers = $this->filterIdentifiers($userIdentifiers, self::USER_IDENTIFIERS);
        $detailIdentifiers = $this->filterIdentifiers($detailIdentifiers, self::DETAIL_IDENTIFIERS);

        if ($userIdentifiers === [] && $detailIdentifiers === []) {
            return null;
        }

        $detailUserIds = collect();

        if ($detailIdentifiers !== []) {
            $detailUserIds = UserDetail::withTrashed()
                ->where(function ($query) use ($detailIdentifiers) {
                    foreach ($detailIdentifiers as $column => $value) {
                        $query->orWhere($column, $value);
                    }
                })
                ->pluck('user_id');
        }

        $user = User::onlyTrashed()
            ->where('company_id', $companyId)
            ->where('type_user', 'employee')
            ->where(function ($query) use ($userIdentifiers, $detailUserIds) {
                foreach ($userIdentifiers as $column => $value) {
                    $query->orWhere($column, $value);
                }

                if ($detailUserIds->isNotEmpty()) {
                    $query->orWhereIn('id', $detailUserIds);
                }
            })
            ->latest('deleted_at')
            ->first();

        if (! $user) {
            return null;
        }

        $user->restore();

        UserDetail::onlyTrashed()
            ->where('user_id', $user->id)
            ->restore();

        UsrSecKey::withoutGlobalScopes()
            ->onlyTrashed()
            ->where('user_id', $user->id)
            ->restore();

        UserCompanyRole::onlyTrashed()
            ->where('user_id', $user->id)
            ->restore();

        return $user->fresh();
    }

    private function filterIdentifiers(array $identifiers, array $allowedColumns): array
    {
        return collect($identifiers)
            ->only($allowedColumns)
            ->map(fn ($value) => is_string($value) ? trim($value) : $value)
            ->filter(fn ($value) => $value !== null && $value !== '')
            ->all();
    }
}
