<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Post;
use App\Models\ClassSession;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SheduleController extends Controller
{
    public function save(Request $request)
    {
        $data = $request->input('sessions');

        ClassSession::truncate();

        // Create new class sessions
        foreach ($data as $sessionData) {

            ClassSession::create([
                'course_id' => $sessionData['courseId'],
                'title' => $sessionData['title'],
                'description' => $sessionData['description'] ?? null,
                'start_time' => Carbon::parse($sessionData['start']), // Store full datetime
                'end_time' => Carbon::parse($sessionData['end']),
            ]);
        }

        return response()->json([
            'message' => 'Class sessions saved successfully.',
        ]);
    }

    public function save2(Request $request)
    {
        // dd($request->all());

        $validator = Validator::make($request->all(), [
            'sessions' => 'required|array',
            'sessions.*.course_id' => 'required|exists:courses,id',
            'sessions.*.title' => 'required|string',
            'sessions.*.description' => 'nullable|string',
            'sessions.*.start_time' => 'required|date',
            'sessions.*.end_time' => 'required|date|after:sessions.*.start_time',
            // Add validation for recurrence fields
            'sessions.*.recurrence_type' => 'nullable|string|in:none,daily,weekly',
            'sessions.*.weekly_days' => 'nullable|array',
            'sessions.*.weekly_days.*' => 'nullable|integer|min:0|max:6',
            'sessions.*.recurrence_end_date' => 'nullable|date|after:sessions.*.start_time',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {




      // Get list of exam IDs from request
        $submittedExamIds = collect($request->input('sessions'))
            ->filter(fn($s) => isset($s['type']) && $s['type'] === 'exam' && isset($s['exam_id']))
            ->pluck('exam_id')
            ->toArray();

        // Handle old exams that are no longer part of the request
        Exam::where('status', 'dated')
            ->whereNotIn('id', $submittedExamIds)
            ->update([
                'status' => 'submited',
                'start_time' => null,
                'end_time' => null,
            ]);




            // Clear existing sessions
            ClassSession::truncate();

            foreach ($request->input('sessions') as $sessionData) {
                // Convert times to Carbon instances
                $startTime = Carbon::parse($sessionData['start_time'], 'Africa/Casablanca');
                $endTime = Carbon::parse($sessionData['end_time'], 'Africa/Casablanca');

                // Calculate session duration in minutes

                if ($sessionData["type"] == "exam") {
                    $exam = Exam::find($sessionData["exam_id"]);


                    $exam->status = "dated";
                    $exam->start_time = Carbon::parse($sessionData['start_time'], 'Africa/Casablanca');
                    $exam->end_time = Carbon::parse($sessionData['end_time'], 'Africa/Casablanca');

                    $exam->save();


                    continue;

                }

                $this->createSession($sessionData, $startTime, $endTime);


            }

            return response()->json([
                'message' => 'Class sessions saved successfully',
                'status' => 'success'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error saving class sessions',
                'error' => $e->getMessage(),
                'status' => 'error'
            ], 500);
        }
    }

    // Helper method to create a single session
    private function createSession($sessionData, $startTime, $endTime)
    {
        $session = new ClassSession([
            'course_id' => $sessionData['course_id'],
            'title' => $sessionData['title'],
            'description' => $sessionData['description'] ?? null,
            'start_time' => $startTime->setTimezone('UTC'),
            'end_time' => $endTime->setTimezone('UTC'),
            'status' => 'scheduled',
        ]);

        $session->save();
    }

    // public function save2(Request $request)
    // {

    //     $validator = Validator::make($request->all(), [
    //         'sessions' => 'required|array',
    //         'sessions.*.course_id' => 'required|exists:courses,id',
    //         'sessions.*.title' => 'required|string',
    //         'sessions.*.description' => 'nullable|string',
    //         'sessions.*.start_time' => 'required|date',
    //         'sessions.*.end_time' => 'required|date|after:sessions.*.start_time',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 422);
    //     }

    //     try {


    //         // Clear existing sessions
    //         ClassSession::truncate();

    //         foreach ($request->input('sessions') as $sessionData) {
    //             $session = new ClassSession([
    //                 'course_id' => $sessionData['course_id'],
    //                 'title' => $sessionData['title'],
    //                 'description' => $sessionData['description'] ?? null,
    //                 'start_time' => Carbon::parse($sessionData['start_time'], 'Africa/Casablanca')->setTimezone('UTC'),
    //                 'end_time' => Carbon::parse($sessionData['end_time'], 'Africa/Casablanca')->setTimezone('UTC'),
    //                 'status' => 'scheduled',
    //             ]);

    //             $session->save();


    //         }


    //         return response()->json([
    //             'message' => 'Class sessions saved successfully',
    //             'status' => 'success'
    //         ]);

    //     } catch (\Exception $e) {

    //         return response()->json([
    //             'message' => 'Error saving class sessions',
    //             'error' => $e->getMessage(),
    //             'status' => 'error'
    //         ], 500);
    //     }
    // }




    /**
     * Send all class sessions to the frontend.
     */
    public function send()
    {
        $classSessions = ClassSession::with('course')->get()->map(function ($session) {
            return [
                'id' => $session->id,
                'course_id' => $session->course_id,
                'title' => $session->title,
                'description' => $session->description,
                'start_time' => $session->start_time->setTimezone('Africa/Casablanca'),
                'end_time' => $session->end_time->setTimezone('Africa/Casablanca'),
            ];
        });

        return response()->json($classSessions);
    }

    public function getByTeacherId($id)
    {
        $classSessions = ClassSession::whereHas('course.teacher', function ($query) use ($id) {
            $query->where('id', $id);
        })->with('course')->get()->map(function ($session) {
            return [
                'id' => $session->id,
                'course_id' => $session->course_id,
                'title' => $session->title,
                'description' => $session->description,
                'start_time' => $session->start_time,
                'end_time' => $session->end_time,
            ];
        });

        return response()->json($classSessions);
    }

    public function getByStudentId($id)
    {
        $classSessions = ClassSession::whereHas('course.level.path.enrollments', function ($query) use ($id) {
            $query->where('user_id', $id);
        })->with('course')->get()->map(function ($session) {
            return [
                'id' => $session->id,
                'course_id' => $session->course_id,
                'title' => $session->title,
                'description' => $session->description,
                'start_time' => $session->start_time,
                'end_time' => $session->end_time,
            ];
        });

        return response()->json($classSessions);
    }
}
