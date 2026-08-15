<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\TeamMember;

final class RoleAdder
{
    public function addRole(TeamMember $member, int $roleId): void
    {
        $member->roles()->sync([$roleId]);
    }
}
