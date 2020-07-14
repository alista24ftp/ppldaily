<?php
namespace App\Traits;

use GuzzleHttp\Client;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;

trait AuthProxy
{
    public function getToken($request)
    {
        try{
            $response = Http::asForm()->post(route('passport.token'), [
                'grant_type' => 'password',
                'client_id' => env('PASSPORT_PASSWORD_GRANT_CLIENT_ID'),
                'client_secret' => env('PASSPORT_PASSWORD_GRANT_CLIENT_SECRET'),
                'username' => $request->username,
                'password' => $request->password,
                'scope' => '',
            ]);
            return json_decode((string) $response->getBody(), true);
        }catch(\Exception $e){
            throw new AuthenticationException('Invalid credentials');
        }
    }

    public function refreshToken($refresh_token)
    {
        try{
            $response = Http::asForm()->post(route('passport.token'), [
                'form_params' => [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $refresh_token,
                    'client_id' => env('PASSPORT_PASSWORD_GRANT_CLIENT_ID'),
                    'client_secret' => env('PASSPORT_PASSWORD_GRANT_CLIENT_SECRET'),
                    'scope' => ''
                ],
            ]);
            return json_decode((string) $response->getBody(), true);
        }catch(\Exception $e){
            return null;
        }
    }

    public function generateCookies($tokenInfo)
    {
        // Delete existing token cookies
        $this->deleteCookies();
        // Respond with error if token contains error
        if(array_key_exists('error', $tokenInfo)) throw new AuthenticationException('Invalid credentials');
        // Split JWT token into 2 parts: header.payload and signature
        $tokenParts = explode('.', $tokenInfo['access_token']);
        $tokenSign = array_pop($tokenParts);
        $tokenPayload = implode('.', $tokenParts);
        // Create cookie and store header.payload portion inside it
        Cookie::queue(
            'token_payload',
            $tokenPayload,
            (int) ($tokenInfo['expires_in'] / 60),
            config('session.path'),
            config('session.domain'),
            config('session.secure'),
            false,
            false,
            config('session.same_site')
        );
        // Create HTTP-only cookie and store signature portion inside it
        Cookie::queue(
            'token_sign',
            $tokenSign,
            (int) ($tokenInfo['expires_in'] / 60),
            config('session.path'),
            config('session.domain'),
            config('session.secure'),
            true,
            false,
            config('session.same_site')
        );
        // Create HTTP-only cookie and store refresh token inside it
        Cookie::queue(
            'token_refresh',
            $tokenInfo['refresh_token'],
            120,
            config('session.path'),
            config('session.domain'),
            config('session.secure'),
            true,
            false,
            config('session.same_site')
        );
    }

    public function deleteCookies()
    {
        Cookie::queue(Cookie::forget('token_payload', config('session.path'), config('session.domain')));
        Cookie::queue(Cookie::forget('token_sign', config('session.path'), config('session.domain')));
        Cookie::queue(Cookie::forget('token_refresh', config('session.path'), config('session.domain')));
    }
}
