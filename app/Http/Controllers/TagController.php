<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use App\Http\Resources\TagResource;

class TagController extends Controller
{
    public function index(Request $request)
    {
        $tags = Tag::all()->load('category');

        return response()->json([
            'tags' => TagResource::collection($tags),
        ]);
    }
}
