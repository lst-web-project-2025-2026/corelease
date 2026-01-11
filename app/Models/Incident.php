<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Incident extends Model
{
    protected $fillable = [
        "user_id",
        "resource_id",
        "reservation_id",
        "description",
        "status",
    ];
}
