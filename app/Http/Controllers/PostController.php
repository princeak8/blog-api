<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\SavePostRequest;
use App\Http\Requests\UpdatePostRequest;

use App\Http\Resources\PostResource;

use App\Services\PostService;

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
        // $this->commentService = $_commentService;
    }

    public function posts()
    {
        try{
            $posts = $this->postService->posts();
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

    public function post($post_id)
    {
        try{
            $post = $this->postService->getPost($post_id);
            return response()->json([
                'statusCode' => 200,
                'data' => new PostResource($post)
            ], 200);
        }catch(\Exception $e){
            \Log::stack(['project'])->info($e->getMessage().' in '.$e->getFile().' at Line '.$e->getLine());
            return response()->json([
                'statusCode' => 500,
                'message' => 'An error occured while trying to perform this operation, Please try again later or contact support'
            ], 500);
        }
    }
}
