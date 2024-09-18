<?php

namespace App\Http\Controllers;

use App\Models\Pornstar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class PornstarController extends Controller
{

    /**
     * Display detailed information about a specific pornstar.
     *
     * @param int $id
     * @return \Inertia\Response
     */
    public function show($id)
    {
        $pornstar->thumbnails = collect(json_decode($pornstar->thumbnails, true))->map(function ($thumbnail) {
            return [
                'type' => $thumbnail['type'],
                'height' => $thumbnail['height'],
                'width' => $thumbnail['width'],
                'urls' => array_map(function ($url) {
                    // Extract the file path from the URL
                    $filePath = parse_url($url, PHP_URL_PATH);

                    // Check if the file exists in storage
                    if (Storage::disk('media')->exists(ltrim($filePath, '/'))) {
                        // Generate URL for the file if it exists
                        return Storage::disk('medi')->url(ltrim($filePath, '/'));
                    }

                    // Return a placeholder or empty string if the file does not exist
                    return '';
                }, $thumbnail['urls']),
            ];
        })->toArray();

    }

    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $limit = $request->get('limit', 10); // Default limit is 10 if not provided
        $selectedEthnicity = $request->get('ethnicity', ''); // Ethnicity filter

        // Fetch pornstars with optional search and ethnicity filter, and pagination
        $query = Pornstar::query();

        if (!empty($search)) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('pornhub_id', 'like', "%{$search}%");
        }

        if (!empty($selectedEthnicity)) {
            $query->where('ethnicity', $selectedEthnicity);
        }

        // Get distinct ethnicities for the filter dropdown
        $ethnicities = Pornstar::distinct()->pluck('ethnicity')->filter();

        $pornstars = $query->paginate($limit);

        return Inertia::render('Pornstars/Index', [
            'pornstars' => $pornstars,
            'search' => $search,
            'limit' => $limit, // Pass the current limit to the frontend
            'selectedEthnicity' => $selectedEthnicity, // Pass the selected ethnicity to the frontend
            'ethnicities' => $ethnicities, // Pass the list of ethnicities for the dropdown
        ]);
    }

}
