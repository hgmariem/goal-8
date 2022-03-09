<?php


namespace App\Http\Middleware;

use App\Helper\Helpers;
use Closure;


class VerifyToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $token = $request->post("token");
        $userDe =  AuthenticateToken($token);

        if (!isset($token) && empty($token)) {
            return response()->json(["data"=>'',"error"=>'Please enter Token',"status"=>0,"msg"=>'']);
        }

        if (!isset($userDe) && empty($userDe)) {
            return response()->json(["data"=>'',"error"=>'Please enter valid Token',"status"=>0,"msg"=>'']);
        }
        return $next($request);
    }
}

?>