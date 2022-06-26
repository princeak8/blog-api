<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Tag;

class TagService 
{

    public function getTag($id)
    {
        return Tag::find($id);
    }

    public function getTagByName($name)
    {
        return Tag::where('name', $name)->first();
    }

    public function getTags()
    {
        return Tag::all();
    }

    public function getTagsCount()
    {
        return Tag::count();
    }

    public function save($data)
    {
        $tag = new Tag;
        $tag->name = $data['name'];
        $tag->save();
        return $tag;
    }

    public function update($tag, $data)
    {
        $tag->name = $data['name'];
        $tag->update();
        return $tag;
    }

}



?>