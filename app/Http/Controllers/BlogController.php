<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\BlogDetailResource;
use App\Http\Resources\BlogResource;
use App\Services\BlogService;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function __construct(private readonly BlogService $service) {}

    public function index(Request $request)
    {
        $blogs = $this->service->getPaginatedBlogs();

        return BlogResource::collection($blogs);
    }

    public function show(string $slug)
    {
        $blog = $this->service->getBlogBySlug($slug);

        return new BlogDetailResource($blog);
    }
}
