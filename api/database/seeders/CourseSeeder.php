<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use App\Models\Course;
use App\Models\Module;
use App\Models\Lesson;

class CourseSeeder extends Seeder
{
    public function run()
    {
        // JSON-Datei einlesen
        $jsonPath = database_path('data/courses.json');

        if (!File::exists($jsonPath)) {
            $this->command->error("Fehler: Die Datei 'courses.json' wurde nicht gefunden!");
            return;
        }

        $json = File::get($jsonPath);
        $courses = json_decode($json, true);

        foreach ($courses as $courseData) {
            // Kurs speichern
            $course = Course::create([
                'title' => $courseData['title'],
                'description' => $courseData['description']
            ]);

            foreach ($courseData['modules'] as $moduleData) {
                // Modul speichern
                $module = Module::create([
                    'course_id' => $course->id,
                    'title' => $moduleData['title'],
                    'order' => $moduleData['order']
                ]);

                foreach ($moduleData['lessons'] as $lessonData) {
                    // Lektion speichern
                    Lesson::create([
                        'module_id' => $module->id,
                        'title' => $lessonData['title'],
                        'content' => $lessonData['content']
                    ]);
                }
            }
        }

        $this->command->info(" Kurse, Module und Lektionen wurden erfolgreich eingefügt!");
    }
}
