<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\BlogDetailResource;
use App\Http\Resources\BlogResource;
use App\Services\BlogService;

class BlogController extends Controller
{
    public function __construct(private readonly BlogService $service) {}

    /**
     * GET /api/blogs
     *
     * Paginated list of blogs (body excluded).
     */
    public function index()
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
