<?php

namespace App\Http\Controllers;

use App\Models\Mentor;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class MentorController extends Controller
{

    public function index()
    {
        try {
            $mentores = Mentor::all();

            return response()->json([
                'mentores' => $mentores
            ], Response::HTTP_OK);

        } catch (QueryException $e) {
            return response()->json([
                'message' => 'Ocorreu um erro ao recuperar os dados. Por favor, tente novamente.'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function store(Request $request)
    {
        try {
            $request->validate([
                "name" => "required|string",
                "email" => "required|email|unique:mentors,email",
                "cpf" => "required|string"
            ],[
                'required' => 'O campo :attribute é obrigatório para proseguir.',
                'string' => 'O campo :attribute precisa ser uma string.',
                'email' => 'O campo :attribute precisa ser um email valido.',
                'uniique' => 'O campo :attribute ja está em uso.'
            ]);

            $data = $request->all();

            $mentor = Mentor::create($data);

            return response()->json([
                'data' => $mentor,
                'message' => 'Mentor criado com sucesso',
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


    public function show(string $id)
    {
        try {

            $mentor = Mentor::where('id', $id)->find($id);

            return response()->json(['success' => 'true', 'msg' => 'Usuário encontrado com sucesso', 'data' => $mentor]);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(Request $request, string $id)
    {
        try {

            $mentor = Mentor::findOrFail($id);

            if($request->has('name')){
                $mentor->name = $request->name;
            }
            if($request->has('email')){
                $mentor->email = $request->email;
            }

            $mentor->save();

            return response()->json(['success' => 'true', 'msg' => 'Usuário alterado com sucesso', 'data' => $mentor]);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    public function destroy(string $id)
    {
        try {
            $mentor = Mentor::findOrFail($id);
            $mentor->delete();

            return response()->json(['success' => 'true', 'msg' => 'Usuário deletado com sucesso', 'data' => $mentor]);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage(),
                'success' => false
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
}
