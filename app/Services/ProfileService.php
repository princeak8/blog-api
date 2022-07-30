<?php

namespace App\Services;

use App\Models\Profile;

class ProfileService 
{

    public function getProfile()
    {
        return Profile::first();
    }
    
    public function getProfileByUserId($id)
    {
        return Profile::where('user_id', $id)->first();
    }

    public function getProfileById($id)
    {
        return Profile::find($id);
    }

    public function save($data)
    {
        return Profile::create($data);
    }

    public function update($profile, $data)
    {

        if(isset($data['name'])) $profile->name = $data['name'];
        if(isset($data['about'])) $profile->about = $data['about'];
        $profile->update();
    }

}



?>