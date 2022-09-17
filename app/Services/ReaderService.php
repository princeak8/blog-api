<?php

namespace App\Services;

use App\Models\Reader;

class ReaderService 
{

    public function getReaderById($id)
    {
        return Reader::find($id);
    }

    public function getReaderByEmail($email)
    {
        return Reader::where('email', $email)->first();
    }

    public function getReaderByProvider($provider_name, $provider_id)
    {
        return Reader::where('provider_name', $provider_name)->where('provider_id', $provider_id)->first();
    }

    public function save($data)
    {
        $data['password'] = bcrypt($data['password']);
        return Reader::create($data);
    }

    public function saveGoogleUser($data)
    {
        return Reader::create($data);
    }

    public function update($reader, $data)
    {
        if(isset($data['name'])) $reader->name = $data['name'];
        $reader->update();
    }

    public function verify_email($reader)
    {
        $reader->email_verified = 1;
        $reader->update();
        return $reader;
    }

    public function updatePassword($reader, $password)
    {
        $reader->password = $password;
        $reader->update();
    }

}



?>