<?php

namespace App\Http\Controllers;

use App\Models\Journal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JournalController extends Controller
{
    // Alle Einträge des Benutzers abrufen
    public function index()
    {
        $journals = Auth::user()->journals()->orderBy('created_at', 'desc')->get();
        return response()->json($journals);
    }

    // Neuen Eintrag erstellen
    public function store(Request $request)
    {
        $request->validate([
            'entry' => 'required|string|max:1000',
            'emotion' => 'nullable|string', // Emotion ist optional
        ]);

        $journal = Auth::user()->journals()->create([
            'entry' => $request->entry,
            'emotion' => $request->emotion,
        ]);

        return response()->json([
            'message' => 'Eintrag erfolgreich gespeichert!',
            'journal' => $journal,
        ], 201);
    }

    // Eintrag löschen
    public function destroy($id)
    {
        $journal = Auth::user()->journals()->findOrFail($id);
        $journal->delete();

        return response()->json([
            'message' => 'Eintrag erfolgreich gelöscht!',
        ]);
    }
}
