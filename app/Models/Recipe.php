<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $fillable = [
        'title',
        'img_id',
        'description',
    ];
    protected $attributes = ['views' => 0];
    protected $appends =["user"];

    public function getCreatedAtAttribute($value){
        $created = new Carbon($value);
        return $created->diffForHumans();
    }

    public function getUpdatedAtAttribute($value){
        $created = new Carbon($value);
        return $created->diffForHumans();
    }

    public function getUserAttribute(){
        return $this->user()->first()->name;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function steps()
    {
        return $this->hasMany(Step::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }
}
