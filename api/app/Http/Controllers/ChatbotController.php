<?php

namespace App\Http\Controllers;
use OpenAi;

use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function respond(Request $request)
    {
        $userMessage = $request->input('message');

        // Eine einfache Logik für empathische Antworten
        $responses = [
            "Das ist völlig in Ordnung. Magst du mir mehr darüber erzählen?",
            "Es ist okay, wenn nicht jede Übung sofort hilft. Was würde dir jetzt guttun?",
            "Manchmal hilft es, die Gedanken aufzuschreiben. Möchtest du das ausprobieren?"
        ];

        $reply = $responses[array_rand($responses)];

        return response()->json(['reply' => $reply]);
    }
}
