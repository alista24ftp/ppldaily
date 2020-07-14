<?php

namespace App\Policies;

use App\Models\User;
use App\Models\ArticleCategory;
use Illuminate\Auth\Access\HandlesAuthorization;

class ArticleCategoryPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether user can add a new article category.
     *
     * @param App\Models\User $user
     * @return mixed
     */
    public function articleCategoryAdd(User $user)
    {
        return $user->hasPermissionTo('article_category_add');
    }

    /**
     * Determine whether user can edit the article category.
     *
     * @param App\Models\User $user
     * @param App\Models\ArticleCategory $category
     * @return mixed
     */
    public function articleCategoryEdit(User $user, ArticleCategory $category)
    {
        return $user->hasPermissionTo('article_category_edit');
    }

    /**
     * Determine whether user can delete the article category.
     *
     * @param App\Models\User $user
     * @param App\Models\ArticleCategory $category
     * @return mixed
     */
    public function articleCategoryDelete(User $user, ArticleCategory $category)
    {
        return $user->hasPermissionTo('article_category_delete');
    }

    /**
     * Determine whether user can enable the article category.
     *
     * @param App\Models\User $user
     * @param App\Models\ArticleCategory $category
     * @return mixed
     */
    public function articleCategoryEnable(User $user, ArticleCategory $category)
    {
        return $user->hasPermissionTo('article_category_enable');
    }

    /**
     * Determine whether user can disable the article category.
     *
     * @param App\Models\User $user
     * @param App\Models\ArticleCategory $category
     * @return mixed
     */
    public function articleCategoryDisable(User $user, ArticleCategory $category)
    {
        return $user->hasPermissionTo('article_category_disable');
    }
}
