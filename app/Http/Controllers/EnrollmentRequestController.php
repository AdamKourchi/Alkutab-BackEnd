<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EnrollmentRequest;
use App\Models\Enrollment;
use App\Models\Path;
use App\Models\Record;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Routing\Requirement\EnumRequirement;



class EnrollmentRequestController extends Controller
{


 
    public function index()
    {
        $user = auth()->user();
        //check that the user have admin role
        if ($user->hasRole('admin')) {

            $requests = EnrollmentRequest::with('path', 'user')
                ->whereNotIn('status', ['approved', 'rejected'])
                ->get();
            return response()->json($requests);
        }
        return response()->json(['error' => 'Not Authorized']);
    }


    public function store(Request $request)
    {

        $student = auth()->user();

        $request_enrollment = $request->request_enrollment;

        $path_id = $request->path_id;

        $path = Path::find(id: $path_id);

        $enrollment_request = EnrollmentRequest::where('user_id', $student->id)->where("status", "!=", "rejected")->first();

        if ($enrollment_request) {
            return response()->json([
                'message' => 'Already have a request.',
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


            $enrollment_request = EnrollmentRequest::create([
                'user_id' => auth()->id(),
                'path_id' => $path->id,
                'age' => $request_enrollment["age"],
                'goal' => $request_enrollment["goal"],
                'education_level' => $request_enrollment["education_level"],
                'memorization_capability' => $request_enrollment["memorization_capability"],
                'status' => $request_enrollment["status"],
                'type' => $request_enrollment["type"],
                'memorize_quran' => $request_enrollment["memorize_quran"] ?? false,


            ]);

            return response()->json($enrollment_request);
        } else {

            // $firstLevel = $path->levels()->orderBy('order', 'asc')->first();
            // $enrollment = Enrollment::create([
            //     'user_id' => auth()->id(),
            //     'path_id' => $path->id,
            //     'level_id' => $firstLevel->id,
            //     'enrolled_at' => now(),
            //     'status' => 'waiting_for_admission',

            // ]);

            $enrollment_request = EnrollmentRequest::create([
                'user_id' => auth()->id(),
                'path_id' => $path->id,
                'age' => $request_enrollment["age"],
                'goal' => $request_enrollment["goal"],
                'education_level' => $request_enrollment["education_level"],
                'memorization_capability' => $request_enrollment["memorization_capability"],
                'status' => $request_enrollment["status"],
                'type' => $request_enrollment["type"],
                'memorize_quran' => $request_enrollment["memorize_quran"] ?? false,

            ]);

            return response()->json($enrollment_request);
        }
    }



    public function approveRequest($request_id,$selected_circle_id = null)
    {
        $enrollment_request = EnrollmentRequest::find($request_id);

        if (!$enrollment_request) {
            return response()->json(['message' => 'Enrollment request not found'], 404);
        }

        $path = Path::find($enrollment_request->path_id);

        if (!$path) {
            return response()->json(['message' => 'Path not found'], 404);
        }
        
        $enrollmentController = new EnrollmentController();

        $enrollmentController->enroll($path->id, $enrollment_request->user_id, $selected_circle_id);

        $enrollment_request->status = 'approved';
        $enrollment_request->save();

        return response()->json(['message' => 'Enrollment request approved successfully']);
    }

    public function rejectRequest($request_id)
    {
        $enrollment_request = EnrollmentRequest::find($request_id);

        if (!$enrollment_request) {
            return response()->json(['message' => 'Enrollment request not found'], 404);
        }

        $enrollment_request->status = 'rejected';
        $enrollment_request->save();



        return response()->json(['message' => 'Enrollment request rejected successfully']);
    }


    public function getRequestsByStudentId($id)
    {
        $enrollment_requests = EnrollmentRequest::where('user_id', $id)
            ->orderBy('created_at', 'desc')
            ->with('path')
            ->get();

        return response()->json($enrollment_requests);
    }
}
