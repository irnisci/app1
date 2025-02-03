<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MoodCheckin;
use Illuminate\Support\Facades\Auth;
class MoodCheckinController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'mood'=> 'required|string',
            'note'=> 'nullable|string',
        ]);

        $moodCheckin = MoodCheckin::create([
            'user_id' => Auth::id(),
            'mood' => $request->mood,
            'note' => $request->note,
            'checked_at' => now(),
        ]);
        return response()->json(["message"=> "Check-in gespeichert",'moodCheckin' => $moodCheckin]);

}

public function getLastCheckin()
{
    $lastCheckin = MoodCheckin::where('user_id', Auth::id())
        ->orderBy('checked_at', 'desc')
        ->first();

    if (!$lastCheckin) {
        return response()->json(['message' => 'Kein Check-in gefunden'], 404);
    }

    return response()->json([
        'mood' => $lastCheckin->mood,
        'note' => $lastCheckin->note,
        'checked_at' => $lastCheckin->checked_at->toIso8601String()
    ]);
}
}
