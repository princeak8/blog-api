<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Comment;
use App\Models\PostTag;
use App\Models\Tag;
use App\Models\Category;
use App\Models\File;

class PostService 
{

    public function getPost($id)
    {
        return Post::find($id);
    }

    public function posts()
    {
        return Post::where('published', 1)->where('visible', 1)->get();
    }

    public function postsCount($user_id)
    {
        return Post::where('published', 1)->where('visible', 1)->count();
    }

    public function getPosts($user_id)
    {
        return Post::where('user_id', $user_id)->get();
    }

    public function getPostsCount($user_id)
    {
        return Post::where('user_id', $user_id)->count();
    }

    public function getPublishedPosts($user_id)
    {
        return Post::where('user_id', $user_id)->where('published', 1)->get();
    }

    public function getPublishedPostsCount($user_id)
    {
        return Post::where('user_id', $user_id)->where('published', 1)->count();
    }

    public function getPublicPosts($user_id)
    {
        return Post::where('user_id', $user_id)->where('published', 1)->where('visible', 1)->get();
    }

    public function getPublicPostsCount($user_id)
    {
        return Post::where('user_id', $user_id)->where('published', 1)->where('visible', 1)->count();
    }

    public function getUnpublishedPosts($user_id)
    {
        return Post::where('user_id', $user_id)->where('published', 0)->get();
    }

    public function getUnpublishedPostsCount($user_id)
    {
        return Post::where('user_id', $user_id)->where('published', 0)->count();
    }

    public function getHiddenPosts($user_id)
    {
        return Post::where('user_id', $user_id)->where('hidden', 0)->get();
    }

    public function getHiddenPostsCount($user_id)
    {
        return Post::where('user_id', $user_id)->where('hidden', 0)->count();
    }

    public function save($data)
    {
        $post = Post::create($data);
        // $post->user_id = $data['user_id'];
        // $post->title = $data['title'];
        // $post->content = $data['content'];
        // if(isset($data['category_id'])) $post->category_id = $data['category_id'];
        // if(isset($data['cover_photo_id'])) $post->cover_photo = $data['cover_photo_id'];
        // if(isset($data['publish'])) $post->publish = 1;
        // $post->save();
        if(isset($data['tags_id']) && count($data['tags_id']) > 0) {
            foreach($data['tags_id'] as $tag_id) {
                $postTag = new PostTag;
                $postTag->post_id = $post->id;
                $postTag->tag_id = $tag_id;
                $postTag->save();
            }
        }
        return $post;
    }

    public function update($post, $data)
    {
        if(isset($data['title'])) $post->title = $data['title'];
        if(isset($data['content'])) $post->content = $data['content'];
        if(isset($data['category_id'])) $post->category_id = $data['category_id'];
        if(isset($data['cover_photo_id'])) $post->cover_photo = $data['cover_photo_id'];
        $post->update();
        
        if(isset($data['tags_id']) && count($data['tags_id']) > 0) {
            $tags_id = [];
            if($post->tags->count() > 0) {
                foreach($post->tags as $postTag) {
                    if(!in_array($postTag->tag_id, $data['tags_id'])) {
                        $postTag->delete();
                    }else{
                        $tags_id[] = $postTag->tag_id;
                    }
                }
            }
            foreach($data['tags_id'] as $tag_id) {
                if(!in_array($tag_id, $tags_id)) {
                    $postTag = new PostTag;
                    $postTag->post_id = $post->id;
                    $postTag->tag_id = $tag_id;
                    $postTag->save();
                }
            }
        }
        return $post;
    }

    public function delete($post)
    {
        $post->delete();
    }

    public function togglePublish($post)
    {
        $post->publish ^= 1;
        $post->update();
        return $post;
    }

    public function toggleVisible($post)
    {
        $post->visible ^= 1;
        $post->update();
        return $post;
    }

    public function increaseViewCount($post)
    {
        $post->views += 1;
        $post->update();
        return $post;
    }

}



?>