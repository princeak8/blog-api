<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Resources\ProfileResource;

use App\Services\ProfileService;

class ProfileController extends Controller
{
    private $postService;

    /**
     * Create a new PostController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->profileService = new ProfileService;
    }

    public function get_profile()
    {
        try{
            $profile = $this->profileService->getProfile();
            return response()->json([
                'statusCode' => 200,
                'data' => new ProfileResource($profile)
            ], 200);
        }catch(\Throwable $e){
            \Log::stack(['project'])->info($e->getMessage().' in '.$e->getFile().' at Line '.$e->getLine());
            throw $e;
            return response()->json([
                'statusCode' => 500,
                'message' => 'An error occured while trying to perform this operation, Please try again later or contact support'
            ], 500);
        }
    }
}
