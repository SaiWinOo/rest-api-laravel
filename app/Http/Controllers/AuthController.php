<?php

namespace App\Http\Controllers;

use App\Http\Resources\AddressResource;
use App\Http\Resources\UserResource;
use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8',
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 1,
        ]);
        Mail::to($request->email)->send(new WelcomeMail($request->name));
        return response()->json([
            'success' => true,
            'message' => 'Registration is successful',
        ]);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email|exists:users',
            'password' => 'required|min:8',
        ]);
        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'message' => ['The provided credentials are not correct!']
            ]);
        }

        $token = Auth::user()->createToken('laptop')->plainTextToken;
        return response()->json([
            'success' => true,
            'message' => 'You are logged in!',
            'token' => $token,
            'user' => new UserResource(Auth::user()),
            'address' =>Auth::user()->address,
        ]);

    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'success' => true,
            'message' => 'You logged out successfully',
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = User::findOrFail(Auth::id());
        $request->validate([
            'name' => 'nullable|string',
            'email' => "email|unique:users,email," . $user->id,
            'bio' => 'nullable',
            'profile_image' => 'nullable',
        ]);
        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('email')) {
            $user->email = $request->email;
        }
        if ($request->has('bio')) {
            $user->bio = $request->bio;
        }
        if ($request->has('profile_image')) {
            $url = $request->file('profile_image')->store('public/profile');
            $user->profile_image = $url;
        }
        $user->update();
        $user->refresh();
        return response()->json([
            'success' => true,
            'message' => 'Profile changes saved!',
            'user' => new UserResource($user),
        ]);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|current_password',
            'new_password' => 'required|min:8|confirmed',
        ]);
        $user = User::findOrFail(Auth::id());
        $user->password = Hash::make($request->new_password);
        $user->update();
        return response()->json([
            'success' => true,
            'message' => 'Password Saved!',
        ]);
    }

}
