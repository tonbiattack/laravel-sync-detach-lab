<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

final class Role extends Model
{
    /** @var array<int, string> */
    protected $guarded = [];

    public $timestamps = false;
}
