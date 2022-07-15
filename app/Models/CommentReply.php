<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentReply extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['comment_id','message','reader_id'];

    public function comment()
    {
        return $this->belongsTo('App\Models\Comment');
    }

    public function reader()
    {
        return $this->belongsTo('App\Models\Reader');
    }
}
