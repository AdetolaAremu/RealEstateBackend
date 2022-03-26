<?php

namespace App\Models;

use App\Traits\Uuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
class Post extends Model
{
    use HasFactory, HasSlug, Uuids;

    protected $guarded = ['id'];

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

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

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}