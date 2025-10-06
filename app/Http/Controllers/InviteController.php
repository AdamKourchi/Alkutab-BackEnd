<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Str;
use App\Mail\TeacherInviteMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;

class InviteController extends Controller
{
    public function inviteTeacher(Request $request)
    {
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'invite_token' => Str::random(64),
            'invite_expires_at' => now()->addHours(24),
            'password'=> bcrypt("password"),
        ]);

        $user->assignRole('teacher');

        Mail::to($user->email)->send(new TeacherInviteMail($user));
    }

    public function acceptInvite(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $user = User::where('invite_token',  $request->token)
            ->where('invite_expires_at', '>', now())
            ->firstOrFail();

        $user->password = Hash::make($request->password);
        $user->invite_token = null;
        $user->invite_expires_at = null;
        $user->save();

        return response()->json(['message' => 'Account activated']);
    }

}
