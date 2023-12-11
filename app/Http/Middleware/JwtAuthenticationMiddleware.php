<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Symfony\Component\HttpFoundation\Response;

class JwtAuthenticationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            JWTAuth::parseToken()->authenticate();
        } catch(\Tymon\JWTAuth\Exceptions\TokenInvalidException $exception){
            
            return response()->json(
                ['message' => 'Token provided is invalid'], 
                Response::HTTP_UNAUTHORIZED
            );
        } catch(\Tymon\JWTAuth\Exceptions\TokenExpiredException $exception){

            return response()->json(
                ['message' => 'Token provided is expired'], 
                Response::HTTP_UNAUTHORIZED
            );
        } catch (\Exception $e) {
           
            return response()->json(
                ['message' => 'Authorization Token not found'], 
                Response::HTTP_UNAUTHORIZED
            );
        } 

        return $next($request);
    }
}
