<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserPreference;

class UserPreferenceController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'sources'       => 'nullable|array',         // Ensures 'sources' is an array
            'sources.*'     => 'string|max:255',         // Each item in the 'sources' array must be a string
            'categories'    => 'nullable|array',         // Ensures 'categories' is an array
            'categories.*'  => 'string|max:255',         // Each item in the 'categories' array must be a string
            'authors'       => 'nullable|array',         // Ensures 'authors' is an array
            'authors.*'     => 'string|max:255',         // Each item in the 'authors' array must be a string
        ]);


        $preference = UserPreference::updateOrCreate(['user_id' => auth()->id()], $data);

        return response()->json($preference);
    }

    /**
     * Show the user preferences.
     *
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        $preference = UserPreference::where('user_id', auth()->id())->first();
        return response()->json($preference);
    }
}
