<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatbotController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\FavoriteSoundController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\GptChatController;
use App\Http\Controllers\HelpTipController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\MoodCheckinController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post("/register",[AuthController::class,"register"]);
Route::post("/login", [AuthController::class, "login"]);

// Route::get('/journals', [JournalController::class, 'index']);
// Route::post('/journals', [JournalController::class, 'store']);
// Route::delete('/journals/{id}', [JournalController::class, 'destroy']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/journals', [JournalController::class, 'index']);
    Route::post('/journals', [JournalController::class, 'store']);
    Route::delete('/journals/{id}', [JournalController::class, 'destroy']);
    Route::post('/favorite-sounds', [FavoriteSoundController::class, 'store']);
    Route::get('/favorite-sounds', [FavoriteSoundController::class, 'index']);
    Route::delete('/favorite-sounds/{id}', [FavoriteSoundController::class, 'destroy']);
});


Route::middleware('auth:sanctum')->group(function () {
    Route::get('/categories', [ExerciseController::class, 'getCategories']);
    Route::get('/category/{id}/exercises', [ExerciseController::class, 'getExercisesByCategory']);
    Route::post('/exercise/{id}/complete', [ExerciseController::class, 'markCompleted']);
    Route::post('/exercise/{id}/favorite', [ExerciseController::class, 'toggleFavorite']);
    Route::get('/exercise/{id}', [ExerciseController::class, 'getExercise']);
    //ROUTEN FÜR DAS STIMMUNGSTAGEBUCH
    Route::post('/mood-checkin', [MoodCheckinController::class, 'store']);
    Route::get('/mood-checkin/last', [MoodCheckinController::class, 'getLastCheckin']);
    //Change password route
    Route::middleware('auth:sanctum')->post('/change-password', [AuthController::class, 'changePassword']);
    //Soforthilfe routen
    Route::post('/instant-help', [HelpTipController::class, 'getHelp']); // KI-Antwort abrufen
    Route::middleware('auth:sanctum')->post('/save-tips', [HelpTipController::class, 'store']);
    Route::get('/saved-tips', [HelpTipController::class, 'getSavedTips']); // Alle Tipps abrufen
    Route::post('/saved-tips/{id}/rate', [HelpTipController::class, 'rateTip']); // Tipp bewerten
});
Route::post('/chatbot/respond', [ChatbotController::class, 'respond']);
Route::post('/chatbot/gpt', [GptChatController::class, 'chat']);


Route::post('/chat', function (Request $request) {
    $userMessage = $request->input('message');

    $response = Http::withHeaders([
        'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
        'Content-Type' => 'application/json'
    ])->post(env('OPENAI_API_BASE') . '/chat/completions', [
        'model' => 'gpt-4',
        'messages' => [
            ['role' => 'system', 'content' => 'Du bist ein Psychologe, der Menschen mit PTBS unterstützt.'],
            ['role' => 'user', 'content' => $userMessage]
        ],
        'max_tokens' => 150,
        'temperature' => 0.7,
    ]);

    $gptReply = $response->json()['choices'][0]['message']['content'] ?? "Ich bin mir nicht sicher, was ich sagen soll, aber ich bin hier für dich.";

    return response()->json(['reply' => $gptReply]);
});


// 📌 KURS-ROUTEN
Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/{id}', [CourseController::class, 'show']);

// 📌 MODUL-ROUTEN
Route::get('/modules/{id}', [ModuleController::class, 'show']);

// 📌 LEKTIONEN-ROUTEN
Route::get('/lessons/{id}', [LessonController::class, 'show']);
//LEKTION ABGESCLOSSEN
Route::middleware('auth:sanctum')->post('/lessons/{id}/complete', [LessonController::class, 'completeLesson']);
Route::middleware('auth:sanctum')->get('/lessons/{id}/status', [LessonController::class, 'getLessonStatus']);
Route::middleware('auth:sanctum')->get('/modules/{id}/progress', [LessonController::class, 'getModuleProgress']);



Route::middleware('auth:sanctum')->get('/courses/{id}/modules', [CourseController::class, 'getCourseModules']);

