<?php

namespace App\Policies;

use App\Models\Article;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArticlePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the article.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Article  $article
     * @return mixed
     */
    public function articleView(User $user, Article $article)
    {
        //if($user->user_blacklisted) return false;
        $permissions = $user->getAllPermissions()->pluck('name')->all();
        if($article->article_enabled && is_null($article->deleted_at)) return true;
        if($article->author_id != $user->id){
            if(!$user->isSuperiorTo($article->author)) return false;
            if(!$article->article_enabled && !is_null($article->deleted_at)){
                return in_array('article_disabled_others_view', $permissions)
                    && in_array('article_soft_deleted_others_view', $permissions);
            }
            return (!$article->article_enabled && in_array('article_disabled_others_view', $permissions))
                || (!is_null($article->deleted_at) && in_array('article_soft_deleted_others_view', $permissions));
        }
        if(!$article->article_enabled && !is_null($article->deleted_at)){
            return in_array('article_soft_deleted_own_view', $permissions)
                && in_array('article_disabled_own_view', $permissions);
        }
        return (!$article->article_enabled && in_array('article_disabled_own_view', $permissions))
            || (!is_null($article->deleted_at) && in_array('article_soft_deleted_own_view', $permissions));
    }

    /**
     * Determine whether the user can remove the viewed article.
     *
     * @param App\Models\User $user
     * @param App\Models\Article $article
     * @param App\Models\User $viewedUser
     * @return mixed
     */
    public function viewedArticleRemove(User $user, Article $article, User $viewedUser)
    {
        if($user->id == $viewedUser->id) {
            return $user->hasPermissionTo('article_viewed_own_remove') && $viewedUser->articleDislikes->contains($article->id);
        }
        return $user->hasPermissionTo('article_viewed_others_remove')
            && $viewedUser->articleDislikes->contains($article->id) && $user->isSuperiorTo($viewedUser);
    }

    /**
     * Determine whether user can remove the visitor's viewed article.
     *
     * @param App\Models\User $user
     * @param App\Models\Article $article
     * @param App\Models\Visitor $visitor
     * @return mixed
     */
    public function visitorViewedArticleRemove(User $user, Article $article, Visitor $visitor)
    {
        return $user->hasPermissionTo('article_viewed_others_remove');
    }

    /**
     * Determine whether the user can publish articles.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function articlePublish(User $user)
    {
        //return !$user->user_blacklisted && $user->hasPermissionTo('article_publish');
        return $user->hasPermissionTo('article_publish');
    }

    /**
     * Determine whether the user can edit the article.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Article  $article
     * @return mixed
     */
    public function articleEdit(User $user, Article $article)
    {
        return $user->id == $article->author_id ?
            $user->hasPermissionTo('article_own_edit')
            : $user->hasPermissionTo('article_others_edit') && $user->isSuperiorTo($article->author);
    }

    /**
     * Determine whether the user can enable the article.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Article  $article
     * @return mixed
     */
    public function articleEnable(User $user, Article $article)
    {
        return $user->id == $article->author_id ?
            $user->hasPermissionTo('article_own_enable')
            : $user->hasPermissionTo('article_others_enable') && $user->isSuperiorTo($article->author);
    }

    /**
     * Determine whether the user can disable the article.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Article  $article
     * @return mixed
     */
    public function articleDisable(User $user, Article $article)
    {
        return $user->id == $article->author_id ?
            $user->hasPermissionTo('article_own_disable')
            : $user->hasPermissionTo('article_others_disable') && $user->isSuperiorTo($article->author);
    }

    /**
     * Determine whether the user can enable likes on the article.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Article  $article
     * @return mixed
     */
    public function articleEnableLike(User $user, Article $article)
    {
        return $user->id == $article->author_id ?
            $user->hasPermissionTo('article_like_own_enable')
            : $user->hasPermissionTo('article_like_others_enable') && $user->isSuperiorTo($article->author);
    }

    /**
     * Determine whether the user can disable likes on the article.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Article  $article
     * @return mixed
     */
    public function articleDisableLike(User $user, Article $article)
    {
        return $user->id == $article->author_id ?
            $user->hasPermissionTo('article_like_own_disable')
            : $user->hasPermissionTo('article_like_others_disable') && $user->isSuperiorTo($article->author);
    }

    /**
     * Determine whether the user can like the article.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Article  $article
     * @return mixed
     */
    public function articleLike(User $user, Article $article)
    {
        return $user->hasPermissionTo('like');
    }

    /**
     * Determine whether the user can remove like from the article.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Article  $article
     * @param  \App\Models\User $likedUser
     * @return mixed
     */
    public function articleLikeRemove(User $user, Article $article, User $likedUser)
    {
        if($user->id == $likedUser->id) {
            return $user->hasPermissionTo('like_own_remove') && $likedUser->articleLikes->contains($article->id);
        }
        return $user->hasPermissionTo('like_others_remove')
            && $likedUser->articleLikes->contains($article->id) && $user->isSuperiorTo($likedUser);
    }

    /**
     * Determine whether the user can enable dislikes on the article.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Article  $article
     * @return mixed
     */
    public function articleEnableDislike(User $user, Article $article)
    {
        return $user->id == $article->author_id ?
            $user->hasPermissionTo('article_dislike_own_enable')
            : $user->hasPermissionTo('article_dislike_others_enable') && $user->isSuperiorTo($article->author);
    }

    /**
     * Determine whether the user can disable dislikes on the article.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Article  $article
     * @return mixed
     */
    public function articleDisableDislike(User $user, Article $article)
    {
        return $user->id == $article->author_id ?
            $user->hasPermissionTo('article_dislike_own_disable')
            : $user->hasPermissionTo('article_dislike_others_disable') && $user->isSuperiorTo($article->author);
    }

    /**
     * Determine whether the user can dislike the article.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Article  $article
     * @return mixed
     */
    public function articleDislike(User $user, Article $article)
    {
        return $user->hasPermissionTo('dislike');
    }

    /**
     * Determine whether the user can remove dislike from the article.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Article  $article
     * @param  \App\Models\User $dislikedUser
     * @return mixed
     */
    public function articleDislikeRemove(User $user, Article $article, User $dislikedUser)
    {
        if($user->id == $dislikedUser->id) {
            return $user->hasPermissionTo('dislike_own_remove') && $dislikedUser->articleDislikes->contains($article->id);
        }
        return $user->hasPermissionTo('dislike_others_remove')
            && $dislikedUser->articleDislikes->contains($article->id) && $user->isSuperiorTo($dislikedUser);
    }

    /**
     * Determine whether the user can enable comments on the article.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Article  $article
     * @return mixed
     */
    public function articleEnableComment(User $user, Article $article)
    {
        return $user->id == $article->author_id ?
            $user->hasPermissionTo('article_comment_own_enable')
            : $user->hasPermissionTo('article_comment_others_enable') && $user->isSuperiorTo($article->author);
    }

    /**
     * Determine whether the user can disable comments on the article.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Article  $article
     * @return mixed
     */
    public function articleDisableComment(User $user, Article $article)
    {
        return $user->id == $article->author_id ?
            $user->hasPermissionTo('article_comment_own_disable')
            : $user->hasPermissionTo('article_comment_others_disable') && $user->isSuperiorTo($article->author);
    }

    /**
     * Determine whether the user can soft delete the article.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Article  $article
     * @return mixed
     */
    public function articleSoftDelete(User $user, Article $article)
    {
        return $user->id == $article->author_id ?
            $user->hasPermissionTo('article_own_soft_delete')
            : $user->hasPermissionTo('article_others_soft_delete') && $user->isSuperiorTo($article->author);
    }

    /**
     * Determine whether the user can restore the article.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Article  $article
     * @return mixed
     */
    public function articleRestore(User $user, Article $article)
    {
        return $user->id == $article->author_id ?
            $user->hasPermissionTo('article_own_restore')
            : $user->hasPermissionTo('article_others_restore') && $user->isSuperiorTo($article->author);
    }

    /**
     * Determine whether the user can permanently delete the article.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Article  $article
     * @return mixed
     */
    public function articleHardDelete(User $user, Article $article)
    {
        return $user->hasPermissionTo('article_hard_delete') &&
            ($user->id == $article->author_id || $user->isSuperiorTo($article->author));
    }
}
