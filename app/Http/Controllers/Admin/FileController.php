<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Http\Requests\saveFileRequest;

use App\Services\FileService;

use App\Http\Resources\FileResource;

class FileController extends Controller
{
    private $fileService;

    /**
     * Create a new FileController instance.
     *
     * @return void
     */
    
    public function __construct() {
        $this->middleware('auth:api');
        $this->fileService = new FileService;
    }

    public function save(saveFileRequest $request)
    {
        try{
            $res = $this->fileService->save($request, auth::user()->domain, auth::user()->id);
            if($res['success']) {
                return response()->json([
                    'statusCode' => 200,
                    'data' => new FileResource($res['file']),
                    'message' => 'Saved Successfully',
                ], 200);
            }else{
                return response()->json([
                    'statusCode' => 200,
                    'message' => $res['message'],
                ], 200);
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
