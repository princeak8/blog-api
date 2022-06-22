<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['title','preview','content','category_id','user_id','published'];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }

    public function coverImage()
    {
        return $this->belongsTo('App\Models\File', 'cover_photo', 'id');
    }

    public function tags()
    {
        return $this->hasMany('App\Models\PostTag');
    }

    public static function boot ()
    {
        parent::boot();

        self::deleting(function (Post $post) {

            if($post->coverImage) $post->coverImage->delete();
            
            foreach ($post->postInterests as $interest)
            {
                $interest->delete();
            }
        });
    }
}
