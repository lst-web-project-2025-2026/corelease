<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;

class Catalog
{
    public static function allresources()
    {
        return DB::select("SELECT name, category, specs, status, created_at, updated_at FROM resources ");
    }
}