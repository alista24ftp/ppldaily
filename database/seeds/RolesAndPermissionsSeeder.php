<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        Permission::insert([
            ['name'=>'admin_dashboard', 'guard_name'=>'api'],
            ['name'=>'registration_approve', 'guard_name'=>'api'],
            ['name'=>'permission_grant', 'guard_name'=>'api'],
            ['name'=>'permission_revoke', 'guard_name'=>'api'],
            ['name'=>'user_info_own_view', 'guard_name'=>'api'],
            ['name'=>'user_info_others_view', 'guard_name'=>'api'],
            ['name'=>'user_info_own_edit', 'guard_name'=>'api'],
            ['name'=>'user_info_others_edit', 'guard_name'=>'api'],
            ['name'=>'user_promote', 'guard_name'=>'api'],
            ['name'=>'user_demote', 'guard_name'=>'api'],
            ['name'=>'user_ban', 'guard_name'=>'api'],
            ['name'=>'user_unban', 'guard_name'=>'api'],
            ['name'=>'user_create', 'guard_name'=>'api'],
            ['name'=>'user_delete', 'guard_name'=>'api'],
            ['name'=>'like', 'guard_name'=>'api'],
            ['name'=>'like_own_remove', 'guard_name'=>'api'],
            ['name'=>'like_others_remove', 'guard_name'=>'api'],
            ['name'=>'dislike', 'guard_name'=>'api'],
            ['name'=>'dislike_own_remove', 'guard_name'=>'api'],
            ['name'=>'dislike_others_remove', 'guard_name'=>'api'],
            ['name'=>'comment', 'guard_name'=>'api'],
            ['name'=>'comment_own_edit', 'guard_name'=>'api'],
            ['name'=>'comment_own_delete', 'guard_name'=>'api'],
            ['name'=>'comment_others_delete', 'guard_name'=>'api'],
            ['name'=>'liked_list_own_view', 'guard_name'=>'api'],
            ['name'=>'liked_list_others_view', 'guard_name'=>'api'],
            ['name'=>'liked_list_own_remove', 'guard_name'=>'api'],
            ['name'=>'liked_list_others_remove', 'guard_name'=>'api'],
            ['name'=>'article_viewed_list_own_view', 'guard_name'=>'api'],
            ['name'=>'article_viewed_list_others_view', 'guard_name'=>'api'],
            ['name'=>'article_viewed_own_remove', 'guard_name'=>'api'],
            ['name'=>'article_viewed_others_remove', 'guard_name'=>'api'],
            ['name'=>'article_viewed_list_own_remove', 'guard_name'=>'api'],
            ['name'=>'article_viewed_list_others_remove', 'guard_name'=>'api'],
            ['name'=>'article_publish', 'guard_name'=>'api'],
            ['name'=>'article_own_edit', 'guard_name'=>'api'],
            ['name'=>'article_others_edit', 'guard_name'=>'api'],
            ['name'=>'article_own_soft_delete', 'guard_name'=>'api'],
            ['name'=>'article_others_soft_delete', 'guard_name'=>'api'],
            ['name'=>'article_soft_deleted_own_view', 'guard_name'=>'api'],
            ['name'=>'article_soft_deleted_others_view', 'guard_name'=>'api'],
            ['name'=>'article_own_restore', 'guard_name'=>'api'],
            ['name'=>'article_others_restore', 'guard_name'=>'api'],
            ['name'=>'article_hard_delete', 'guard_name'=>'api'],
            ['name'=>'article_own_enable', 'guard_name'=>'api'],
            ['name'=>'article_others_enable', 'guard_name'=>'api'],
            ['name'=>'article_own_disable', 'guard_name'=>'api'],
            ['name'=>'article_others_disable', 'guard_name'=>'api'],
            ['name'=>'article_disabled_own_view', 'guard_name'=>'api'],
            ['name'=>'article_disabled_others_view', 'guard_name'=>'api'],
            ['name'=>'article_like_own_enable', 'guard_name'=>'api'],
            ['name'=>'article_like_others_enable', 'guard_name'=>'api'],
            ['name'=>'article_like_own_disable', 'guard_name'=>'api'],
            ['name'=>'article_like_others_disable', 'guard_name'=>'api'],
            ['name'=>'article_dislike_own_enable', 'guard_name'=>'api'],
            ['name'=>'article_dislike_others_enable', 'guard_name'=>'api'],
            ['name'=>'article_dislike_own_disable', 'guard_name'=>'api'],
            ['name'=>'article_dislike_others_disable', 'guard_name'=>'api'],
            ['name'=>'article_comment_own_enable', 'guard_name'=>'api'],
            ['name'=>'article_comment_others_enable', 'guard_name'=>'api'],
            ['name'=>'article_comment_own_disable', 'guard_name'=>'api'],
            ['name'=>'article_comment_others_disable', 'guard_name'=>'api'],
            ['name'=>'article_category_add', 'guard_name'=>'api'],
            ['name'=>'article_category_edit', 'guard_name'=>'api'],
            ['name'=>'article_category_delete', 'guard_name'=>'api'],
            ['name'=>'article_category_enable', 'guard_name'=>'api'],
            ['name'=>'article_category_disable', 'guard_name'=>'api'],
            ['name'=>'ad_create', 'guard_name'=>'api'],
            ['name'=>'ad_edit', 'guard_name'=>'api'],
            ['name'=>'ad_delete', 'guard_name'=>'api'],
            ['name'=>'ad_enable', 'guard_name'=>'api'],
            ['name'=>'ad_disable', 'guard_name'=>'api'],
            ['name'=>'ad_type_add', 'guard_name'=>'api'],
            ['name'=>'ad_type_edit', 'guard_name'=>'api'],
            ['name'=>'ad_type_delete', 'guard_name'=>'api'],
            ['name'=>'ad_type_enable', 'guard_name'=>'api'],
            ['name'=>'ad_type_disable', 'guard_name'=>'api'],
            ['name'=>'site_info_manage', 'guard_name' => 'api'],
        ]);

        // Create roles and assign permissions
        Role::create(['name'=>'Admin', 'guard_name'=>'api', 'priority'=>10])->givePermissionTo(Permission::all());
        Role::create(['name'=>'Moderator', 'guard_name'=>'api', 'priority'=>9])->givePermissionTo([
            'admin_dashboard',
            'registration_approve',
            'permission_grant', 'permission_revoke',
            'user_info_own_view', 'user_info_others_view', 'user_info_own_edit', 'user_info_others_edit',
            'user_promote', 'user_demote', 'user_ban', 'user_unban',
            'like', 'like_own_remove', 'like_others_remove',
            'dislike', 'dislike_own_remove', 'dislike_others_remove',
            'comment', 'comment_own_edit', 'comment_own_delete', 'comment_others_delete',
            'liked_list_own_view', 'liked_list_others_view', 'liked_list_own_remove', 'liked_list_others_remove',
            'article_viewed_list_own_view', 'article_viewed_list_others_view',
            'article_viewed_list_own_remove', 'article_viewed_list_others_remove',
            'article_viewed_own_remove', 'article_viewed_others_remove',
            'article_publish', 'article_own_edit', 'article_others_edit',
            'article_own_soft_delete', 'article_others_soft_delete', 'article_own_restore', 'article_others_restore',
            'article_soft_deleted_own_view', 'article_soft_deleted_others_view',
            'article_hard_delete',
            'article_own_enable', 'article_others_enable', 'article_own_disable', 'article_others_disable',
            'article_disabled_own_view', 'article_disabled_others_view',
            'article_like_own_enable', 'article_like_others_enable',
            'article_like_own_disable', 'article_like_others_disable',
            'article_dislike_own_enable', 'article_dislike_others_enable',
            'article_dislike_own_disable', 'article_dislike_others_disable',
            'article_comment_own_enable', 'article_comment_others_enable',
            'article_comment_own_disable', 'article_comment_others_disable',
            'article_category_add', 'article_category_edit', 'article_category_delete',
            'article_category_enable', 'article_category_disable',
            'ad_create', 'ad_edit', 'ad_delete', 'ad_enable', 'ad_disable',
            'ad_type_add', 'ad_type_edit', 'ad_type_delete', 'ad_type_enable', 'ad_type_disable',
        ]);
        Role::create(['name'=>'Author', 'guard_name'=>'api', 'priority'=>3])->givePermissionTo([
            'user_info_own_view', 'user_info_own_edit',
            'like', 'like_own_remove', 'dislike', 'dislike_own_remove',
            'comment', 'comment_own_edit', 'comment_own_delete',
            'liked_list_own_view', 'liked_list_own_remove',
            'article_viewed_list_own_view', 'article_viewed_list_own_remove', 'article_viewed_own_remove',
            'article_publish', 'article_own_edit',
            'article_own_soft_delete', 'article_soft_deleted_own_view', 'article_own_restore',
            'article_own_enable', 'article_own_disable', 'article_disabled_own_view',
            'article_like_own_enable', 'article_like_own_disable',
            'article_dislike_own_enable', 'article_dislike_own_disable',
            'article_comment_own_enable', 'article_comment_own_disable',
        ]);
        Role::create(['name'=>'Member', 'guard_name'=>'api', 'priority'=>1])->givePermissionTo([
            'user_info_own_view', 'user_info_own_edit',
            'like', 'like_own_remove', 'dislike', 'dislike_own_remove', 'comment', 'comment_own_edit', 'comment_own_delete',
            'liked_list_own_view', 'liked_list_own_remove',
            'article_viewed_list_own_view', 'article_viewed_list_own_remove', 'article_viewed_own_remove',
        ]);

        // Assign roles and permissions to users
        $admin = User::find(1);
        $admin->syncRoles(['Admin']);
        $moderator = User::find(2);
        $moderator->syncRoles(['Moderator']);
        $author = User::find(3);
        $author->syncRoles(['Author']);
        $member = User::find(4);
        $member->syncRoles(['Member']);
        $bannedUser = User::find(6);
        $bannedUser->syncRoles(['Moderator']);
    }
}
