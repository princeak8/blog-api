<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests\SaveMessageRequest;

use App\Http\Resources\MessageResource;

use App\Services\MessageService;
use App\Services\ProfileService;

use Illuminate\Support\Facades\Mail;
use App\Mail\ContactMessageMail;

class MessageController extends Controller
{
    private $messageService;
    private $profileService;

    /**
     * Create a new MessageController instance.
     *
     * @return void
     */
    public function __construct() {
        $this->messageService = new MessageService;
        $this->profileService = new ProfileService;
    }

    public function save(SaveMessageRequest $request)
    {
        try{
            $data = $request->validated();
            $message = $this->messageService->save($data);
            try{
                $user = $this->profileService->getProfile();
                Mail::mailer($data['domain'])->to($user)->send(new ContactMessageMail($message));
            }catch(\Exception $e){
                \Log::stack(['project'])->info($e->getMessage().' in '.$e->getFile().' at Line '.$e->getLine());
                return response()->json([
                    'statusCode' => 201,
                    'data' => new MessageResource($message)
                ], 201);
            }
            return response()->json([
                'statusCode' => 200,
                'data' => new MessageResource($message)
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
