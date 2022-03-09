<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Http\Response;
use AUth;
use League\OAuth2\Server\ResourceServer;
use League\OAuth2\Server\Exception\OAuthServerException;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;

class CheckJWTAccessToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        

        $token = $request->user()->token();
               
         // Bearer token has expired
        $today = Carbon::now();
        if ($today > $token->expires_at) {
            $request->user()->token()->revoke();
            return response()->json([
                'status'=>'Error',
                'message' => 'Token has expired',
                'data' => null
            ], Response::HTTP_FORBIDDEN);
        }
        // Bearer token is revoked
        if($request->user()->token()->revoked){
            return response()->json([
                'status'=>'Error',
                'message' => 'Token has been revoked, please login again!',
                'data' => null

            ], 419);
        }
        // return next request with decoded JWt Bearer token (id, client_id, user_id, scopes)
        $request->merge(['oauth_token' => $token]);
      
       return $next($request);
    }
}