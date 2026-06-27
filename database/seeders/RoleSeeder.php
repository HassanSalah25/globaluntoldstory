<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'settings.view', 'settings.manage',
            'menus.view', 'menus.manage',
            'pages.view', 'pages.manage',
            'sections.view', 'sections.manage',
            'hero.view', 'hero.manage',
            'stats.view', 'stats.manage',
            'services.view', 'services.manage',
            'process.view', 'process.manage',
            'testimonials.view', 'testimonials.manage',
            'categories.view', 'categories.manage',
            'portfolio.view', 'portfolio.manage',
            'blog.view', 'blog.manage',
            'faqs.view', 'faqs.manage',
            'team.view', 'team.manage',
            'timeline.view', 'timeline.manage',
            'skills.view', 'skills.manage',
            'values.view', 'values.manage',
            'features.view', 'features.manage',
            'offices.view', 'offices.manage',
            'partners.view', 'partners.manage',
            'clients.view', 'clients.manage',
            'resources.view', 'resources.manage',
            'awards.view', 'awards.manage',
            'media.view', 'media.manage',
            'seo.view', 'seo.manage',
            'contact-requests.view', 'contact-requests.manage',
            'leads.view', 'leads.manage',
            'newsletter.view', 'newsletter.manage',
            'users.view', 'users.manage',
            'roles.view', 'roles.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $superAdmin = Role::firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $editor = Role::firstOrCreate(['name' => 'editor', 'guard_name' => 'web']);
        $viewer = Role::firstOrCreate(['name' => 'viewer', 'guard_name' => 'web']);

        $superAdmin->syncPermissions(Permission::all());

        $admin->syncPermissions(array_filter($permissions, fn (string $p) => ! str_starts_with($p, 'roles.')));

        $editorPermissions = array_filter($permissions, function (string $permission) {
            return str_ends_with($permission, '.manage')
                && ! in_array($permission, ['users.manage', 'roles.manage'], true);
        });
        $editor->syncPermissions(array_merge(
            $editorPermissions,
            array_filter($permissions, fn (string $p) => str_ends_with($p, '.view'))
        ));

        $viewer->syncPermissions(array_filter($permissions, fn (string $p) => str_ends_with($p, '.view')));
    }
}
