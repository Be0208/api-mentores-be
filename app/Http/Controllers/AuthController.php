<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate(
                [
                    'email' => 'required|email',
                    'password' => 'required|string'
                ],
                [
                    'required' => 'O campo :attribute é obrigatório.',
                    'string' => 'O campo :attribute precisa ser uma string.',
                    'email' => 'O campo :attribute precisa ser um email válido.',
                ]
            );

            $user = User::where('email', $request->email)->first();

            if(!$user || !Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email ou Senha inválidos',
                ], Response::HTTP_UNAUTHORIZED);
            };

            $token = $user->createToken($user->email)->plainTextToken;

            return response()->json([
                'data' => $user,
                'token' => $token,
                'message' => 'Login feito com sucesso',
                'success' => true
            ], Response::HTTP_OK);
        }
        catch (ValidationException $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
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
