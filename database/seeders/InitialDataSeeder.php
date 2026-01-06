<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Permission;
use App\Models\PermissionGroup;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InitialDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar empresa padrão para o superusuário
        $devCompany = Company::create([
            'name' => 'Sistema',
            'cnpj' => '00.000.000/0000-00',
            'email' => 'dev@lgpdgo.com',
            'active' => true,
        ]);

        // Criar superusuário
        $superUser = User::create([
            'name' => 'Desenvolvedor',
            'email' => 'dev@lgpdgo.com',
            'password' => Hash::make('password'),
            'company_id' => $devCompany->id,
            'is_super_user' => true,
            'email_verified_at' => now(),
        ]);

        // Criar permissões básicas do sistema
        $permissions = [
            ['name' => 'Gerenciar Empresas', 'slug' => 'manage-companies', 'description' => 'Criar, editar e excluir empresas'],
            ['name' => 'Visualizar Empresas', 'slug' => 'view-companies', 'description' => 'Visualizar listagem de empresas'],
            ['name' => 'Gerenciar Usuários', 'slug' => 'manage-users', 'description' => 'Criar, editar e excluir usuários'],
            ['name' => 'Visualizar Usuários', 'slug' => 'view-users', 'description' => 'Visualizar listagem de usuários'],
            ['name' => 'Gerenciar Permissões', 'slug' => 'manage-permissions', 'description' => 'Criar, editar e excluir permissões'],
            ['name' => 'Visualizar Permissões', 'slug' => 'view-permissions', 'description' => 'Visualizar listagem de permissões'],
            ['name' => 'Gerenciar Grupos de Permissões', 'slug' => 'manage-permission-groups', 'description' => 'Criar, editar e excluir grupos de permissões'],
            ['name' => 'Visualizar Grupos de Permissões', 'slug' => 'view-permission-groups', 'description' => 'Visualizar grupos de permissões'],
        ];

        foreach ($permissions as $permission) {
            Permission::create($permission);
        }

        // Criar grupos de permissões
        $adminGroup = PermissionGroup::create([
            'name' => 'Administrador',
            'slug' => 'admin',
            'description' => 'Acesso completo ao sistema (exceto gerenciamento de empresas)',
        ]);

        $managerGroup = PermissionGroup::create([
            'name' => 'Gerente',
            'slug' => 'manager',
            'description' => 'Pode gerenciar usuários e visualizar relatórios',
        ]);

        $userGroup = PermissionGroup::create([
            'name' => 'Usuário Padrão',
            'slug' => 'user',
            'description' => 'Acesso básico ao sistema',
        ]);

        // Associar permissões aos grupos
        $adminGroup->permissions()->attach(Permission::whereNotIn('slug', ['manage-companies'])->pluck('id'));
        $managerGroup->permissions()->attach(Permission::whereIn('slug', ['manage-users', 'view-users'])->pluck('id'));
        $userGroup->permissions()->attach(Permission::whereIn('slug', ['view-users'])->pluck('id'));

        $this->command->info('Dados iniciais criados com sucesso!');
        $this->command->info('Superusuário: dev@lgpdgo.com');
        $this->command->info('Senha: password');

        // Criar usuário de teste
        $testCompany = Company::create([
            'name' => 'Empresa Teste',
            'cnpj' => '11.111.111/1111-11',
            'email' => 'teste@teste.com',
            'active' => true,
        ]);

        User::create([
            'name' => 'Usuário Teste',
            'email' => 'teste@teste.com',
            'password' => Hash::make('asdasdasd'),
            'company_id' => $testCompany->id,
            'is_super_user' => false,
            'email_verified_at' => now(),
        ]);

        $this->command->info('Usuário de teste criado com sucesso!');
        $this->command->info('E-mail: teste@teste.com');
        $this->command->info('Senha: asdasdasd');
    }
}
