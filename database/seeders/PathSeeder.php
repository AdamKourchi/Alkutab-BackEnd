<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Path;
use App\Models\Level;
use App\Models\Course;
use App\Models\User;
use App\Models\Circle;


class PathSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // Get the first user with role teacher
        $teacher = User::role('teacher')->first();

        //create two paths
        $hifdPath = Path::create([
            'name' => 'Hifd Path',
            'description' => 'Path for Hifd students',
            'start_at' => now(),
            'end_at' => now()->addMonths(12),
            'duration_months' => 12,
            'created_by' => 1,
            'diploma_title' => 'Diploma in Hifd',
            'is_active' => true,
            'is_hifd' => true,

        ]);


        Circle::create([
            'title' => 'Circle A',
            'path_id' => $hifdPath->id,
            'user_id' => $teacher->id,
            'days_of_week' => json_encode([0, 2, 4]), 
            'start_time' => '2025-10-01 10:00:00',
            'end_time' => '2025-10-01 12:00:00',
            'link'=>""

        ]);


        $generalPath = Path::create([
            'name' => 'General Path',
            'description' => 'Path for general students',
            'start_at' => now(),
            'end_at' => now()->addMonths(6),
            'duration_months' => 6,
            'created_by' => 1,
            'diploma_title' => 'Diploma in General Studies',
            'is_active' => true,
            'is_hifd' => false,
        ]);


        // Create one level for the general path
        $level = Level::create([
            'path_id' => $generalPath->id,
            'name' => 'General Level 1',
            'start_at' => now(),
            'end_at' => now()->addMonths(6),
            'duration_months' => 6,
            'description' => 'First level for general path',
            'order' => 1,
        ]);



        // Create a course in the created level
        Course::create([
            'level_id' => $level->id,
            'user_id' => $teacher ? $teacher->id : null,
            'title' => 'General Course 1',
            'description' => 'First course in general level 1',
        ]);

    }
}
