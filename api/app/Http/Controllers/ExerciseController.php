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

    public function markExerciseComplete($id)
    {
        UserExercise::updateOrCreate(
            ['user_id' => Auth::id(), 'exercise_id' => $id],
            ['completed' => true]
        );

        return response()->json(['message' => 'Übung als erledigt markiert']);
    }

    public function toggleFavorite($id)
    {
        $exercise = Exercise::findOrFail($id);
        $exercise->is_favorite = !$exercise->is_favorite;
        $exercise->save();

        return response()->json(['message' => 'Favoritenstatus geändert']);
    }
}
