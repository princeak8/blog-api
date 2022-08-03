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

    public function save($data)
    {
        $data['password'] = bcrypt($data['password']);
        return Reader::create($data);
    }

    public function update($reader, $data)
    {
        if(isset($data['name'])) $reader->name = $data['name'];
        $reader->update();
    }

}



?>