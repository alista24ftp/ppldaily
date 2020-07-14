<?php
namespace App\Http\Middleware;

use Closure;

class AttachBearerToken
{
    public function handle($request, Closure $next)
    {
        if(!$request->headers->has('Authorization')){
            // Get cookies from incoming request and read contents
            $jwt_payload = $request->cookie('token_payload');
            $jwt_sign = $request->cookie('token_sign');
            //$refresh_token = $request->cookie('token_refresh_token');
            //dd($jwt_payload);

            if(!is_null($jwt_payload) && !is_null($jwt_sign)){
                // Piece together the original JWT by using the header.payload + signature cookies
                $jwt = $jwt_payload . '.' . $jwt_sign;
                // Put this token into the request authorization header "Bearer"
                $request->headers->set('Authorization', 'Bearer ' . $jwt);
                //$request->headers->add(['Authorization' => 'Bearer ' . $jwt]);
            }
        }

        return $next($request);
    }
}
