<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use App\Http\Requests\SavePostRequest;
use App\Http\Requests\UpdatePostRequest;

use Log;

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
        $this->middleware('auth:api');
        // $this->middleware('validatedUser');
        $this->postService = new PostService;
        // $this->commentService = $_commentService;
    }

    public function save(SavePostRequest $request)
    {
        $input = $request->all();
        $input['user_id'] = auth::user()->id;
        try{
            $post = $this->postService->save($input);
            return response()->json([
                'statusCode' => 200,
                'message' => 'Saved Successfully',
                // 'data' => new PostResource($post)
            ], 200);
        }catch(\Exception $e){
            \Log::stack(['project'])->info($e->getMessage().' in '.$e->getFile().' at Line '.$e->getLine());
            return response()->json([
                'statusCode' => 500,
                'message' => 'An error occured while trying to perform this operation, Please try again later or contact support'
            ], 500);
        }
    }

    public function update(UpdatePostRequest $request)
    {
        $input = $request->all();
        $input['user_id'] = 1;
        try{
            $post = $this->postService->update($input);
            return response()->json([
                'statusCode' => 200,
                'message' => 'Updated Successfully',
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

    public function public_posts()
    {
        $user_id = 1;
        try{
            $posts = $this->postService->getPublicPosts($user_id);
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

    public function unpublished_posts()
    {
        $user_id = 1;
        try{
            $posts = $this->postService->getUnpublishedPosts($user_id);
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

    public function hidden_posts()
    {
        $user_id = 1;
        try{
            $posts = $this->postService->getHiddenPosts($user_id);
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
}
