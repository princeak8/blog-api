<?php

namespace App\Services;

use Hash;
use Carbon\Carbon;
use Illuminate\Support\Str;

use App\Models\EmailVerification;

class AuthService
{
    public function emailVerificationLink($reader)
    {
        do{
            $token = '';
            for($i=0; $i<6; $i++) {
                $token .= mt_rand(0, 9);
            }
            //$token = Str::random(4);
            $signature = hash('md5', $token);
            $exists = $this->getToken($reader->id, $token);
        } while ($exists);
        return env('APP_URL').'verify_email/'.$signature;
    }

    public function getToken($reader_id, $token)
    {
        return EmailVerification::where('reader_id', $reader_id)->where('signature', hash('md5', $token))->first();
    }

    public function clearUserTokens($reader_id)
    {
        $fields = EmailVerification::where('reader_id', $reader_id)->get();
        if($fields->count() > 0) {
            foreach($fields as $field) $field->delete();
        }
    }
}



?>