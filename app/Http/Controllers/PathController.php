<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePathRequest;
use App\Http\Requests\UpdatePathRequest;
use App\Models\Path;
use App\Models\User;

use Illuminate\Http\Request;

use Carbon\Carbon;


class PathController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $paths = Path::all();
        $paths = Path::with(['levels', 'enrollments', "circles.teacher", 'levels.courses.teacher', 'circles'])->get();
        return response()->json($paths);
    }

    public function getTeacherPaths($teacher_id)
    {


        $Generalpaths = Path::whereHas('levels.courses', function ($q) use ($teacher_id) {
            $q->where('user_id', operator: $teacher_id);
        })->with([
                    'levels' => function ($q) use ($teacher_id) {
                        $q->whereHas('courses', function ($q) use ($teacher_id) {
                            $q->where('user_id', $teacher_id);
                        });
                    },
                    'levels.courses' => function ($q) use ($teacher_id) {
                        $q->where('user_id', $teacher_id);
                    }
                ])->get();


        $hifdPaths = $paths = Path::whereHas('circles', function ($q) use ($teacher_id) {
            $q->where('user_id', operator: $teacher_id);
        })->with('circles')
            ->get();



        $paths = $Generalpaths->merge($hifdPaths);


        return response()->json($paths);
    }

    public function student_current_path($student_id)
    {
        $user = User::find($student_id);

        $enrollment = $user->enrollments()
            ->where('graduated', 0)
            ->with('path.levels.courses.classSessions', 'path.enrollments')
            ->first();

        if (!$enrollment || !$enrollment->path) {
            return response()->json(null); // or some 404/empty fallback
        }

        return response()->json($enrollment->path);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request['created_by'] = auth()->id();

        // Parse start_at and end_at dates
        $request['start_at'] = Carbon::parse($request['start_at'])->format('Y-m-d H:i:s');
        $request['end_at'] = Carbon::parse($request['end_at'])->format('Y-m-d H:i:s');

        // Create the path
        $path = Path::create($request->all());

        // Return JSON (or redirect)
        return response()->json([
            'message' => 'Path created successfully.',
            'data' => $path
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Path $path)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Path $path)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {


        $path = Path::findOrFail($id);

        // Parse start_at and end_at dates
        $request['start_at'] = Carbon::parse($request['start_at'])->format('Y-m-d H:i:s');
        $request['end_at'] = Carbon::parse($request['end_at'])->format('Y-m-d H:i:s');

        // Optional: Add the user who created it
        $request['created_by'] = auth()->id(); // or $request->user()->id


        // Update the path with validated data
        $path->update($request->all());

        if ($request->has('levels')) {

            $newLevelIds = collect($request->levels)->pluck('id')->filter()->toArray();

            // Delete levels that are not in the new data
            $path->levels()->whereNotIn('id', $newLevelIds)->delete();

            foreach ($request->levels as $levelData) {

                $levelData['start_at'] = Carbon::parse($levelData['start_at'])->format('Y-m-d H:i:s');
                $levelData['end_at'] = Carbon::parse($levelData['end_at'])->format('Y-m-d H:i:s');
                // Check if the level exists
                $level = $path->levels()->find($levelData['id']);

                if ($level) {
                    // Update the level
                    $level->update($levelData);
                } else {
                    // Create a new level
                    $level = $path->levels()->create($levelData);
                }
            }
        }


        if ($request->has('circles')) {

            $newCirclesIds = collect($request->circles)->pluck('id')->filter()->toArray();

            // Delete circles that are not in the new data
            $path->circles()->whereNotIn('id', $newCirclesIds)->delete();

            foreach ($request->circles as $circleData) {

                $circleData['start_time'] = Carbon::parse($circleData['start_time'])->setTimezone('Africa/Casablanca')->format('Y-m-d H:i:s');

                $circleData['end_time'] = Carbon::parse($circleData['end_time'])->setTimezone('Africa/Casablanca')->format('Y-m-d H:i:s');

                $circleData['days_of_week'] = json_encode($circleData['days_of_week']);


                // Check if the level exists
                $circle = $path->circles()->find($circleData['id']);

                if ($circle) {
                    // Update the circle
                    $circle->update($circleData);
                } else {
                    // Create a new circle
                    $circle = $path->circles()->create($circleData);
                }
            }
        }




        return response()->json([
            'message' => 'Path, levels, and courses updated successfully.',
            'data' => $path->load('levels.courses') // Load levels and courses for response
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $path = Path::findOrFail($id);

        $path->delete();

        return response()->json(["message" => "Path deleted successfully."]);
    }
}
