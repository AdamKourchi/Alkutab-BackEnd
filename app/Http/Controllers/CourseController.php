<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use App\Models\Course;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Course::all()->load("teacher", 'level.path', 'classSessions');
        
        return response()->json($courses);
    }

    public function getTeacherCourses($teacher_id){
    $courses = Course::where("user_id", $teacher_id)
        ->with(['teacher', 'level.path', 'classSessions'])
        ->get();

    return response()->json($courses);    }

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
    public function store(StoreCourseRequest $request)
    {

        $data = $request->only(['level_id', 'title', 'description', 'user_id']);

        $course = Course::create($data);


        return response()->json([
            'message' => 'Course created successfully',
            'course' => $course,
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $course = Course::find($id);
        
        return response()->json($course);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCourseRequest $request, Course $course)
    {
        $data = $request->validated();

        $course->update($data);

        return response()->json([
            'message' => 'Course updated successfully',
            'course' => $course,
        ]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $course = Course::findOrFail($id);


        $course->delete();


        return response()->json([
            'message' => 'Course deleted successfully',
        ]);
    }
}
