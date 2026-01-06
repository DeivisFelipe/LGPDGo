<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\Request as DsarRequest;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DsarTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $company = Company::first();

        if (!$company) {
            $this->command->error('❌ Nenhuma empresa encontrada. Execute InitialDataSeeder primeiro.');
            return;
        }

        // 1. Solicitação VENCIDA (overdue)
        DsarRequest::create([
            'company_id' => $company->id,
            'protocolo' => 'DSAR-' . strtoupper(Str::random(8)),
            'tipo_solicitacao' => 'exclusao',
            'nome_titular' => 'Maria Silva Vencida',
            'email' => 'maria.vencida@example.com',
            'telefone' => '(11) 98765-4321',
            'cpf' => '123.456.789-00',
            'descricao' => 'Solicito a exclusão completa de todos os meus dados pessoais do sistema.',
            'preferencia_contato' => 'email',
            'status' => 'pendente',
            'prazo_resposta' => now()->subDays(5), // 5 dias ATRASADO
        ]);

        // 2. Solicitação CRÍTICA (2 dias restantes)
        DsarRequest::create([
            'company_id' => $company->id,
            'protocolo' => 'DSAR-' . strtoupper(Str::random(8)),
            'tipo_solicitacao' => 'acesso',
            'nome_titular' => 'João Santos Crítico',
            'email' => 'joao.critico@example.com',
            'telefone' => '(11) 97654-3210',
            'cpf' => '987.654.321-00',
            'descricao' => 'Preciso de acesso a todos os dados que vocês possuem sobre mim.',
            'preferencia_contato' => 'email',
            'status' => 'em_andamento',
            'prazo_resposta' => now()->addDays(2), // 2 dias restantes
        ]);

        // 3. Solicitação ALTA (5 dias restantes)
        DsarRequest::create([
            'company_id' => $company->id,
            'protocolo' => 'DSAR-' . strtoupper(Str::random(8)),
            'tipo_solicitacao' => 'retificacao',
            'nome_titular' => 'Ana Costa Alta',
            'email' => 'ana.alta@example.com',
            'telefone' => '(21) 98765-1234',
            'cpf' => '456.789.123-00',
            'descricao' => 'Meu endereço está incorreto no cadastro, preciso atualizar.',
            'preferencia_contato' => 'telefone',
            'status' => 'pendente',
            'prazo_resposta' => now()->addDays(5), // 5 dias restantes
        ]);

        // 4. Solicitação NORMAL (10 dias restantes)
        DsarRequest::create([
            'company_id' => $company->id,
            'protocolo' => 'DSAR-' . strtoupper(Str::random(8)),
            'tipo_solicitacao' => 'portabilidade',
            'nome_titular' => 'Pedro Oliveira Normal',
            'email' => 'pedro.normal@example.com',
            'cpf' => '321.654.987-00',
            'descricao' => 'Solicito a portabilidade dos meus dados em formato CSV.',
            'preferencia_contato' => 'email',
            'status' => 'pendente',
            'prazo_resposta' => now()->addDays(10), // 10 dias restantes
        ]);

        // 5. Solicitação CONCLUÍDA (não deve notificar)
        DsarRequest::create([
            'company_id' => $company->id,
            'protocolo' => 'DSAR-' . strtoupper(Str::random(8)),
            'tipo_solicitacao' => 'informacao',
            'nome_titular' => 'Carlos Pereira Concluído',
            'email' => 'carlos.concluido@example.com',
            'cpf' => '789.123.456-00',
            'descricao' => 'Gostaria de saber como vocês utilizam meus dados.',
            'preferencia_contato' => 'email',
            'status' => 'concluida',
            'resposta' => 'Seus dados são utilizados conforme nossa política de privacidade...',
            'prazo_resposta' => now()->addDays(8),
        ]);

        $this->command->info('✓ 5 solicitações DSAR de teste criadas:');
        $this->command->info('  • 1 VENCIDA (5 dias atrasado)');
        $this->command->info('  • 1 CRÍTICA (2 dias restantes)');
        $this->command->info('  • 1 ALTA (5 dias restantes)');
        $this->command->info('  • 1 NORMAL (10 dias restantes)');
        $this->command->info('  • 1 CONCLUÍDA (não deve notificar)');
    }
}
