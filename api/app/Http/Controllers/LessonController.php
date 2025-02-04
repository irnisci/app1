<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use App\Models\UserProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LessonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Lesson::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return response()->json(Lesson::findOrFail($id));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function completeLesson($id)
    {
        $user = Auth::user();
        $lesson = Lesson::findOrFail($id);

        $exists = UserProgress::where('user_id',$user->id)->where('lesson_id',$lesson->id)->exists();
        if($exists){
            return response()->json(['message' => 'Lektion wurde bereits abgeschlossen'], 400);
        }

        UserProgress::create([
            'user_id' => $user->id,
            'lesson_id' => $lesson->id,
            'completed_at' => now()
        ]);
        return response()->json(["message"=>"Lektion erfolgreich abgeschlossen"]);
    }

    public function getLessonStatus($id)
{
    $user = auth()->user();

    $lesson = Lesson::findOrFail($id);
    $completed = UserProgress::where('user_id', $user->id)
        ->where('lesson_id', $lesson->id)
        ->exists();

    return response()->json([
        'lesson' => $lesson,
        'completed' => $completed
    ]);
}


// Prufen ob modul abgeschlossen ist und demensprechend frontend icons check oder lock anzeigen in moduledetails.vue
public function getModuleProgress($moduleId)
{
    $user = auth()->user();

    // Alle Lektionen im Modul abrufen
    $lessons = Lesson::where('module_id', $moduleId)->get();

    // Überprüfen, welche Lektionen vom Nutzer abgeschlossen wurden
    $completedLessons = UserProgress::where('user_id', $user->id)
        ->whereIn('lesson_id', $lessons->pluck('id'))
        ->pluck('lesson_id')
        ->toArray();

    return response()->json([
        'lessons' => $lessons,
        'completed' => $completedLessons
    ]);
}
}
