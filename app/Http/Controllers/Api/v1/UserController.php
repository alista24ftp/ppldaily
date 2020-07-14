<?php
namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function userInfo(Request $request, User $user)
    {
        $this->authorize('userInfoView', $user);
        $userInfo = $user->toArray();
        $userInfo['roles'] = $user->roles->pluck('name')->toArray();
        $userInfo['permissions'] = $user->getAllPermissions()->pluck('name')->toArray();
        return response()->json($userInfo);
    }
}
