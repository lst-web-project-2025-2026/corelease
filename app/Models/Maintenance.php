<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Maintenance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "resource_id",
        "user_id",
        "start_date",
        "end_date",
        "description",
    ];
    protected $casts = ["start_date" => "datetime", "end_date" => "datetime"];

    // Dynamic Status Logic
    public function getStatusAttribute()
    {
        $now = now();
        if ($now < $this->start_date) {
            return "Scheduled";
        }
        if ($now->between($this->start_date, $this->end_date)) {
            return "In Progress";
        }
        return "Completed";
    }
}
