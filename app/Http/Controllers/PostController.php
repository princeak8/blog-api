<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\SavePostRequest;
use App\Http\Requests\UpdatePostRequest;

use App\Http\Resources\PostResource;

use App\Services\PostService;
use App\Services\CommentService;
use App\Models\Post;

class PostController extends Controller
{
    
    private $postService;
    private $commentService;

    /**
     * Create a new PostController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->postService = new PostService;
        $this->commentService = new CommentService;
    }

    public function posts($page=1)
    {
        try{
            if(is_int($page)) {
                dd($page);
                $posts = $this->postService->paginatedPosts($page);
                $count = $this->postService->postsCount();
                return response()->json([
                    'statusCode' => 200,
                    'data' => PostResource::collection($posts),
                    'meta' => [
                        'totalPosts' => $count,
                        'perPage' => Post::$per_page
                    ]
                ], 200);
            }else{
                return response()->json([
                    'statusCode' => 402,
                    'message' => "Invalid page Number"
                ], 402);
            }
        }catch(\Exception $e){
            \Log::stack(['project'])->info($e->getMessage().' in '.$e->getFile().' at Line '.$e->getLine());
            return response()->json([
                'statusCode' => 500,
                'message' => 'An error occured while trying to perform this operation, Please try again later or contact support'
            ], 500);
        }
    }

    public function latestPosts()
    {
        try{
            $posts = $this->postService->latestPosts();
            return response()->json([
                'statusCode' => 200,
                'data' => PostResource::collection($posts)
            ], 200);
        }catch(\Exception $e){
            \Log::stack(['project'])->info($e->getMessage().' in '.$e->getFile().' at Line '.$e->getLine());
            return response()->json([
                'statusCode' => 500,
                'message' => 'An error occured while trying to perform this operation, Please try again later or contact support'
            ], 500);
        }
    }

    public function post($title)
    {
        try{
            $post = $this->postService->getPostByTitle($title);

            //dd($post);
            return response()->json([
                'statusCode' => 200,
                'data' => ($post) ? new PostResource($post) : []
            ], 200);
        }catch(\Exception $e){
            \Log::stack(['project'])->info($e->getMessage().' in '.$e->getFile().' at Line '.$e->getLine());
            //dd($e->getMessage().' in '.$e->getFile().' at Line '.$e->getLine());
            return response()->json([
                'statusCode' => 500,
                'message' => 'An error occured while trying to perform this operation, Please try again later or contact support'
            ], 500);
        }
    }

    public function increase_view_count($post_id)
    {
        try{
            $post = $this->postService->getPost($post_id);
            if($post) {
                $post = $this->postService->increaseViewCount($post);
                return response()->json([
                    'statusCode' => 200,
                    'data' => new PostResource($post),
                    'message' => "Successful"
                ], 200);
            }else{
                return response()->json([
                    'statusCode' => 404,
                    'message' => "Post not found"
                ], 404);
            }
        }catch(\Exception $e){
            \Log::stack(['project'])->info($e->getMessage().' in '.$e->getFile().' at Line '.$e->getLine());
            return response()->json([
                'statusCode' => 500,
                'message' => 'An error occured while trying to perform this operation, Please try again later or contact support'
            ], 500);
        }
    }
}
