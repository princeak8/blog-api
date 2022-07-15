<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use App\Http\Requests\SaveCommentRequest;
use App\Http\Requests\CommentReplyRequest;

use App\Http\Resources\CommentResource;
use App\Http\Resources\CommentReplyResource;

use App\Services\CommentService;

class CommentController extends Controller
{
    private $commentService;

    /**
     * Create a new CommentController instance.
     *
     * @return void
     */
    
    public function __construct() {
        $this->middleware('auth:reader');
        $this->commentService = new CommentService;
    }

    public function save(SaveCommentRequest $request)
    {
        $input = $request->all();
        $input['reader_id'] = auth::user('reader')->id;
        try{
            $comment = $this->commentService->save($input);
                return response()->json([
                    'statusCode' => 200,
                    'message' => 'Saved Successfully',
                    'data' => new CommentResource($comment)
                ], 200);
        }catch(\Exception $e){
            \Log::stack(['project'])->info($e->getMessage().' in '.$e->getFile().' at Line '.$e->getLine());
            return response()->json([
                'statusCode' => 500,
                'message' => 'An error occured while trying to perform this operation, Please try again later or contact support'
            ], 500);
        }
    }

    public function saveReply(CommentReplyRequest $request)
    {
        $input = $request->all();
        $input['reader_id'] = auth::user('reader')->id;
        try{
            $reply = $this->commentService->saveReply($input);
                return response()->json([
                    'statusCode' => 200,
                    'message' => 'Saved Successfully',
                    'data' => new CommentReplyResource($reply)
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
