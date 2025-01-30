<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteSoundController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'sound_name' => 'required|string',
        ]);

        $favorite = Auth::user()->favoriteSounds()->create([
            'sound_name' => $request->sound_name,
        ]);

        return response()->json([
            'message' => 'Sound zu Favoriten hinzugefügt!',
            'favorite' => $favorite,
        ], 201);
    }

    // Hole alle Favoriten des Benutzers
    public function index()
    {
        $favorites = Auth::user()->favoriteSounds()->orderBy('created_at', 'desc')->get();
        return response()->json($favorites);
    }

    // Entferne einen Sound aus den Favoriten
    public function destroy($id)
    {
        $favorite = Auth::user()->favoriteSounds()->findOrFail($id);
        $favorite->delete();

        return response()->json([
            'message' => 'Sound aus Favoriten entfernt!',
        ]);
    }
}
