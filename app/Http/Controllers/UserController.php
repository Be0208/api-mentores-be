<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class UserController extends Controller
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
            $request->validate([
                "name" => "required|string",
                "email" => "required|email|unique:users,email",
                "password" => "required|string"
            ],[
                'required' => 'O campo :attribute é obrigatório para proseguir.',
                'string' => 'O campo :attribute precisa ser uma string.',
                'email' => 'O campo :attribute precisa ser um email valido.',
                'uniique' => 'O campo :attribute ja está em uso.'
            ]);

            $data = $request->all();
            $data['password'] = Hash::make($data['password']);

            $user = User::create($data);

            return response()->json([
                'data' => $user,
                'message' => 'Usário criado com sucesso',
                'success' => true
            ], Response::HTTP_CREATED);
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
