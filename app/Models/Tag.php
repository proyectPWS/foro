<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'color'];

    //Relacion Muchos a muchos
    public function posts()
    {
        return $this->belongsToMany(Post::class);
    }
    //SLUG
    public function getRouteKeyName()
    {
        return "slug";
    }
}
