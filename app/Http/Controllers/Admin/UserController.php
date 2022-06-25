<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Http\Requests\CreateProfileRequest;

use App\Services\ProfileService;

use App\Http\Resources\ProfileResource;

class UserController extends Controller
{
    private $profileService;

    public function __construct() {
        $this->middleware('auth:api');
        $this->profileService = new ProfileService;
    }

    public function create_profile(CreateProfileRequest $request)
    {
        try{
            $post = $request->all();
            $post['user_id'] = auth::user()->id;
            $post['email'] = auth::user()->email;
            $profile = $this->profileService->getProfileByUserId(auth::user()->id);
            if(!$profile) {
                $profile = $this->profileService->save($post);
                return response()->json([
                    'statusCode' => 200,
                    'data' => new ProfileResource($profile),
                    'message' => 'Created Successfully'
                ], 200);
            }else{
                return response()->json([
                    'statusCode' => 402,
                    'data' => new ProfileResource($profile),
                    'message' => 'Profile Exists'
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

    public function getProfile($user_id)
    {
        try{
            $profile = $this->profileService->getProfileByUserId($user_id);
            $profile = ($profile) ? new ProfileResource($profile) : null;
            return response()->json([
                'statusCode' => 200,
                'data' => $profile
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
