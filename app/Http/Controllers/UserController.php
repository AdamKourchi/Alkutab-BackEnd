<?php

namespace App\Http\Controllers;

use App\Models\Circle;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Record;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function me(Request $request)
    {
        $user = $request->user();

        if ($user->hasRole('teacher')) {
            $user->load('courses.level');
            $user->circles = Circle::where('user_id', $user->id)
                ->with('path:id,name')
                ->get();
        } elseif ($user->hasRole('student')) {

            $user->load([
                'enrollments' => function ($q) {
                    $q->where('graduated', 0)
                        ->with('path.levels.courses.teacher:id,name', 'circle');
                },
                'answers',
                'examSubmissions.examSubmissionAnswers'
            ]);

            $currentEnrollment = $user->enrollments->where('graduated', 0);


            $user->enrollmentRequests = $user->enrollmentsRequests()
                    ->where('path_id', $currentEnrollment->first()->path_id ?? null)
                    ->where('status', 'approved')
                    ->get();


            // Add circle data to each current enrollment
            $user->circle = optional($currentEnrollment->first())->circle;

            $user->wajibs = optional(Record::where('id', optional($currentEnrollment->first())->record_id)->first())->wajibs;


            $user->class_sessions = $currentEnrollment
                ->flatMap(fn($enrollment) => $enrollment->path->levels)
                ->flatMap(fn($level) => $level->courses)
                ->flatMap(fn($course) => $course->classSessions);

            $user->courses = $currentEnrollment
                ->flatMap(fn($enrollment) => $enrollment->path->levels)
                ->flatMap(fn($level) => $level->courses)
                ->unique('id');



        }
        return response()->json($user);
    }

    public function studentsByCourseId($course_id)
    {
        $course = Course::find($course_id);

        $enrollments = Enrollment::where('level_id', $course->level->id)
            ->where('graduated', 0)
            ->get();

        $students = $enrollments->map(fn($enrollment) => $enrollment->student);

        return response()->json($students);
    }

    public function studentsByCircleId($circle_id)
    {
        $circle = Circle::find($circle_id);

        if (!$circle) {
            return response()->json(['message' => 'Circle not found'], 404);
        }

        $enrollments = Enrollment::where('circle_id', $circle->id)
            ->where('graduated', 0)
            ->get();


        $students = $enrollments->map(function ($enrollment) {
            $student = $enrollment->student;
            if ($student) {

                $student->enrollment_requests = $student->enrollmentsRequests()
                    ->where('path_id', $enrollment->path_id)
                    ->where('status', 'approved')
                    ->get();


                $student->record = Record::where('id', $enrollment->record_id)
                    ->first();

                if ($student->record) {
                    $student->wajibs = $student->record->wajibs;
                }
            }
            return $student;
        });


        if ($students->isEmpty()) {
            return response()->json([]);
        }

        return response()->json($students);
    }

}