<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Path;
use App\Models\Enrollment;
use App\Models\Record;
use App\Models\User;

class EnrollmentController extends Controller
{


    public function getAll(){
        $enrollments = Enrollment::with('student', 'path.levels.courses.teacher','circle')->get();

        return response()->json($enrollments);
    }
    public function getByCourseId($courseId)
    {
        $course = Course::find($courseId);
        $path = Path::find($course->level->path->id);

        $enrollments = Enrollment::where("path_id", $path->id)->with('student')->get();


        return response()->json($enrollments);


    }
    public function enroll($pathId,$studentId,$selected_circle_id = null)
    {
        $path = Path::findOrFail($pathId);

        $student = User::find($studentId);

        $enrollment = Enrollment::where('user_id', $student->id)->first();

        if ($enrollment) {
            return response()->json([
                'message' => 'Already enrolled in this path.',
                'data' => $path
            ], 400);
        }

        if ($path['is_active'] == 0) {
            return response()->json([
                'message' => 'Path is not active.',
                'data' => $path
            ], 400);
        }


        if ($path['is_hifd'] == 1) {

            $record = Record::create();

            $enrollment = Enrollment::create([
                'user_id' => $student->id,
                'path_id' => $path->id,
                'enrolled_at' => now(),
                'level_id' => null,
                'record_id' => $record->id,
                "circle_id" => $selected_circle_id,
            ]);

            return response()->json([
                'message' => 'Enrolled in Hifd path successfully.',
                'data' => $path
            ], 201);


        } else {

            $firstLevel = $path->levels()->orderBy('order', 'asc')->first();

            $enrollment = Enrollment::create([
                'user_id' => $student->id,
                'path_id' => $path->id,
                'level_id' => $firstLevel->id,
                'enrolled_at' => now(),
            ]);


        }


        return response()->json([
            'message' => 'Enrolled in path successfully.',
            'data' => $path
        ], 201);

    }
}
