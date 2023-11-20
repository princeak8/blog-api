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
    
    public function __construct(Request $request) {
        $this->middleware('auth');
        // dd($request->bearerToken());
        // try{
        //     Auth::guard('reader')->check();
        // }catch(\Exception $e){
        //     dd($e->getMessage());
        // }
        // dd(Auth::user()->id);
        // if ( Auth::guard('reader')->check())
        // {
        //     dd(auth::user('reader')->id);
        // }else{
        //     dd('not authenticated');
        // }
        $this->commentService = new CommentService;
    }

    public function save(SaveCommentRequest $request)
    {
        $input = $request->all();
        $input['reader_id'] = Auth::user()->id;
        try{
            $comment = $this->commentService->save($input);
            $comments = $this->commentService->getCommentsByPostId($input['post_id']);
                return response()->json([
                    'statusCode' => 200,
                    'message' => 'Saved Successfully',
                    'data' => CommentResource::collection($comments)
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
        $input['reader_id'] = Auth::user()->id;
        try{
            $this->commentService->saveReply($input);
            $replies = $this->commentService->getRepliesByCommentId($input['comment_id']);
                return response()->json([
                    'statusCode' => 200,
                    'message' => 'Saved Successfully',
                    'data' => CommentReplyResource::collection($replies)
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
