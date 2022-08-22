<?php

namespace App\Services;

use Hash;
use Carbon\Carbon;
use Illuminate\Support\Str;

use App\Models\EmailVerification;
use App\Models\Reader;

class AuthService
{
    public function emailVerificationSignature($reader)
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
        $this->saveToken($reader->id, $token);
        return $signature;
    }

    public function getToken($reader_id, $token)
    {
        return EmailVerification::where('reader_id', $reader_id)->where('token', $token)->first();
    }

    public function getEmailTokenBySignature($signature)
    {
        $emailTokens = EmailVerification::all();
        foreach($emailTokens as $emailToken) {
            $tokenSignature = hash('md5', $emailToken->token);
            if($signature == $tokenSignature) {
                return $emailToken;
            }
        }
        return false;
    }

    public function saveToken($reader_id, $token)
    {
        $emailToken = new EmailVerification;
        $emailToken->reader_id = $reader_id;
        $emailToken->token = $token;
        $emailToken->save();
    }

    public function clearUserTokens($reader_id)
    {
        $fields = EmailVerification::where('reader_id', $reader_id)->get();
        if($fields->count() > 0) {
            foreach($fields as $field) $field->delete();
        }
    }

    public function verifySignature($signature)
    {
        $emailToken = $this->getEmailTokenBySignature($signature);
        return ($emailToken) ? $emailToken->reader : false;
    }
}



?>