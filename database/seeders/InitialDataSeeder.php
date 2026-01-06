<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Cookie;
use App\Models\DataInventory;
use App\Models\DataSubject;
use App\Models\Department;
use App\Models\Permission;
use App\Models\PermissionGroup;
use App\Models\Request;
use App\Models\Risk;
use App\Models\Training;
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
            
            // Permissões LGPD
            ['name' => 'Gerenciar Inventário de Dados', 'slug' => 'manage-data-inventory', 'description' => 'Criar e editar mapeamentos (ROPA)'],
            ['name' => 'Visualizar Inventário de Dados', 'slug' => 'view-data-inventory', 'description' => 'Visualizar mapeamentos de dados'],
            ['name' => 'Gerenciar Solicitações DSAR', 'slug' => 'manage-requests', 'description' => 'Atender solicitações de titulares'],
            ['name' => 'Visualizar Solicitações DSAR', 'slug' => 'view-requests', 'description' => 'Visualizar solicitações de titulares'],
            ['name' => 'Gerenciar Riscos', 'slug' => 'manage-risks', 'description' => 'Criar e gerenciar matriz de riscos'],
            ['name' => 'Visualizar Riscos', 'slug' => 'view-risks', 'description' => 'Visualizar matriz de riscos'],
            ['name' => 'Gerenciar Cookies', 'slug' => 'manage-cookies', 'description' => 'Configurar gestão de cookies'],
            ['name' => 'Visualizar Cookies', 'slug' => 'view-cookies', 'description' => 'Visualizar cookies cadastrados'],
            ['name' => 'Gerenciar Treinamentos', 'slug' => 'manage-trainings', 'description' => 'Criar e gerenciar treinamentos LGPD'],
            ['name' => 'Visualizar Treinamentos', 'slug' => 'view-trainings', 'description' => 'Visualizar treinamentos disponíveis'],
            ['name' => 'Gerar Documentos', 'slug' => 'generate-documents', 'description' => 'Gerar políticas e documentos LGPD'],
            ['name' => 'Gerar Selo LGPD', 'slug' => 'generate-seal', 'description' => 'Gerar selo de adequação LGPD'],
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
            'name' => 'Empresa Teste LTDA',
            'cnpj' => '11.111.111/1111-11',
            'email' => 'teste@teste.com',
            'active' => true,
            'score_adequacao' => 45,
            'status_selo' => false,
        ]);

        $testUser = User::create([
            'name' => 'Usuário Teste',
            'email' => 'teste@teste.com',
            'password' => Hash::make('asdasdasd'),
            'company_id' => $testCompany->id,
            'is_super_user' => false,
            'email_verified_at' => now(),
        ]);

        // Criar departamentos de exemplo
        $createdDepartments = [
            Department::factory()->for($testCompany)->create([
                'name' => 'Recursos Humanos',
                'description' => 'Gestão de pessoas e folha de pagamento',
            ]),
            Department::factory()->for($testCompany)->create([
                'name' => 'Tecnologia da Informação',
                'description' => 'Infraestrutura e desenvolvimento',
            ]),
            Department::factory()->for($testCompany)->create([
                'name' => 'Marketing',
                'description' => 'Marketing digital e comunicação',
            ]),
            Department::factory()->for($testCompany)->create([
                'name' => 'Comercial',
                'description' => 'Vendas e atendimento',
            ]),
        ];

        // Criar inventário de dados (ROPA)
        DataInventory::factory()->create([
            'company_id' => $testCompany->id,
            'department_id' => $createdDepartments[0]->id,
            'nome_processo' => 'Folha de Pagamento',
            'finalidade' => 'Processamento de salários e benefícios dos colaboradores',
            'base_legal' => 'execucao_contrato',
            'categoria_dados' => ['pessoais', 'financeiros', 'trabalhistas'],
            'tempo_retencao' => '5 anos após desligamento',
            'quem_acessa' => ['RH', 'Contabilidade'],
            'medidas_seguranca' => 'Criptografia de dados, acesso restrito via senha, backup diário',
            'origem_dados' => ['Formulário de admissão', 'Documentos do colaborador'],
            'destinatarios_dados' => ['Contador', 'Banco para pagamento'],
            'transferencia_internacional' => false,
        ]);

        DataInventory::factory()->create([
            'company_id' => $testCompany->id,
            'department_id' => $createdDepartments[2]->id,
            'nome_processo' => 'Marketing Digital - Newsletter',
            'finalidade' => 'Envio de campanhas de marketing e comunicações',
            'base_legal' => 'consentimento',
            'categoria_dados' => ['contato', 'comportamentais'],
            'tempo_retencao' => 'Até revogação do consentimento',
            'quem_acessa' => ['Marketing', 'TI'],
            'medidas_seguranca' => 'Opt-in duplo, link de descadastro, HTTPS',
            'origem_dados' => ['Formulário de cadastro no site'],
            'destinatarios_dados' => ['Plataforma de email marketing'],
            'transferencia_internacional' => true,
            'pais_destino' => 'Estados Unidos',
        ]);

        // Criar titulares
        DataSubject::factory()->create([
            'company_id' => $testCompany->id,
            'tipo' => 'funcionarios',
            'nome' => 'Funcionários',
            'descricao' => 'Colaboradores ativos e inativos da empresa',
            'quantidade_estimada' => 50,
        ]);

        DataSubject::factory()->create([
            'company_id' => $testCompany->id,
            'tipo' => 'clientes',
            'nome' => 'Clientes',
            'descricao' => 'Pessoas que adquirem produtos ou serviços',
            'quantidade_estimada' => 500,
        ]);

        // Criar riscos
        Risk::factory()->create([
            'company_id' => $testCompany->id,
            'titulo' => 'Acesso não autorizado a dados sensíveis',
            'descricao' => 'Risco de colaboradores sem permissão acessarem dados pessoais sensíveis',
            'probabilidade' => 'media',
            'impacto' => 'alto',
            'nivel_risco' => 'alto', // 3 * 4 = 12 (médio-alto)
            'plano_acao' => 'Implementar controles de acesso mais rigorosos e auditoria de logs',
            'status' => 'em_tratamento',
            'data_revisao' => now()->addMonths(3),
            'responsavel_id' => $testUser->id,
        ]);

        // Criar solicitação DSAR
        Request::factory()->create([
            'company_id' => $testCompany->id,
            'tipo' => 'consulta',
            'nome_titular' => 'João Silva',
            'email_titular' => 'joao.silva@example.com',
            'telefone_titular' => '(11) 98765-4321',
            'documento_titular' => '123.456.789-00',
            'detalhes' => 'Gostaria de saber quais dados pessoais vocês possuem sobre mim.',
            'status' => 'aberto',
            'verificado' => false,
            'responsavel_id' => $testUser->id,
        ]);

        // Criar cookies
        Cookie::factory()->create([
            'company_id' => $testCompany->id,
            'nome' => 'PHPSESSID',
            'descricao' => 'Cookie de sessão necessário para funcionamento do sistema',
            'categoria' => 'necessario',
            'provedor' => 'Sistema',
            'duracao' => 'Sessão',
            'finalidade' => 'Manter sessão do usuário ativa durante navegação',
            'requer_consentimento' => false,
            'is_active' => true,
        ]);

        Cookie::factory()->create([
            'company_id' => $testCompany->id,
            'nome' => '_ga',
            'descricao' => 'Cookie analítico do Google Analytics',
            'categoria' => 'analitico',
            'provedor' => 'Google',
            'duracao' => '2 anos',
            'finalidade' => 'Análise de tráfego e comportamento dos visitantes',
            'script_path' => '/js/analytics.js',
            'requer_consentimento' => true,
            'is_active' => true,
        ]);

        // Criar treinamento
        $training = Training::factory()->create([
            'company_id' => $testCompany->id,
            'titulo' => 'Introdução à LGPD',
            'descricao' => 'Treinamento básico sobre a Lei Geral de Proteção de Dados',
            'conteudo' => 'Neste treinamento você aprenderá os conceitos fundamentais da LGPD...',
            'duracao_minutos' => 60,
            'tipo' => 'online',
            'obrigatorio' => true,
            'data_inicio' => now(),
            'data_fim' => now()->addMonths(6),
            'is_active' => true,
        ]);

        // Associar usuário ao treinamento
        $training->users()->attach($testUser->id, [
            'completed_at' => null,
        ]);

        $this->command->info('Usuário de teste criado com sucesso!');
        $this->command->info('E-mail: teste@teste.com');
        $this->command->info('Senha: asdasdasd');
        $this->command->info('');
        $this->command->info('Dados de exemplo LGPD criados:');
        $this->command->info('- 4 Departamentos');
        $this->command->info('- 2 Inventários de Dados (ROPA)');
        $this->command->info('- 2 Tipos de Titulares');
        $this->command->info('- 1 Risco mapeado');
        $this->command->info('- 1 Solicitação DSAR');
        $this->command->info('- 2 Cookies');
        $this->command->info('- 1 Treinamento');
    }
}
