<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Step extends Model
{
    protected $fillable = [
        'description',
        'number'
    ];
    public function recipe()
    {
        return $this->belongsTo(Recipe::class);
    }
}
