<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable =[
        'slug', 'category'
    ];

    public function post()
    {
        return $this->belongsToMany(Post::class);
    }
}
