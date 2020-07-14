<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, HasRoles;

    protected $guard_name = 'api';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'username', 'email', 'password', 'first_name', 'last_name', 'profile_pic', 'about',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime:Y-m-d H:i:s',
        'last_active_time' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
        'user_blacklisted' => 'boolean',
    ];

    protected $attributes = [
        'user_blacklisted' => false,
    ];

    /**
     * Find the user instance for the given username.
     *
     * @param  string  $username
     * @return \App\Models\User
     */
    public function findForPassport($username)
    {
        return $this->where('username', $username)->first();
    }

    /**
     * Validate the password of the user for the Passport password grant.
     *
     * @param  string  $password
     * @return bool
     */
    public function validateForPassportPasswordGrant($password)
    {
        return Hash::check($password, $this->password);
    }

    public function articles()
    {
        return $this->hasMany(Article::class, 'author_id', 'id');
    }

    public function articleLikes()
    {
        return $this->belongsToMany(Article::class, 'article_likes', 'user_id', 'article_id')->withTimestamps();
    }

    public function articleDislikes()
    {
        return $this->belongsToMany(Article::class, 'article_dislikes', 'user_id', 'article_id')->withTimestamps();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id', 'id');
    }

    public function articleViews()
    {
        return $this->belongsToMany(Article::class, 'article_user_views', 'user_id', 'article_id')->withTimestamps();
    }

    protected function isSuperiorTo(User $anotherUser)
    {
        return $this->roles->max('priority') > $anotherUser->roles->max('priority');
    }
}
