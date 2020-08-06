<?php
namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Traits\AuthProxy;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\RefreshTokenRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Auth\AuthenticationException;

class AuthController extends Controller
{
    use AuthProxy;

    public function login(Request $request)
    {
        // 1. Validate request params
        $this->validate($request, [
            'username' => 'required|max:255',
            'password' => 'required'
        ]);
        // 2. Get tokens using password grant client
        $user = User::where('username', $request->username)->first();
        if(!$user || !Hash::check($request->password, $user->password)) {
            throw new AuthenticationException('Invalid credentials');
        }
        if(empty($user->email_verified_at) || !empty($user->user_blacklisted)){
            throw new AuthenticationException('User is not verified');
        }
        $tokenInfo = $this->getToken($request);
        // 3. Generate cookies for tokens
        $this->generateCookies($tokenInfo);
        // 4. Store access token and refresh tokens inside Redis cache
        // 5. Update user's last active time (Move to another endpoint, with bearer token)
        /*
        \Auth::guard('api')->user()->update([
            'last_active_time' => Carbon::now()
        ]);
        */
        // 6. Return response with cookies
        return response('Login Successful');
    }

    public function register(Request $request)
    {
        // 1. Validate request params
        $this->validate($request, [
            'username' => 'required|max:255|unique:users,username',
            'email' => 'required|max:255|unique:users,email',
            'password' => 'required|min:6|confirmed',
            //'password_confirmation' => 'required|confirmed',
            'captcha_key' => 'required',
            'captcha' => 'required',
        ]);
        // 2. Check captcha content
        $captcha = Cache::get($request->captcha_key);
        if(!$captcha){
            return response('Captcha expired', 422);
        }
        if(!hash_equals($captcha['captcha_phrase'], $request->captcha)){
            Cache::forget($request->captcha_key);
            return response('Invalid captcha', 401);
        }
        // 3. Create new user with indicated params and insert into DB
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'last_active_time' => Carbon::now(),
            'first_name' => $request->username,
        ]);
        // 4. Return response with newly created user
        return response()->json($user->toArray(), 201);
    }

    public function refresh(Request $request)
    {
        // TODO
    }

    public function logout(Request $request, RefreshTokenRepository $refreshTokens)
    {
        // 1. Remove Bearer token header
        $request->headers->remove('Authorization');
        // 2. Revoke current refresh token
        $accessToken = $request->user('api')->token();
        $refreshTokens->revokeRefreshTokensByAccessTokenId($accessToken->id);
        // 3. Revoke current access token
        $accessToken->revoke();
        // 4. Delete all token cookies
        $this->deleteCookies();
        // 5. Return response indicating successful logout
        return response()->json(null, 204);
    }
}
