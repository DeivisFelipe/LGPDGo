<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\DataInventory;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Risk>
 */
class RiskFactory extends Factory
{
    public function definition(): array
    {
        $riscos = [
            [
                'titulo' => 'Vazamento de dados pessoais',
                'descricao' => 'Risco de exposição de dados pessoais por falha de segurança',
            ],
            [
                'titulo' => 'Acesso não autorizado',
                'descricao' => 'Acesso indevido a dados sensíveis por colaboradores sem permissão',
            ],
            [
                'titulo' => 'Perda de dados por falha técnica',
                'descricao' => 'Risco de perda de dados por falta de backup adequado',
            ],
            [
                'titulo' => 'Compartilhamento inadequado',
                'descricao' => 'Compartilhamento de dados com terceiros sem base legal',
            ],
        ];

        $risco = fake()->randomElement($riscos);

        return [
            'uuid' => (string) Str::uuid(),
            'company_id' => Company::factory(),
            'data_inventory_id' => null,
            'titulo' => $risco['titulo'],
            'descricao' => $risco['descricao'],
            'probabilidade' => fake()->randomElement(['muito_baixa', 'baixa', 'media', 'alta', 'muito_alta']),
            'impacto' => fake()->randomElement(['muito_baixo', 'baixo', 'medio', 'alto', 'muito_alto']),
            'plano_acao' => fake()->paragraph(),
            'status' => fake()->randomElement(['identificado', 'em_tratamento', 'mitigado', 'aceito']),
            'data_revisao' => fake()->dateTimeBetween('now', '+6 months'),
            'responsavel_id' => null,
        ];
    }
}
