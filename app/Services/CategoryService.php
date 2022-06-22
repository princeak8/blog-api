<?php

namespace App\Services;

use App\Models\Post;
use App\Models\Category;

class CategoryService 
{

    public function getCategory($id)
    {
        return Category::find($id);
    }

    public function getCategoryByName($name)
    {
        return Category::where('name', $name)->first();
    }

    public function getCategories()
    {
        return Category::all();
    }

    public function getCategoriesCount()
    {
        return Category::count();
    }

    public function save($data)
    {
        $category = new Category;
        $category->name = $data['name'];
        $category->save();
    }

    public function update($category, $data)
    {
        $category->name = $data['name'];
        $category->update();
    }

}



?>