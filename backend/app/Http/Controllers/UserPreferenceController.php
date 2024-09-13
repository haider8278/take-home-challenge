<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserPreference;
use Illuminate\Support\Facades\Auth;

class UserPreferenceController extends Controller
{
    // Get user preferences
    public function getPreferences()
    {
        $preferences = UserPreference::where('user_id', Auth::id())->first();
        return response()->json($preferences);
    }

    // Set user preferences
    public function setPreferences(Request $request)
    {
        $request->validate([
            'preferred_sources' => 'array|nullable',
            'preferred_categories' => 'array|nullable',
            'preferred_authors' => 'array|nullable',
        ]);

        $preferences = UserPreference::updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'preferred_sources' => $request->input('preferred_sources'),
                'preferred_categories' => $request->input('preferred_categories'),
                'preferred_authors' => $request->input('preferred_authors'),
            ]
        );

        return response()->json($preferences);
    }
}
