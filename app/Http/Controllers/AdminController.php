<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
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
}
