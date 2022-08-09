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
        return Comment::where('post_id', $post_id)->orderBy('created_at', 'desc')->get();
    }

    public function commentsCount($post_id)
    {
        return Comment::where('comment_id', $post_id)->count();
    }

    public function getcomments($user_id)
    {
        return Comment::where('reader_id', $user_id)->orderBy('created_at', 'desc')->get();
    }

    public function save($data)
    {
        $comment = Comment::create($data);
        return $comment;
    }

    public function delete($comment)
    {
        $comment->delete();
    }


    public function getReply($id)
    {
        return CommentReply::find($id);
    }

    public function getRepliesByCommentId($comment_id)
    {
        return CommentReply::where('comment_id', $comment_id)->orderBy('created_at', 'desc')->get();
    }

    public function repliesCount($comment_id)
    {
        return CommentReply::where('comment_id', $comment_id)->count();
    }

    public function getReplies($user_id)
    {
        return CommentReply::where('reader_id', $user_id)->orderBy('created_at', 'desc')->get();
    }

    public function saveReply($data)
    {
        $reply = CommentReply::create($data);
        return $reply;
    }

    public function deleteReply($reply)
    {
        $reply->delete();
    }

}



?>