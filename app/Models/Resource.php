<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = ["name", "category", "specs", "status"];

    protected $casts = [
        "specs" => "array", // Automatically handles JSON conversion
    ];

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }
}
