<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Comment;
use Illuminate\Auth\Access\HandlesAuthorization;

class CommentPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether user can post a comment.
     *
     * @param App\Models\User $user
     * @return mixed
     */
    public function commentPost(User $user)
    {
        return $user->hasPermissionTo('comment');
    }

    /**
     * Determine whether user can edit the comment.
     *
     * @param App\Models\User $user
     * @param App\Models\Comment $comment
     * @return mixed
     */
    public function editComment(User $user, Comment $comment)
    {
        return $user->id == $comment->user_id && $user->hasPermissionTo('comment_own_edit');
    }

    /**
     * Determine whether user can delete the comment.
     *
     * @param App\Models\User $user
     * @param App\Models\Comment $comment
     * @return mixed
     */
    public function deleteComment(User $user, Comment $comment)
    {
        return $user->id == $comment->user_id ?
            $user->hasPermissionTo('comment_own_delete')
            : $user->hasPermissionTo('comment_others_delete') && $user->isSuperiorTo($comment->author);
    }
}

