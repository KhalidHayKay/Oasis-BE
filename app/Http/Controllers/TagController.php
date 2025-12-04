<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use App\Services\TagService;
use Illuminate\Http\Request;
use App\Http\Resources\TagResource;
use App\Http\Resources\CategoryResource;

class TagController extends Controller
{
    public function __construct(private readonly TagService $service) {}

    public function index(Request $request)
    {
        $cats = $this->service->byCategory();

        return CategoryResource::collection($cats);
    }

    public function ding(Request $request)
    {
        $tags = Tag::all()->load('category');

        return response()->json([
            'tags' => TagResource::collection($tags),
        ]);
    }
}
