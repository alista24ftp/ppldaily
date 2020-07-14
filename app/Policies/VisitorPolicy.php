<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Visitor;
use Illuminate\Auth\Access\HandlesAuthorization;

class VisitorPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether user can blacklist the visitor.
     *
     * @param App\Models\User $user
     * @param App\Models\Visitor $visitor
     * @return mixed
     */
    public function visitorBlacklist(User $user, Visitor $visitor)
    {
        return $user->hasPermissionTo('user_ban');
    }

    /**
     * Determine whether user can unban the visitor.
     *
     * @param App\Models\User $user
     * @param App\Models\Visitor $visitor
     * @return mixed
     */
    public function visitorUnban(User $user, Visitor $visitor)
    {
        return $user->hasPermissionTo('user_unban');
    }

    /**
     * Determine whether user can remove the visitor's viewed article list.
     *
     * @param App\Models\User $user
     * @param App\Models\Visitor $visitor
     * @return mixed
     */
    public function visitorArticleViewedListRemove(User $user, Visitor $visitor)
    {
        return $user->hasPermissionTo('article_viewed_list_others_remove');
    }

    /**
     * Determine whether user can view the visitor's viewed article list.
     *
     * @param App\Models\User $user
     * @param App\Models\Visitor $visitor
     * @return mixed
     */
    public function visitorArticleViewedListView(User $user, Visitor $visitor)
    {
        return $user->hasPermissionTo('article_viewed_list_others_view');
    }
}
