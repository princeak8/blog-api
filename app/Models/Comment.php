<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['post_id','message','reader_id'];

    public function post()
    {
        return $this->belongsTo('App\Models\Post');
    }

    public function reader()
    {
        return $this->belongsTo('App\Models\Reader');
    }

    public function replies()
    {
        return $this->hasMany('App\Models\CommentReply')->orderBy('created_at', 'desc');
    }

    public static function boot ()
    {
        parent::boot();

        self::deleting(function (Comment $comment) {

            if($comment->replies && $comment->replies->count() > 0) {
                foreach ($comment->replies as $reply)
                {
                    $reply->delete();
                }
            }
        });
    }
}
