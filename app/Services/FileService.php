<?php

namespace App\Services;

use App\Models\Profile;

use Cloudinary;

use App\Models\File;

class FileService 
{

    public function save($request, $domain, $user_id)
    {
        $image = $request->file('image');
        // $fileData = [];
        // $fileData['mime_type'] = $request->file('image')->getMimeType();;
        // $fileData['original_filename'] = $request->file('image')->getClientOriginalName();
        // $fileData['extension'] = $request->file('image')->getClientOriginalExtension();
        // dd($fileData);
        if($request->file('image')->getSize() <= 5000000) {
            $uploadedPhoto = $image->storeOnCloudinary('blogs/'.$domain);
            $filename = $uploadedPhoto->getFileName();
            $url = $uploadedPhoto->getPath();
            $secureUrl = $uploadedPhoto->getSecurePath();
            $publicId = $uploadedPhoto->getPublicId();
            $uploadDate = $uploadedPhoto->getTimeUploaded();
            $width = $uploadedPhoto->getWidth(); 
            $height = $uploadedPhoto->getHeight();
            $size = $uploadedPhoto->getSize(); // Get the size of the uploaded file in bytes
            $rSize = $uploadedPhoto->getReadableSize(); // Get the size of the uploaded file in bytes, megabytes, gigabytes or terabytes. E.g 1.5 MB

            $file = new File;
            $file->filename = $filename;
            $file->url = $url;
            $file->secure_url = $secureUrl;
            $file->public_id = $publicId;
            $file->width = $width;
            $file->height = $height;
            $file->size = $size;
            $file->formatted_size = $rSize;
            $file->user_id = $user_id;
            $file->file_type = 'image';
            $file->mime_type = $request->file('image')->getMimeType();;
            $file->original_filename = $request->file('image')->getClientOriginalName();
            $file->extension = $request->file('image')->getClientOriginalExtension();
            $file->save();
            return [
                'success' => true,
                'file' => $file
            ];
        }else{
            return [
                'success' => false,
                'message' => 'image greater than 5MB'
            ];
        }
        
    }

    public function update($profile, $data)
    {

        if(isset($data['name'])) $profile->name = $data['name'];
        if(isset($data['about'])) $profile->about = $data['about'];
        $profile->update();
    }

}



?>