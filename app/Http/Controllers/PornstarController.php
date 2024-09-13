<?php

namespace App\Http\Controllers;

use App\Models\Pornstar;
use Inertia\Inertia;

class PornstarController extends Controller
{

    // keep that for rest.
    //    /**
    //     *
    //     * @return \Illuminate\Http\JsonResponse
    //     */
    //    public function index()
    //    {
    //        // Retrieve all pornstars from the database
    //        $pornstars = Pornstar::all();
    //
    //        // Return the pornstars as a JSON response
    //        return response()->json($pornstars);
    //    }
    //
    //    /**
    //     *
    //     * @param  int  $id
    //     * @return \Illuminate\Http\JsonResponse
    //     */
    //    public function show($id)
    //    {
    //        // Retrieve the pornstar by ID
    //        $pornstar = Pornstar::find($id);
    //
    //        // Check if the pornstar was found
    //        if (!$pornstar) {
    //            return response()->json(['message' => 'Pornstar not found'], 404);
    //        }
    //
    //        // Return the pornstar as a JSON response
    //        return response()->json($pornstar);
    //    }


        /**
         *
         * @return \Inertia\Response
         */
        public function index()
        {
            // Example of fetching data
            $pornstars = Pornstar::all();

            return Inertia::render('Pornstars/Index', [
                'pornstars' => $pornstars
            ]);
        }

}
