<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreClassSessionRequest;
use App\Http\Requests\UpdateClassSessionRequest;
use App\Models\ClassSession;

class ClassSessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StoreClassSessionRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $classSession = ClassSession::find($id);
        //load courses and teacher 
        $classSession->load('course.teacher');
        $classSession->course->teacher = $classSession->course->teacher->only('name');
        if (!$classSession) {
            return response()->json(['message' => 'Class session not found'], 404);
        }

        return response()->json($classSession);
    
    
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClassSession $classSession)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateClassSessionRequest $request, ClassSession $classSession)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClassSession $classSession)
    {
        //
    }
}
