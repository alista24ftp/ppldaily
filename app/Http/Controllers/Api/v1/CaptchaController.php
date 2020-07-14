<?php
namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Gregwar\Captcha\CaptchaBuilder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class CaptchaController extends Controller
{
    public function store(Request $request, CaptchaBuilder $captchaBuilder)
    {
        $captcha_key = 'captcha-' . Str::random();
        $captcha = $captchaBuilder->build();
        $expires_at = Carbon::now()->addMinutes(2);
        Cache::put($captcha_key, ['captcha_phrase' => $captcha->getPhrase()], $expires_at);
        return response()->json([
            'captcha_key' => $captcha_key,
            'expires_at' => $expires_at->toDateTimeString(),
            'captcha_content' => $captcha->inline()
        ], 201);
    }
}
