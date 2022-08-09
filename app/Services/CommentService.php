<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Comment;
use App\Models\CommentReply;

class CommentService 
{

    public function getComment($id)
    {
        return Comment::find($id);
    }

    public function getCommentsByPostId($post_id)
    {
        return Comment::where('post_id', $post_id)->get();
    }

    public function commentsCount($post_id)
    {
        return Comment::where('comment_id', $post_id)->count();
    }

    public function getcomments($user_id)
    {
        return Comment::where('user_id', $user_id)->get();
    }

    public function save($data)
    {
        $comment = Comment::create($data);
        return $comment;
    }

    public function saveReply($data)
    {
        $reply = CommentReply::create($data);
        return $reply;
    }

    public function delete($comment)
    {
        $comment->delete();
    }

}



?>