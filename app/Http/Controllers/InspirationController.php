<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Resources\InspirationResource;

class InspirationController extends Controller
{
    public function index()
    {
        $inspo = \App\Models\Inspiration::where('is_active', true)
            ->orderBy('display_order', 'asc')
            ->get();

        return InspirationResource::collection($inspo);
    }
}
