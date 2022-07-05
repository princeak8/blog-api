<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Http\Requests\SaveCommentRequest;

use App\Services\PostService;
use App\Services\CommentService;

use App\Http\Resources\CommentResource;

class CommentController extends Controller
{
    private $postService;
    private $commentService;

    /**
     * Create a new PostController instance.
     *
     * @return void
     */
    
    public function __construct() {
        $this->middleware('blogAuth');
        $this->postService = new PostService;
        $this->commentService = new CommentService;
    }

    public function save(SaveCommentRequest $request)
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
}
