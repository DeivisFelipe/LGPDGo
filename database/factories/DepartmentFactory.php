<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Department>
 */
class DepartmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $departments = [
            ['name' => 'Recursos Humanos', 'description' => 'Gestão de pessoas, folha de pagamento e recrutamento'],
            ['name' => 'Tecnologia da Informação', 'description' => 'Infraestrutura, desenvolvimento e segurança da informação'],
            ['name' => 'Marketing', 'description' => 'Comunicação, marketing digital e relacionamento com clientes'],
            ['name' => 'Financeiro', 'description' => 'Gestão financeira, contabilidade e tesouraria'],
            ['name' => 'Comercial', 'description' => 'Vendas, relacionamento com clientes e pós-venda'],
            ['name' => 'Jurídico', 'description' => 'Assessoria jurídica, contratos e compliance'],
            ['name' => 'Operações', 'description' => 'Gestão operacional e logística'],
        ];

        $dept = fake()->randomElement($departments);

        return [
            'uuid' => (string) Str::uuid(),
            'company_id' => Company::factory(),
            'name' => $dept['name'],
            'description' => $dept['description'],
            'is_active' => fake()->boolean(90),
        ];
    }
}
