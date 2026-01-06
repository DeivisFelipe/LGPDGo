<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DataInventory>
 */
class DataInventoryFactory extends Factory
{
    public function definition(): array
    {
        $processos = [
            [
                'nome' => 'Folha de Pagamento',
                'finalidade' => 'Processamento de salários e benefícios dos colaboradores',
                'base_legal' => 'execucao_contrato',
                'categorias' => ['pessoais', 'financeiros', 'trabalhistas'],
                'tempo' => '5 anos após desligamento',
                'medidas' => 'Criptografia de dados, acesso restrito ao RH, backup diário',
            ],
            [
                'nome' => 'Cadastro de Clientes',
                'finalidade' => 'Gestão do relacionamento comercial',
                'base_legal' => 'execucao_contrato',
                'categorias' => ['pessoais', 'contato', 'comerciais'],
                'tempo' => '5 anos após última interação',
                'medidas' => 'Acesso via senha, logs de acesso, HTTPS',
            ],
            [
                'nome' => 'Marketing Digital',
                'finalidade' => 'Envio de campanhas e newsletters',
                'base_legal' => 'consentimento',
                'categorias' => ['contato', 'comportamentais'],
                'tempo' => 'Até revogação do consentimento',
                'medidas' => 'Opt-in duplo, link de descadastro, HTTPS',
            ],
        ];

        $processo = fake()->randomElement($processos);

        return [
            'uuid' => (string) Str::uuid(),
            'company_id' => Company::factory(),
            'department_id' => Department::factory(),
            'nome_processo' => $processo['nome'],
            'finalidade' => $processo['finalidade'],
            'base_legal' => $processo['base_legal'],
            'categoria_dados' => $processo['categorias'],
            'tempo_retencao' => $processo['tempo'],
            'quem_acessa' => ['RH', 'TI', 'Gestão'],
            'medidas_seguranca' => $processo['medidas'],
            'origem_dados' => ['Formulário interno', 'Titular'],
            'destinatarios_dados' => ['Contador', 'Banco'],
            'transferencia_internacional' => fake()->boolean(10),
            'pais_destino' => fake()->boolean(10) ? 'Estados Unidos' : null,
        ];
    }
}
