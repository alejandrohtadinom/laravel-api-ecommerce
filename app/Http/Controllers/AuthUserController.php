<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;

class AuthUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     *  @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): JsonResponse
    {
        $validatedData = $request->validate(
            [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users|max:255',
                'password' => 'required|confirmed|max:255',
            ]
        );

        $user = User::create(
            [
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
            ]
        );

        $token = $user->createToken($validatedData['password']);

        return response()->json(
            [
                'user' => $user,
                'token' => $token->plainTextToken
            ],
            201
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $user = $request->user();
        return response()->json($user, 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $user = $request->user();

        $validatedData = $request->validate([
            'name' => 'string|max:255',
            'email' => 'email|max:255',
            'password' => 'confirmed',
        ]);

        $user->fill($validatedData);
        $user->save();

        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $user = $request->user();
        $user->delete();
        return response()->json($user);
    }

    /**
     * Login the user.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $validatedData = $request->validate(
            [
                'email' => 'required|email',
                'password' => 'required',
            ]
        );

        if (!Auth::attempt($validatedData)) {
            return response()->json(
                [
                    'error' => 'Invalid credentials'
                ],
                401
            );
        } else {
            $user = Auth::user();
            $token = $user->createToken($validatedData['password'])->plainTextToken;
            return response()->json(
                [
                    'token' => $token,
                ],
                200
            );
        }
    }

    /**
     * Logout the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();
        return response()->json(
            [
                'message' => 'Loged out'
            ],
            205
        );
    }
}
