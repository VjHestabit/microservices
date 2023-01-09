<?php

namespace App\Http\Middleware;

use App\Helpers\CustomHelper;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$role)
    {
        try{
            $aUser = JWTAuth::parseToken()->authenticate();
            if(isset($aUser) && $role && in_array($aUser['role_id'],$role)){
                return $next($request);
            }
        }catch(Exception $e){
            return response()->json(['success' => false,'message' => "You are not authorized"]);
        }
        return $next($request);
    }
}
