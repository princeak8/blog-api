<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

use App\Http\Requests\CreateProfileRequest;

use App\Services\ProfileService;
use App\Services\ReaderService;

use App\Http\Resources\ProfileResource;
use App\Http\Resources\ReaderResource;

class UserController extends Controller
{
    private $profileService;

    public function __construct() {
        $this->middleware('auth:api');
        $this->profileService = new ProfileService;
        $this->readerService = new ReaderService;
    }

    public function create_profile(CreateProfileRequest $request)
    {
        try{
            $post = $request->all();
            $post['user_id'] = auth::user()->id;
            $post['email'] = auth::user()->email;
            $profile = $this->profileService->getProfileByUserId(auth::user()->id);
            if(!$profile) {
                //dd($post);
                $profile = $this->profileService->save($post);
                $reader = ['email'=>auth::user()->email, 'password'=>auth::user()->password, 'name'=>$post['name'], 'is_admin'=>1];
                $this->readerService->save($reader);
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

    public function change_password(Request $request)
    {
        try{
            $validator = Validator::make($request->all(), [
                'password' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }
            $user = $this->readerService->getReaderByEmail(auth::user()->email);
            if($user) {
                //dd($post);
                $this->readerService->updatePassword($user, $request->get('password'));
                return response()->json([
                    'statusCode' => 200,
                    'message' => 'Updated Successfully'
                ], 200);
            }else{
                return response()->json([
                    'statusCode' => 402,
                    'message' => 'User does not Exists'
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
