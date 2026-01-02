<?php

namespace App\Http\Controllers;

use App\Models\Inspiration;
use App\Http\Resources\InspirationResource;

class InspirationController extends Controller
{
    public function index()
    {
        $inspo = Inspiration::where('is_active', true)
            ->orderBy('display_order', 'asc')
            ->get();

        return InspirationResource::collection($inspo);
    }
}
