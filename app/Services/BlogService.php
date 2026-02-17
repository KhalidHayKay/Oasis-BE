<?php

namespace App\Services;

use App\Models\Blog;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class BlogService
{
    public function getPaginatedBlogs(int $perPage = 6): LengthAwarePaginator
    {
        return Blog::latest()->paginate($perPage);
    }

    public function getBlogBySlug(string $slug): Blog
    {
        return Blog::where('slug', $slug)->firstOrFail();
    }
}
