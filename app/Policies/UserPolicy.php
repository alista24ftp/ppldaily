<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether user can view target user's info.
     *
     * @param App\Models\User $user
     * @param App\Models\User $targetUser
     * @return mixed
     */
    public function userInfoView(User $user, User $targetUser)
    {
        return $user->id == $targetUser->id ?
            $user->hasPermissionTo('user_info_own_view')
            : $user->hasPermissionTo('user_info_others_view') && $user->isSuperiorTo($targetUser);
    }

    /**
     * Determine whether user can edit target user's info.
     *
     * @param App\Models\User $user
     * @param App\Models\User $targetUser
     * @return mixed
     */
    public function userInfoEdit(User $user, User $targetUser)
    {
        return $user->id == $targetUser->id ?
            $user->hasPermissionTo('user_info_own_edit')
            : $user->hasPermissionTo('user_info_others_edit') && $user->isSuperiorTo($targetUser);
    }

    /**
     * Determine whether user can manually create a user.
     *
     * @param App\Models\User $user
     * @return mixed
     */
    public function userCreate(User $user)
    {
        return $user->hasPermissionTo('user_create');
    }

    /**
     * Determine whether user can delete the target user.
     *
     * @param App\Models\User $user
     * @param App\Models\User $targetUser
     * @return mixed
     */
    public function userDelete(User $user, User $targetUser)
    {
        return $user->id != $targetUser->id && $user->hasPermissionTo('user_delete') && $user->isSuperiorTo($targetUser);
    }

    /**
     * Determine whether user can ban the target user.
     *
     * @param App\Models\User $user
     * @param App\Models\User $targetUser
     * @return mixed
     */
    public function userBan(User $user, User $targetUser)
    {
        return $user->id != $targetUser->id && $user->hasPermissionTo('user_ban') && $user->isSuperiorTo($targetUser);
    }

    /**
     * Determine whether user can unban the target user.
     *
     * @param App\Models\User $user
     * @param App\Models\User $targetUser
     * @return mixed
     */
    public function userUnban(User $user, User $targetUser)
    {
        return $user->id != $targetUser->id && $user->hasPermissionTo('user_unban') && $user->isSuperiorTo($targetUser);
    }

    /**
     * Determine whether user can promote the target user.
     *
     * @param App\Models\User $user
     * @param App\Models\User $targetUser
     * @return mixed
     */
    public function userPromote(User $user, User $targetUser)
    {
        return $user->id != $targetUser->id && $user->hasPermissionTo('user_promote') && $user->isSuperiorTo($targetUser);
    }

    /**
     * Determine whether user can demote the target user.
     *
     * @param App\Models\User $user
     * @param App\Models\User $targetUser
     * @return mixed
     */
    public function userDemote(User $user, User $targetUser)
    {
        return $user->id != $targetUser->id && $user->hasPermissionTo('user_demote') && $user->isSuperiorTo($targetUser);
    }

    /**
     * Determine whether user can grant permissions to the target user.
     *
     * @param App\Models\User $user
     * @param App\Models\User $targetUser
     * @return mixed
     */
    public function permissionGrant(User $user, User $targetUser)
    {
        return $user->id != $targetUser->id && $user->hasPermissionTo('permission_grant')
            && $user->isSuperiorTo($targetUser);
    }

    /**
     * Determine whether user can revoke permissions from the target user.
     *
     * @param App\Models\User $user
     * @param App\Models\User $targetUser
     * @return mixed
     */
    public function permissionRevoke(User $user, User $targetUser)
    {
        return $user->id != $targetUser->id && $user->hasPermissionTo('permission_revoke')
            && $user->isSuperiorTo($targetUser);
    }

    /**
     * Determine whether user can access admin dashboard.
     *
     * @param App\Models\User $user
     * @return mixed
     */
    public function adminDashboard(User $user)
    {
        return $user->hasPermissionTo('admin_dashboard');
    }

    /**
     * Determine whether user can approve registrations.
     *
     * @param App\Models\User $user
     * @return mixed
     */
    public function registrationApprove(User $user)
    {
        return $user->hasPermissionTo('registration_approve');
    }

    /**
     * Determine whether user can view the user's liked list.
     *
     * @param App\Models\User $user
     * @param App\Models\User $targetUser
     * @return mixed
     */
    public function likedListView(User $user, User $targetUser)
    {
        return $user->id == $targetUser->id ?
            $user->hasPermissionTo('liked_list_own_view')
            : $user->hasPermissionTo('liked_list_others_view') && $user->isSuperiorTo($targetUser);
    }

    /**
     * Determine whether user can remove the user's liked list.
     *
     * @param App\Models\User $user
     * @param App\Models\User $targetUser
     * @return mixed
     */
    public function likedListRemove(User $user, User $targetUser)
    {
        return $user->id == $targetUser->id ?
            $user->hasPermissionTo('liked_list_own_remove')
            : $user->hasPermissionTo('liked_list_others_remove') && $user->isSuperiorTo($targetUser);
    }

    /**
     * Determine whether user can view the user's viewed article list.
     *
     * @param App\Models\User $user
     * @param App\Models\User $targetUser
     * @return mixed
     */
    public function articleViewedListView(User $user, User $targetUser)
    {
        return $user->id == $targetUser->id ?
            $user->hasPermissionTo('article_viewed_list_own_view')
            : $user->hasPermissionTo('article_viewed_list_others_view') && $user->isSuperiorTo($targetUser);
    }

    /**
     * Determine whether user can remove the user's viweed article list.
     *
     * @param App\Models\User $user
     * @param App\Models\User $targetUser
     * @return mixed
     */
    public function articleViewedListRemove(User $user, User $targetUser)
    {
        return $user->id == $targetUser->id ?
            $user->hasPermissionTo('article_viewed_list_own_remove')
            : $user->hasPermissionTo('article_viewed_list_others_remove') && $user->isSuperiorTo($targetUser);
    }
}
