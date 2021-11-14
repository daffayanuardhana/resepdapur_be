<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Like extends Model
{
    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }
}
