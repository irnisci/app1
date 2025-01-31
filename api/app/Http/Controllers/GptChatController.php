<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Exercise;
use Illuminate\Support\Facades\Auth;

class GptChatController extends Controller
{
    public function chat(Request $request)
    {
        $userMessage = $request->input('message');
        $exerciseId = $request->input('exercise_id');
        $chatHistory = $request->input('chat_history', []);

        $exercise = Exercise::find($exerciseId);
        if (!$exercise) {
            return response()->json(['reply' => "Ich konnte die Übung nicht finden. Magst du mir erzählen, wie du dich fühlst?"]);
        }

        // Systemrolle mit stärkerem Fokus auf die Übung
        $messages = [
            ['role' => 'system', 'content' => "Du bist ein freundlicher Therapeut für Menschen mit PTBS. Deine Aufgabe ist es, gezielt Fragen zur Übung zu stellen, den Nutzer zu ermutigen und ihm zu helfen, das Gelernte in den Alltag zu integrieren. Halte das Gespräch freundlich und unterstützend, aber bleibe auf das Thema der Übung fokussiert. Antworte nie mit 'Ich bin mir nicht sicher'."],
            ['role' => 'user', 'content' => "Ich habe gerade die Übung \"{$exercise->title}\" abgeschlossen. {$exercise->description}"],
            ['role' => 'assistant', 'content' => "Wie war diese Übung für dich? Gab es einen Moment, der dir besonders geholfen hat?"]
        ];

        // Vorherige Chat-Nachrichten mitgeben
        foreach ($chatHistory as $chatMessage) {
            $messages[] = ['role' => $chatMessage['role'], 'content' => $chatMessage['content']];
        }

        // Nutzerantwort anhängen
        $messages[] = ['role' => 'user', 'content' => $userMessage];

        // Anfrage an GPT senden
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
            'Content-Type' => 'application/json'
        ])->post('https://api.openai.com/v1/chat/completions', [
            // 'model' => 'gpt-4',
            'model' => 'gpt-3.5-turbo',
            'messages' => $messages,
            'max_tokens' => 200,
            'temperature' => 0.6,
        ]);

        // KI-Antwort auswerten
        $gptReply = $response->json()['choices'][0]['message']['content'] ?? "Ich bin hier für dich. Kannst du mir mehr über dein Erlebnis mit der Übung erzählen?";

        return response()->json(['reply' => $gptReply]);
    }
}
