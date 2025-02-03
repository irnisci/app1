<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use App\Models\HelpTip;

class HelpTipController extends Controller {
    //Antwort speichern
    // KI-Antwort generieren und speichern
    public function getHelp(Request $request) {
        $request->validate([
            'topic' => 'required|string',
            'userInput' => 'nullable|string'
        ]);

        $prompt = "Du bist ein erfahrener Therapeut. Gib eine kurze, praktische Strategie für eine Person mit dem Thema '{$request->topic}'.";

        if (!empty($request->userInput)) {
            $prompt .= " Die Person beschreibt ihre Situation so: '{$request->userInput}'.";
        }

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
            'Content-Type' => 'application/json'
        ])->post(env('OPENAI_API_BASE') . '/chat/completions', [
            'model' => 'gpt-4',
            'messages' => [['role' => 'system', 'content' => $prompt]],
            'max_tokens' => 150
        ]);
        if (!$response->successful()) {
            return response()->json(['error' => 'Fehler bei der KI-Abfrage.', 'details' => $response->json()], 500);
        }
        $aiResponse = $response->json()['choices'][0]['message']['content'];

        // Antwort speichern
        $helpTip = HelpTip::create([
            'user_id' => Auth::id(),
            'topic' => $request->topic,
            'tip' => $aiResponse
        ]);

        return response()->json(['reply' => $aiResponse, 'tip' => $helpTip]);
    }

    public function store(Request $request)
{
    $request->validate([
        'topic' => 'required|string',
        'tip' => 'required|string',
    ]);

    $helpTip = HelpTip::create([
        'user_id' => Auth::id(),
        'topic' => $request->topic,
        'tip' => $request->tip,
    ]);

    return response()->json([
        'message' => 'Tipp gespeichert!',
        'tip' => $helpTip
    ], 201);
}

    // Alle gespeicherten Tipps abrufen
    public function getSavedTips() {
        $tips = HelpTip::where('user_id', Auth::id())->get();
        return response()->json($tips);
    }

    // Tipp bewerten
    public function rateTip(Request $request, $id) {
        $request->validate(['rating' => 'required|integer|min:1|max:5']);

        $tip = HelpTip::where('id', $id)->where('user_id', Auth::id())->first();
        if (!$tip) {
            return response()->json(['message' => 'Tipp nicht gefunden'], 404);
        }

        $tip->update(['rating' => $request->rating]);

        return response()->json(['message' => 'Bewertung gespeichert', 'tip' => $tip]);
    }
}
