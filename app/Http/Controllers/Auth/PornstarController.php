<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Pornstar;
use App\Utils\PictureUtil;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class PornstarController extends Controller
{

    /**
     * Display detailed information about a specific pornstar.
     *
     * @param int $id
     * @return RedirectResponse|Response
     */
    public function show($id)
    {
        $pornstar = Pornstar::find($id);
        if (!$pornstar) {
            return Redirect::route('pornstars.index');
        }

        // Retrieve picture cdns
        $pictures = PictureUtil::retrievePictureCdnForPornstar($pornstar['pornhub_id']);

        return Inertia::render('Pornstars/Show', [
            'pornstar' => $pornstar,
            'pictures' => $pictures,
        ]);
    }

    public function index(Request $request)
    {
        $query = Pornstar::query();

        // Handle search query
        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Default limit to 10 if not provided
        $limit = $request->get('limit', 10);

        // Get paginated results
        $pornstars = $query->paginate($limit);

        // Append search and limit parameters to pagination links
        $pornstars->appends([
            'search' => $request->search,
            'limit' => $limit,
        ]);

        return Inertia::render('Pornstars/Index', [
            'pornstars' => $pornstars,
            'search' => $request->search,
            'limit' => $limit,
        ]);
    }

}
