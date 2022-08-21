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
            $data = $request->all();
            if(isset($data['domain'])) {
                $reader = $this->readerService->save($request->validated());
                $blog = $this->profileService->getProfile();
                $emailLink = $this->authService->emailVerificationLink($reader, $data['domain']);
                // try{
                //     $fromAddress = env($data['domain'].'_MAIL_HOST');
                //     $data = ['name'=>$reader->name, 'link'=>$emailLink, 'blog'=>$blog];
                //     Mail::mailer($data['domain'])->send('mails.verify_email', $data, function($message) use($reader, $blog) {
                //         $message->to($reader->email, $reader->name)->subject
                //             ('Verify your Email');
                //         $message->from($fromAddress, $blog->blog_name);
                //     });
                // }catch(\Throwable $th) {
                //     \Log::stack(['project'])->info('could not send email '.$th->getMessage());
                // }
                return response()->json([
                    'statusCode' => 200,
                    'message' => 'Registeration Successful.. An Email confirmation link has been sent to your mail. Confirm your email and login'
                ], 200);
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

    public function verify_email($domain, $email, $signature)
    {
        try{
            $blog = $this->profileService->getProfile();
            $reader = $this->readerService->getReaderByEmail($email);
            if($reader) {
                //$this->authService->ver
            }else{
                redirect('https://'.$domain.'.com/email_user_not_found');
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
