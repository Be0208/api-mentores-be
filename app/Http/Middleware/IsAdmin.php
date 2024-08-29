<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(Auth::check() && Auth::user()->role == 'Admin') { //verifica se o usuario é user ou admin, se for adm ele vai prosseguir
            return $next($request); //faz com que o middleware passa pra conseguir acessar a rota, vai pra requisição
        }

        return response()->json(["success" => false, 'message' => 'Rota apenas de adiministrador', 'error' => 'Unauthorized'], Response::HTTP_FORBIDDEN);
    }

}
