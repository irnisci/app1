<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Exercise;
use Illuminate\Support\Facades\File;

class ExerciseSeeder extends Seeder
{
    public function run()
    {
        // JSON-Datei einlesen
        $json = File::get(database_path('data/exercises.json'));
        $exercises = json_decode($json, true);

        foreach ($exercises as $exercise) {
            // Kategorie prüfen oder erstellen
            $category = Category::firstOrCreate(['name' => $exercise['category']]);

            // Übung einfügen
            Exercise::create([
                'category_id' => $category->id,
                'title' => $exercise['title'],
                'description' => $exercise['description'],
                'duration' => $exercise['duration']
            ]);
        }
    }
}
