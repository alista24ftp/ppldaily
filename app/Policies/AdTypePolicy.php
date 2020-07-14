<?php

namespace App\Policies;

use App\Models\User;
use App\Models\AdType;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdTypePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether user can add a new ad type.
     *
     * @param App\Models\User $user
     * @return mixed
     */
    public function adTypeAdd(User $user)
    {
        return $user->hasPermissionTo('ad_type_add');
    }

    /**
     * Determine whether user can edit the ad type.
     *
     * @param App\Models\User $user
     * @param App\Models\AdType $adType
     * @return mixed
     */
    public function adTypeEdit(User $user, AdType $adType)
    {
        return $user->hasPermissionTo('ad_type_edit');
    }

    /**
     * Determine whether user can delete the ad type.
     *
     * @param App\Models\User $user
     * @param App\Models\AdType $adType
     * @return mixed
     */
    public function adTypeDelete(User $user, AdType $adType)
    {
        return $user->hasPermissionTo('ad_type_delete');
    }

    /**
     * Determine whether user can enable the ad type.
     *
     * @param App\Models\User $user
     * @param App\Models\AdType $adType
     * @return mixed
     */
    public function adTypeEnable(User $user, AdType $adType)
    {
        return $user->hasPermissionTo('ad_type_enable');
    }

    /**
     * Determine whether user can disable the ad type.
     *
     * @param App\Models\User $user
     * @param App\Models\AdType $adType
     * @return mixed
     */
    public function adTypeDisable(User $user, AdType $adType)
    {
        return $user->hasPermissionTo('ad_type_disable');
    }
}
