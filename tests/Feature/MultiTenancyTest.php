<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MultiTenancyTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->artisan('db:seed', ['--class' => 'InitialDataSeeder']);
    }

    /** @test */
    public function users_can_only_see_data_from_their_company()
    {
        // Criar duas empresas
        $company1 = Company::create([
            'name' => 'Empresa 1',
            'cnpj' => '11.111.111/0001-11',
            'active' => true,
        ]);

        $company2 = Company::create([
            'name' => 'Empresa 2',
            'cnpj' => '22.222.222/0001-22',
            'active' => true,
        ]);

        // Criar usuários em empresas diferentes
        $user1 = User::create([
            'name' => 'User 1',
            'email' => 'user1@company1.com',
            'password' => bcrypt('password'),
            'company_id' => $company1->id,
            'is_super_user' => false,
        ]);

        $user2 = User::create([
            'name' => 'User 2',
            'email' => 'user2@company2.com',
            'password' => bcrypt('password'),
            'company_id' => $company2->id,
            'is_super_user' => false,
        ]);

        // Autenticar como user1
        $this->actingAs($user1);

        // User1 só deve ver usuários da empresa 1
        $users = User::all();
        $this->assertTrue($users->contains($user1));
        $this->assertFalse($users->contains($user2));
    }

    /** @test */
    public function super_user_can_see_all_companies_data()
    {
        $company1 = Company::create([
            'name' => 'Empresa 1',
            'cnpj' => '11.111.111/0001-11',
            'active' => true,
        ]);

        $company2 = Company::create([
            'name' => 'Empresa 2',
            'cnpj' => '22.222.222/0001-22',
            'active' => true,
        ]);

        $user1 = User::create([
            'name' => 'User 1',
            'email' => 'user1@company1.com',
            'password' => bcrypt('password'),
            'company_id' => $company1->id,
            'is_super_user' => false,
        ]);

        $user2 = User::create([
            'name' => 'User 2',
            'email' => 'user2@company2.com',
            'password' => bcrypt('password'),
            'company_id' => $company2->id,
            'is_super_user' => false,
        ]);

        $superUser = User::where('is_super_user', true)->first();
        $this->actingAs($superUser);

        // Superusuário deve ver todos os usuários
        $users = User::all();
        $this->assertTrue($users->contains($user1));
        $this->assertTrue($users->contains($user2));
        $this->assertTrue($users->contains($superUser));
    }

    /** @test */
    public function company_id_is_automatically_set_when_creating_records()
    {
        $company = Company::create([
            'name' => 'Empresa Teste',
            'cnpj' => '33.333.333/0001-33',
            'active' => true,
        ]);

        $user = User::create([
            'name' => 'User Test',
            'email' => 'test@company.com',
            'password' => bcrypt('password'),
            'company_id' => $company->id,
            'is_super_user' => false,
        ]);

        $this->actingAs($user);

        // Criar um novo usuário sem especificar company_id
        $newUser = User::create([
            'name' => 'New User',
            'email' => 'newuser@company.com',
            'password' => bcrypt('password'),
        ]);

        $this->assertEquals($company->id, $newUser->company_id);
    }

    /** @test */
    public function non_authenticated_users_see_all_data()
    {
        $company1 = Company::create([
            'name' => 'Empresa 1',
            'cnpj' => '11.111.111/0001-11',
            'active' => true,
        ]);

        $company2 = Company::create([
            'name' => 'Empresa 2',
            'cnpj' => '22.222.222/0001-22',
            'active' => true,
        ]);

        // Sem autenticação, deve ver todos os dados (para permitir login)
        $companies = Company::all();
        $this->assertCount(3, $companies); // 2 criadas + 1 do seed
    }

    /** @test */
    public function user_belongs_to_a_company()
    {
        $company = Company::create([
            'name' => 'Empresa Teste',
            'cnpj' => '44.444.444/0001-44',
            'active' => true,
        ]);

        $user = User::create([
            'name' => 'User Test',
            'email' => 'test@company.com',
            'password' => bcrypt('password'),
            'company_id' => $company->id,
            'is_super_user' => false,
        ]);

        $this->assertInstanceOf(Company::class, $user->company);
        $this->assertEquals($company->id, $user->company->id);
    }

    /** @test */
    public function company_has_many_users()
    {
        $company = Company::create([
            'name' => 'Empresa Teste',
            'cnpj' => '55.555.555/0001-55',
            'active' => true,
        ]);

        $user1 = User::create([
            'name' => 'User 1',
            'email' => 'user1@test.com',
            'password' => bcrypt('password'),
            'company_id' => $company->id,
        ]);

        $user2 = User::create([
            'name' => 'User 2',
            'email' => 'user2@test.com',
            'password' => bcrypt('password'),
            'company_id' => $company->id,
        ]);

        $this->assertCount(2, $company->users);
        $this->assertTrue($company->users->contains($user1));
        $this->assertTrue($company->users->contains($user2));
    }
}
