<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $fillable = [
        'title',
        'img_id',
        'description',
    ];
    protected $attributes = ['views' => 0];
    public function recipes()
    {
        return $this->hasMany(Step::class);
    }
}
