<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Permission;
use App\Models\PermissionGroup;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'InitialDataSeeder']);
    }

    /** @test */
    public function user_can_have_direct_permissions()
    {
        $company = Company::factory()->create();
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'company_id' => $company->id,
        ]);

        $permission = Permission::create([
            'name' => 'Test Permission',
            'slug' => 'test-permission',
            'description' => 'Test',
        ]);

        $user->permissions()->attach($permission);

        $this->assertTrue($user->hasPermission('test-permission'));
    }

    /** @test */
    public function user_can_have_permissions_through_groups()
    {
        $company = Company::factory()->create();
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'company_id' => $company->id,
        ]);

        $permission = Permission::create([
            'name' => 'Test Permission',
            'slug' => 'test-permission',
            'description' => 'Test',
        ]);

        $group = PermissionGroup::create([
            'name' => 'Test Group',
            'slug' => 'test-group',
            'description' => 'Test',
        ]);

        $group->permissions()->attach($permission);
        $user->permissionGroups()->attach($group);

        $this->assertTrue($user->hasPermission('test-permission'));
    }

    /** @test */
    public function super_user_has_all_permissions()
    {
        $superUser = User::where('is_super_user', true)->first();

        $this->assertTrue($superUser->hasPermission('any-permission'));
        $this->assertTrue($superUser->hasPermission('manage-users'));
        $this->assertTrue($superUser->isSuperUser());
    }

    /** @test */
    public function user_without_permission_returns_false()
    {
        $company = Company::factory()->create();
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'company_id' => $company->id,
        ]);

        $this->assertFalse($user->hasPermission('non-existent-permission'));
    }

    /** @test */
    public function user_can_check_any_permission()
    {
        $company = Company::factory()->create();
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'company_id' => $company->id,
        ]);

        $permission1 = Permission::create([
            'name' => 'Permission 1',
            'slug' => 'permission-1',
        ]);

        $permission2 = Permission::create([
            'name' => 'Permission 2',
            'slug' => 'permission-2',
        ]);

        $user->permissions()->attach($permission1);

        $this->assertTrue($user->hasAnyPermission(['permission-1', 'permission-2']));
        $this->assertFalse($user->hasAnyPermission(['permission-2', 'permission-3']));
    }

    /** @test */
    public function user_can_check_all_permissions()
    {
        $company = Company::factory()->create();
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'company_id' => $company->id,
        ]);

        $permission1 = Permission::create([
            'name' => 'Permission 1',
            'slug' => 'permission-1',
        ]);

        $permission2 = Permission::create([
            'name' => 'Permission 2',
            'slug' => 'permission-2',
        ]);

        $user->permissions()->attach([$permission1->id, $permission2->id]);

        $this->assertTrue($user->hasAllPermissions(['permission-1', 'permission-2']));
        $this->assertFalse($user->hasAllPermissions(['permission-1', 'permission-2', 'permission-3']));
    }

    /** @test */
    public function permission_group_can_have_multiple_permissions()
    {
        $group = PermissionGroup::create([
            'name' => 'Test Group',
            'slug' => 'test-group',
        ]);

        $permission1 = Permission::create([
            'name' => 'Permission 1',
            'slug' => 'permission-1',
        ]);

        $permission2 = Permission::create([
            'name' => 'Permission 2',
            'slug' => 'permission-2',
        ]);

        $group->permissions()->attach([$permission1->id, $permission2->id]);

        $this->assertCount(2, $group->permissions);
        $this->assertTrue($group->permissions->contains($permission1));
        $this->assertTrue($group->permissions->contains($permission2));
    }

    /** @test */
    public function user_can_have_multiple_permission_groups()
    {
        $company = Company::factory()->create();
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'company_id' => $company->id,
        ]);

        $group1 = PermissionGroup::create([
            'name' => 'Group 1',
            'slug' => 'group-1',
        ]);

        $group2 = PermissionGroup::create([
            'name' => 'Group 2',
            'slug' => 'group-2',
        ]);

        $user->permissionGroups()->attach([$group1->id, $group2->id]);

        $this->assertCount(2, $user->permissionGroups);
        $this->assertTrue($user->permissionGroups->contains($group1));
        $this->assertTrue($user->permissionGroups->contains($group2));
    }

    /** @test */
    public function seeded_permissions_exist()
    {
        $this->assertDatabaseHas('permissions', ['slug' => 'manage-companies']);
        $this->assertDatabaseHas('permissions', ['slug' => 'view-companies']);
        $this->assertDatabaseHas('permissions', ['slug' => 'manage-users']);
        $this->assertDatabaseHas('permissions', ['slug' => 'view-users']);
    }

    /** @test */
    public function seeded_permission_groups_exist()
    {
        $this->assertDatabaseHas('permission_groups', ['slug' => 'admin']);
        $this->assertDatabaseHas('permission_groups', ['slug' => 'manager']);
        $this->assertDatabaseHas('permission_groups', ['slug' => 'user']);
    }
}
