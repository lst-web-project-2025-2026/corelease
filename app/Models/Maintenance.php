<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    protected $fillable = [
        "resource_id",
        "start_date",
        "end_date",
        "description",
    ];
    protected $casts = ["start_date" => "datetime", "end_date" => "datetime"];
}
