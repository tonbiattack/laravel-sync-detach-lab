<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

final class TeamMember extends Model
{
    /** @var array<int, string> */
    protected $guarded = [];

    public $timestamps = false;

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'member_role', 'member_id', 'role_id');
    }
}
