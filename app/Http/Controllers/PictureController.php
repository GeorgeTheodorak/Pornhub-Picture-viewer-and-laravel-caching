<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class PictureController extends Controller
{

    public function show(Request $request, $image)
    {
        // Logic to fetch and serve the image
        $path = storage_path("app/public/media/{$image}");
        if (file_exists($path)) {
            return response()->file($path);
        }

        abort(404);
    }

}
