<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

use App\Http\Resources\TagResource;

use App\Services\TagService;

class TagController extends Controller
{

    private $tagService;

    /**
     * Create a new TagController instance.
     *
     * @return void
     */
    
    public function __construct() {
        $this->middleware('auth:api');
        $this->tagService = new TagService;
    }

    public function tags()
    {
        try{
            $tags = $this->tagService->getTags();
            $tags = ($tags->count() > 0) ? TagResource::collection($tags) : [];
            return response()->json([
                'statusCode' => 200,
                'data' => $tags
            ], 200);
        }catch(\Exception $e){
            \Log::stack(['project'])->info($e->getMessage().' in '.$e->getFile().' at Line '.$e->getLine());
            return response()->json([
                'statusCode' => 500,
                'message' => 'An error occured while trying to perform this operation, Please try again later or contact support'
            ], 500);
        }
    }

    public function save(Request $request)
    {
        try{ 
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|min:1|unique:tags'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $tag = $this->tagService->save($request->all());
            return response()->json([
                'statusCode' => 200,
                'data' => $tag,
                'message' => 'Saved Successfully'
            ], 200);
        }catch(\Exception $e){
            \Log::stack(['project'])->info($e->getMessage().' in '.$e->getFile().' at Line '.$e->getLine());
            return response()->json([
                'statusCode' => 500,
                'message' => 'An error occured while trying to perform this operation, Please try again later or contact support'
            ], 500);
        }
    }

    public function update(Request $request)
    {
        try{ 
            $validator = Validator::make($request->all(), [
                'tag_id' => 'required|integer',
                'name' => 'required|string|min:1|unique:tags'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }
            $tag = $this->tagService->getTag($request->input('tag_id'));
            if($tag) {
                $tag = $this->tagService->update($tag, $request->all());
                return response()->json([
                    'statusCode' => 200,
                    'data' => $tag,
                    'message' => 'updated Successfully'
                ], 200);
            }else{
                return response()->json([
                    'statusCode' => 404,
                    'message' => 'Tag does not exist'
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
