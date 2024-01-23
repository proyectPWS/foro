<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'slug'];
    use HasFactory;
    //Relacion uno a muchos
    public function posts()
    {
        $this->hasMany(Post::class);
    }
    //SLUG
    public function getRouteKeyName()
    {
        return "slug";
    }
}
