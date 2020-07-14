<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Ad;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether user can create new ad.
     *
     * @param App\Models\User $user
     * @return mixed
     */
    public function adCreate(User $user)
    {
        return $user->hasPermissionTo('ad_create');
    }

    /**
     * Determine whether user can edit the ad.
     *
     * @param App\Models\User $user
     * @param App\Models\Ad $ad
     * @return mixed
     */
    public function adEdit(User $user, Ad $ad)
    {
        return $user->hasPermissionTo('ad_edit');
    }

    /**
     * Determine whether user can delete the ad.
     *
     * @param App\Models\User $user
     * @param App\Models\Ad $ad
     * @return mixed
     */
    public function adDelete(User $user, Ad $ad)
    {
        return $user->hasPermissionTo('ad_delete');
    }

    /**
     * Determine whether user can enable the ad.
     *
     * @param App\Models\User $user
     * @param App\Models\Ad $ad
     * @return mixed
     */
    public function adEnable(User $user, Ad $ad)
    {
        return $user->hasPermissionTo('ad_enable');
    }

    /**
     * Determine whether user can disable the ad.
     *
     * @param App\Models\User $user
     * @param App\Models\Ad $ad
     * @return mixed
     */
    public function adDisable(User $user, Ad $ad)
    {
        return $user->hasPermissionTo('ad_disable');
    }
}
