<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Exercise;
use App\Models\UserExercise;
use Auth;

class ExerciseController extends Controller
{
    public function getCategories()
    {
        return response()->json(Category::all());
    }

    public function getExercisesByCategory($id)
    {
        return response()->json(Exercise::where('category_id', $id)->get());
    }

    public function markCompleted($exerciseId)
    {
        $userExercise = UserExercise::firstOrCreate([
            'user_id' => Auth::id(),
            'exercise_id' => $exerciseId,
        ]);

        $userExercise->completed = true;
        $userExercise->save();

        return response()->json(['message' => 'Übung als erledigt markiert']);
    }

    public function toggleFavorite($exerciseId)
    {
        $userExercise = UserExercise::firstOrCreate([
            'user_id' => Auth::id(),
            'exercise_id' => $exerciseId,
        ]);

        $userExercise->is_favorite = !$userExercise->is_favorite;
        $userExercise->save();

        return response()->json(['message' => 'Favoritenstatus aktualisiert', 'is_favorite' => $userExercise->is_favorite]);
    }

//     public function getExercise($id)
// {
//     $exercise = Exercise::where('id', $id)->first();
//     return response()->json($exercise);
// }

public function getExercise($id)
{
    $exercise = Exercise::findOrFail($id);
    $userExercise = UserExercise::where('user_id', Auth::id())->where('exercise_id', $id)->first();

    return response()->json([
        'exercise' => $exercise,
        'is_favorite' => $userExercise ? $userExercise->is_favorite : false,
        'completed' => $userExercise ? $userExercise->completed : false,
    ]);
}
}
