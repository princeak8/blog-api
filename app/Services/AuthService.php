<?php

namespace App\Services;

use Hash;
use Carbon\Carbon;
use Illuminate\Support\Str;

use App\Models\EmailVerification;
use App\Models\Reader;

class AuthService
{
    public function emailVerificationLink($reader, $domain)
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
        $this->saveSignature($reader->id, $signature);
        return env('APP_URL').$domain.'/'.'verify_email/'.$reader->email.'/'.$signature;
    }

    public function getToken($reader_id, $token)
    {
        return EmailVerification::where('reader_id', $reader_id)->where('signature', hash('md5', $token))->first();
    }

    public function saveSignature($reader_id, $signature)
    {
        $emailSignature = new EmailVerification;
        $emailSignature->reader_id = $reader_id;
        $emailSignature->signature = $signature;
        $emailSignature->save();
    }

    public function clearUserTokens($reader_id)
    {
        $fields = EmailVerification::where('reader_id', $reader_id)->get();
        if($fields->count() > 0) {
            foreach($fields as $field) $field->delete();
        }
    }

    public function verifySignature($reader, $signature)
    {
        $signature = $this->getToken($reader->id, $signature);
        return ($signature) ? 1 : 0;
    }
}



?>