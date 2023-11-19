<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

use App\Services\ReaderService;
use App\Services\AuthService;
use App\Services\ProfileService;

use App\Http\Resources\ReaderResource;

class RegisterController extends Controller
{
    private $readerService;
    private $authService;
    private $profileService;

    /**
     * Create a new ReaderController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->readerService = new ReaderService;
        $this->authService = new AuthService;
        $this->profileService = new ProfileService;
    }

    public function register(RegisterRequest $request)
    {
        try{
            $post = $request->all();
            // dd($post['domain_name']);
            if(isset($post['domain'])) {
                if(isset($post['domain_name'])) {
                    $reader = $this->readerService->save($request->validated());
                    $blog = $this->profileService->getProfile();
                    $signature = $this->authService->emailVerificationSignature($reader);
                    $emailLink = $post['domain_name'].'/confirm_email'.'/'.$signature;
                    try{
                        $fromAddress = 'registration@'.env($post['domain'].'_DOMAIN_NAME');
                        $data = ['name'=>$reader->name, 'link'=>$emailLink, 'blog'=>$blog];
                        Mail::mailer($post['domain'])->send('mails.verify_email', $data, function($message) use($reader, $blog, $fromAddress) {
                            $message->to($reader->email, $reader->name)->subject
                                ('Verify your Email');
                            $message->from($fromAddress, $blog->blog_name);
                        });

                        return response()->json([
                            'statusCode' => 200,
                            'message' => 'Registeration Successful.. An Email confirmation link has been sent to your mail. Confirm your email and login'
                        ], 200);
                    }catch(\Throwable $th) {
                        \Log::stack(['project'])->info('could not send email '.$th->getMessage());
                        return response()->json([
                            'statusCode' => 201,
                            'message' => 'Registeration Successful.. But an error occured while attempting to send Email verification link.. please contact the administrator'
                        ], 201);
                    }
                    
                }else{
                    return response()->json([
                        'statusCode' => 500,
                        'message' => 'Domain Name is not set in the post data'
                    ], 500);
                }
            }else{
                return response()->json([
                    'statusCode' => 500,
                    'message' => 'Domain is not set in the post data'
                ], 500);
            }
        }catch (\Throwable $th) {
            \Log::stack(['project'])->info($th->getMessage().' in '.$th->getFile().' at Line '.$th->getLine());
            return response()->json([
                'statusCode' => 500,
                'message' => 'An error occured while trying to perform this operation, Please try again later or contact support'
            ], 500);
        }
    }

    public function verify_email(Request $request)
    {
        $post = $request->all();
        try{
            $validator = Validator::make($post, [
                'signature' => 'required|string'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }
            $user = $this->authService->verifySignature($post['signature']);
            if($user) {
                $user = $this->readerService->verify_email($user); //Update the verified status of the user
                $this->authService->clearUserTokens($user->id); //delete all the email verification tokens from the db

                return response()->json([
                    'statusCode' => 201,
                    'message' => 'Email Link Confirmed Succesfully'
                ], 201);
                //Attempt to login the user automatically
                // $token = ($reader = Auth::guard('reader')->getProvider()->retrieveByCredentials(["email"=>$user->email]))
                // ? Auth::guard('reader')->login($reader)
                // : false;
                // if($token) { //if login was successful
                //     $user = new ReaderResource($user);
                //     return response()->json([
                //         'statusCode' => 200,
                //         'message' => 'Email Link Confirmed Succesfully',
                //         'data' => [
                //             'token' => $token,
                //             'token_type' => 'bearer',
                //             'token_expires_in' => Auth::guard('reader')->factory()->getTTL() * 60, 
                //             'user' => $user
                //         ]
                //     ], 200);
                // }else{
                    //If login was not successful
                    
            }else{
                return response()->json([
                    'statusCode' => 402,
                    'message' => 'Email Link Verification Failed'
                ], 402);
            }
            
        }catch (\Throwable $th) {
            \Log::stack(['project'])->info($th->getMessage().' in '.$th->getFile().' at Line '.$th->getLine());
            return response()->json([
                'statusCode' => 500,
                'message' => 'An error occured while trying to perform this operation, Please try again later or contact support'
            ], 500);
        }
    }

    public function test_email_verification(Request $request)
    {
        $post = $request->all();
        $reader = $this->readerService->getReaderByEmail($post['email']);
        if($reader) {
            $signature = $this->authService->emailVerificationSignature($reader);
            $user = $this->authService->verifySignature($signature);
            if($user) {
                $user = $this->readerService->verify_email($user);
                $this->authService->clearUserTokens($user->id);
                $token = ($reader = Auth::guard('reader')->getProvider()->retrieveByCredentials(["email"=>$user->email]))
                ? Auth::guard('reader')->login($reader)
                : false;
                if($token) {
                    $user = new ReaderResource($user);
                    return response()->json([
                        'statusCode' => 200,
                        'data' => [
                            'token' => $token,
                            'token_type' => 'bearer',
                            'token_expires_in' => Auth::guard('reader')->factory()->getTTL() * 60, 
                            'user' => $user
                        ]
                    ], 200);
                }else{
                    echo "Login failed";
                }
            }else{
                echo "verification failed";
            }
        }else{
            echo "user was not found";
        }
    }
}
