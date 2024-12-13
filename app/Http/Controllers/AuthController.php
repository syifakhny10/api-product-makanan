<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/register",
     *     summary="Register user",
     *     description="Mendaftarkan user baru.",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "email", "password"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="johndoe@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User successfully registered",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="object",
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="John Doe"),
     *                 @OA\Property(property="email", type="string", example="johndoe@example.com"),
     *                 @OA\Property(property="created_at", type="string", example="2024-01-01T00:00:00.000000Z"),
     *                 @OA\Property(property="updated_at", type="string", example="2024-01-01T00:00:00.000000Z")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error"
     *     )
     * )
     */


     public function register(Request $request)
     {
         $request->validate([
             'name' => 'required|string|max:255',
             'email' => 'required|string|email|max:255|unique:users',
             'password' => 'required|string|min:8',
         ]);

         $user = User::create([
             'name' => $request->name,
             'email' => $request->email,
             'password' => Hash::make($request->password),
         ]);

         return response()->json(['user' => $user], 201);
     }



     /**
      * @OA\Post(
      *     path="/login",
      *     summary="Login user",
      *     description="Login user dan mendapatkan token akses.",
      *     tags={"Authentication"},
      *     @OA\RequestBody(
      *         required=true,
      *         @OA\JsonContent(
      *             required={"email", "password"},
      *             @OA\Property(property="email", type="string", format="email", example="johndoe@example.com"),
      *             @OA\Property(property="password", type="string", format="password", example="password123")
      *         )
      *     ),
      *     @OA\Response(
      *         response=200,
      *         description="Login berhasil",
      *         @OA\JsonContent(
      *             @OA\Property(property="token", type="string", example="eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9...")
      *         )
      *     ),
      *     @OA\Response(
      *         response=422,
      *         description="Validation error"
      *     ),
      *     @OA\Response(
      *         response=401,
      *         description="Invalid credentials"
      *     )
      * )
      */

     public function login(Request $request)
     {
         $request->validate([
             'email' => 'required|email',
             'password' => 'required',
         ]);

         $user = User::where('email', $request->email)->first();

         if (!$user || !Hash::check($request->password, $user->password)) {
             throw ValidationException::withMessages([
                 'email' => ['The provided credentials are incorrect.'],
             ]);
         }

         return response()->json(['token' => $user->createToken('API Token')->plainTextToken]);
     }

     /**
      * @OA\Post(
      *     path="/logout",
      *     summary="Logout user",
      *     description="Logout user dan menghapus token akses saat ini.",
      *     tags={"Authentication"},
      *     security={{"sanctum":{}}},
      *     @OA\Response(
      *         response=200,
      *         description="Logout berhasil",
      *         @OA\JsonContent(
      *             @OA\Property(property="message", type="string", example="Logged out")
      *         )
      *     ),
      *     @OA\Response(
      *         response=401,
      *         description="Unauthorized"
      *     )
      * )
      */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out'], 200);
    }
}
