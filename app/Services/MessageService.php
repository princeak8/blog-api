<?php

namespace App\Services;

use App\Models\Message;

class MessageService 
{

    public function getMessage($id)
    {
        return Message::find($id);
    }

    public function getMessagesByName($name)
    {
        return Message::where('name', $name)->get();
    }

    public function getMessagesByEmail($email)
    {
        return Message::where('email', $email)->get();
    }

    public function getMessages()
    {
        return Message::all();
    }

    public function getMessagesCount()
    {
        return Message::count();
    }

    public function save($data)
    {
        $message = new Message;
        $message->name = $data['name'];
        $message->email = $data['email'];
        $message->message = $data['message'];
        $message->save();
        return $message;
    }

    public function delete($message)
    {
        $message->delete();
    }

}



?>