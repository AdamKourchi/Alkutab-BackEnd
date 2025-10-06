<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    function profile()
    {
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
        //
    }
}
