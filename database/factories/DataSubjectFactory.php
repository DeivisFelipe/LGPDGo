<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DataSubject>
 */
class DataSubjectFactory extends Factory
{
    public function definition(): array
    {
        $tipos = [
            ['tipo' => 'funcionarios', 'nome' => 'Funcionários', 'desc' => 'Colaboradores da empresa', 'qtd' => fake()->numberBetween(10, 500)],
            ['tipo' => 'clientes', 'nome' => 'Clientes', 'desc' => 'Pessoas que adquirem produtos/serviços', 'qtd' => fake()->numberBetween(100, 10000)],
            ['tipo' => 'parceiros', 'nome' => 'Parceiros Comerciais', 'desc' => 'Empresas parceiras', 'qtd' => fake()->numberBetween(5, 50)],
            ['tipo' => 'fornecedores', 'nome' => 'Fornecedores', 'desc' => 'Pessoas jurídicas fornecedoras', 'qtd' => fake()->numberBetween(20, 200)],
        ];

        $titular = fake()->randomElement($tipos);

        return [
            'uuid' => (string) Str::uuid(),
            'company_id' => Company::factory(),
            'tipo' => $titular['tipo'],
            'nome' => $titular['nome'],
            'descricao' => $titular['desc'],
            'quantidade_estimada' => $titular['qtd'],
        ];
    }
}
