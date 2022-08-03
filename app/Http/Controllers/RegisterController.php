<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use Mail;

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
            dd($request->route('db'));
            $reader = $this->readerService->save($request->validated());
            $blog = $this->profileService->getProfile();
            $emailLink = $this->authService->emailVerificationLink($reader);
            try{
                $data = ['name'=>$reader->name, 'link'=>$emailLink, 'blog'=>$blog];
                Mail::send('mails.verify_email', $data, function($message) use($reader, $blog) {
                    $message->to($reader->email, $reader->name)->subject
                        ('Verify your Email');
                    $message->from('noreply@zizix6host.com',$blog->blog_name);
                });
            }catch(\Throwable $th) {
                \Log::stack(['project'])->info('could not send email '.$th->getMessage());
            }
            return response()->json([
                'statusCode' => 200,
                'data' => new ReaderResource($reader)
            ], 200);
        }catch (\Throwable $th) {
            \Log::stack(['project'])->info($th->getMessage().' in '.$th->getFile().' at Line '.$th->getLine());
            return response()->json([
                'statusCode' => 500,
                'message' => 'An error occured while trying to perform this operation, Please try again later or contact support'
            ], 500);
        }
    }

    public function verify_email($email, $signature)
    {
        try{
            $blog = $this->profileService->getProfile();
            $reader = $this->readerService->getReaderByEmail($email);
            if($reader) {
                //$this->authService->ver
            }else{
                return response()->json([
                    'statusCode' => 404,
                    'message' => new ReaderResource($reader)
                ], 404);
            }
            
        }catch (\Throwable $th) {
            \Log::stack(['project'])->info($th->getMessage().' in '.$th->getFile().' at Line '.$th->getLine());
            return response()->json([
                'statusCode' => 500,
                'message' => 'An error occured while trying to perform this operation, Please try again later or contact support'
            ], 500);
        }
    }
}
