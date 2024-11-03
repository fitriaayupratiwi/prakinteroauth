<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
public function register(Request $request)
{
$request->validate([
'name' => 'required|string|max:255',
'email' => 'required|string|email|max:255|unique:users',
'password' => 'required|string|min:8|confirmed',
]);
$user = User::create([
'name' => $request->name,
'email' => $request->email,
'password' => Hash::make($request->password),
'role' => 'user', // default role
]);
return response()->json(['message' => 'User registered
successfully.'], 201);
}
public function login(Request $request)
{
$request->validate([
'email' => 'required|string|email',
'password' => 'required|string',
]);
$user = User::where('email', $request->email)->first();
if (!$user || !Hash::check($request->password, $user->password)) {
throw ValidationException::withMessages([
'email' => ['The provided credentials are incorrect.'],
]);
}
$token = $user->createToken('auth_token')->plainTextToken;
return response()->json(['token' => $token], 200);
}
public function updateProfile(Request $request)
    {
        $user = Auth::user();


        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user->name = $request->input('name');
        $user->email = $request->input('email');

        if ($user instanceof User) {
            $user->save();
        } else {
            return response()->json(['message' => 'User is not an instance of User'], 500);
        }

        return response()->json(['message' => 'Profile updated successfully', 'user' => $user], 200);
    }

    public function deleteUser(Request $request, $id)
    {

        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }


        if ($user->id === Auth::id()) {
            return response()->json(['message' => 'You cannot delete your own account'], 403);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }

    public function getAllUsers()
    {
        // Ambil semua pengguna dari database
        $users = User::all();

        return response()->json(['users' => $users], 200);
    }
}


