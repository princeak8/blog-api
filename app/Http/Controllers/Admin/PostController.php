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
use App\Services\CommentService;

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
        $this->commentService = new CommentService;
    }

    public function save(SavePostRequest $request)
    {
        $input = $request->all();
        $input['user_id'] = auth::user()->id;
        try{
            $previewArr = explode(' ', $input['preview']);
            if(count($previewArr) <= 25) { 
                $post = $this->postService->save($input);
                return response()->json([
                    'statusCode' => 200,
                    'message' => 'Saved Successfully',
                    'data' => new PostResource($post)
                ], 200);
            }else{
                return response()->json([
                    'statusCode' => 402,
                    'message' => 'Preview word count should not be more than 25words',
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

    public function update(UpdatePostRequest $request)
    {
        try{
            $input = $request->all();
            //$input['user_id'] = 1;
            $post = $this->postService->getPost($input['post_id']);
            if($post) {
                if(isset($input['preview'])) {
                    $previewArr = explode(' ', $input['preview']);
                    if(count($previewArr) > 25) { 
                        return response()->json([
                            'statusCode' => 402,
                            'message' => 'Preview word count should not be more than 25words',
                        ], 402);
                    }
                }
                $post = $this->postService->update($post, $input);
                return response()->json([
                    'statusCode' => 200,
                    'message' => 'Updated Successfully',
                    'data' => new PostResource($post)
                ], 200);
            }else{
                return response()->json([
                    'statusCode' => 404,
                    'message' => 'Post not found'
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

    public function delete($id)
    {
        try{
            $post = $this->postService->getPost($id);
            if($post) {
                $this->postService->delete($post);
                return response()->json([
                    'statusCode' => 200,
                    'message' => 'Deleted Successfully'
                ], 200);
            }else{
                return response()->json([
                    'statusCode' => 404,
                    'message' => 'Post not found'
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

    public function toggle_publish($id)
	{
        try{
            $post = $this->postService->getPost($id);
            if($post) {
                $post = $this->postService->togglePublish($post);
                return response()->json([
                    'statusCode' => 200,
                    'data' => new PostResource($post),
                    'message' => 'Successful'
                ], 200);
            }else{
                return response()->json([
                    'statusCode' => 404,
                    'message' => 'Post not found'
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

	public function toggle_visibility($id)
	{
		try{
            $post = $this->postService->getPost($id);
            if($post) {
                $post = $this->postService->toggleVisible($post);
                return response()->json([
                    'statusCode' => 200,
                    'data' => new PostResource($post),
                    'message' => 'Successful'
                ], 200);
            }else{
                return response()->json([
                    'statusCode' => 404,
                    'message' => 'Post not found'
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
    
    public function post($id)
    {
        try{
            $post = $this->postService->getPost($id);
            if($post) {
                return response()->json([
                    'statusCode' => 200,
                    'data' => new PostResource($post),
                    'message' => 'Successful'
                ], 200);
            }else{
                return response()->json([
                    'statusCode' => 404,
                    'message' => 'Post not found'
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

    public function all()
    {
        //$user_id = 1;
        try{
            $posts = $this->postService->getPosts();
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

    public function published_posts()
    {
        $user_id = 1;
        try{
            $posts = $this->postService->getpublishedPosts($user_id);
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
