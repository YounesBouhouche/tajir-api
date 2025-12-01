<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\CategoryCollection;
use App\Http\Resources\CategoryResource;
use App\Models\Category;

class CategoryController extends BaseController
{
    public function index()
    {
        return $this->sendResponse(CategoryCollection::make(Category::all()));
    }

    public function show(int $id)
    {
        return $this->sendResponse(CategoryResource::make(Category::findOrFail($id)));
    }
}
