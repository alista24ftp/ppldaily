<?php

namespace App\Policies;

use App\Models\User;
use App\Models\SiteInfo;
use Illuminate\Auth\Access\HandlesAuthorization;

class SiteInfoPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether user can manage the site info.
     *
     * @param App\Models\User $user
     * @param App\Models\SiteInfo $info
     * @return mixed
     */
    public function siteInfoManage(User $user, SiteInfo $info)
    {
        return $user->hasPermissionTo('site_info_manage');
    }
}
