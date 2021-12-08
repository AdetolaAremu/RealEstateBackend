<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function type()
    {
        return $this->belongsTo(EstateType::class, 'type');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function images()
    {
        return $this->hasMany(PostImages::class);
    }

    public function ratings()
    {
        return $this->hasMany(PostRating::class);
    }

    public function likes()
    {
        return $this->hasMany(Likes::class);
    }

    public function comment()
    {
        return $this->hasMany(Comment::class);
    }

    public function avgRating()
    {
        return $this->ratings()->selectRaw('avg(rating) as aggregate, post_id')->groupBy('post_id');
    }
}