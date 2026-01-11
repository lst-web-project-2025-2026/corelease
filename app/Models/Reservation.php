<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        "user_id",
        "resource_id",
        "start_date",
        "end_date",
        "user_justification",
        "manager_justification",
        "configuration",
        "status",
    ];

    protected $casts = [
        "configuration" => "array",
        "start_date" => "datetime",
        "end_date" => "datetime",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}
