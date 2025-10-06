<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $teachers = User::role('teacher')->with(['courses'])->get();
        
        return response()->json($teachers);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create the teacher
        $teacher = User::create($validated);

        // Assign the 'teacher' role
        $teacher->assignRole('teacher');

        // Return JSON (or redirect)
        return response()->json([
            'message' => 'Teacher created successfully.',
            'data' => $teacher
        ], 201);
    }

    function profile(){
    return response()->json(
        auth()->user()
    );
}

    function updateProfile(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'currentPassword' => ['required_with:newPassword', 'string'],
            'newPassword' => ['nullable', 'string', 'min:6'],
        ]);

        // If user wants to change password
        if (!empty($validated['newPassword'])) {
            if (!Hash::check($validated['currentPassword'], $user->password)) {
                return response()->json(['message' => 'كلمة المرور الحالية غير صحيحة'], 422);
            }

            $user->password = bcrypt($validated['newPassword']);
        }

        $user->name = $validated['name'];
        $user->save();

        return response()->json(['message' => 'تم تحديث الملف الشخصي بنجاح']);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $teacher = User::findOrFail($id);
        $teacher->removeRole('teacher');
        $teacher->delete();
        return response()->json([
            'message' => 'Teacher deleted successfully.'
        ]);
    }
}
