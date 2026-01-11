<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $fillable = ["name", "category", "specs", "status"];

    protected $casts = [
        "specs" => "array", // Automatically handles JSON conversion
    ];

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }
}
