<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class MiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'InitialDataSeeder']);
        
        // Criar rotas de teste
        Route::middleware(['web', 'auth', 'permission:test-permission'])
            ->get('/test-permission-route', function () {
                return response()->json(['message' => 'success']);
            });

        Route::middleware(['web', 'auth', 'super_user'])
            ->get('/test-super-user-route', function () {
                return response()->json(['message' => 'success']);
            });
    }

    /** @test */
    public function permission_middleware_blocks_user_without_permission()
    {
        $company = Company::factory()->create();
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'company_id' => $company->id,
        ]);

        $this->actingAs($user)
            ->get('/test-permission-route')
            ->assertStatus(403);
    }

    /** @test */
    public function permission_middleware_allows_user_with_permission()
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
        ]);

        $user->permissions()->attach($permission);

        $this->actingAs($user)
            ->get('/test-permission-route')
            ->assertStatus(200)
            ->assertJson(['message' => 'success']);
    }

    /** @test */
    public function permission_middleware_allows_super_user()
    {
        $superUser = User::where('is_super_user', true)->first();

        $this->actingAs($superUser)
            ->get('/test-permission-route')
            ->assertStatus(200);
    }

    /** @test */
    public function super_user_middleware_blocks_regular_users()
    {
        $company = Company::factory()->create();
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'company_id' => $company->id,
        ]);

        $this->actingAs($user)
            ->get('/test-super-user-route')
            ->assertStatus(403);
    }

    /** @test */
    public function super_user_middleware_allows_super_user()
    {
        $superUser = User::where('is_super_user', true)->first();

        $this->actingAs($superUser)
            ->get('/test-super-user-route')
            ->assertStatus(200)
            ->assertJson(['message' => 'success']);
    }

    /** @test */
    public function tenant_scope_middleware_blocks_users_without_company()
    {
        $user = User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
            'company_id' => null,
            'is_super_user' => false,
        ]);

        $this->actingAs($user)
            ->get('/dashboard')
            ->assertStatus(403);
    }

    /** @test */
    public function unauthenticated_users_are_redirected_to_login()
    {
        $this->get('/dashboard')
            ->assertRedirect('/login');
    }
}
