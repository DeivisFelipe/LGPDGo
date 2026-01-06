<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Training>
 */
class TrainingFactory extends Factory
{
    public function definition(): array
    {
        $treinamentos = [
            [
                'titulo' => 'Introdução à LGPD',
                'descricao' => 'Conceitos básicos da Lei Geral de Proteção de Dados',
                'duracao' => 60,
            ],
            [
                'titulo' => 'Segurança da Informação',
                'descricao' => 'Boas práticas para proteção de dados pessoais',
                'duracao' => 45,
            ],
            [
                'titulo' => 'Tratamento de Incidentes',
                'descricao' => 'Como agir em caso de vazamento de dados',
                'duracao' => 30,
            ],
            [
                'titulo' => 'Direitos dos Titulares',
                'descricao' => 'Como atender solicitações (DSAR) dos titulares',
                'duracao' => 40,
            ],
        ];

        $treinamento = fake()->randomElement($treinamentos);

        return [
            'uuid' => (string) Str::uuid(),
            'company_id' => Company::factory(),
            'titulo' => $treinamento['titulo'],
            'descricao' => $treinamento['descricao'],
            'conteudo' => fake()->paragraph(5),
            'duracao_minutos' => $treinamento['duracao'],
            'tipo' => fake()->randomElement(['online', 'presencial', 'hibrido']),
            'obrigatorio' => fake()->boolean(80),
            'data_inicio' => fake()->dateTimeBetween('-3 months', 'now'),
            'data_fim' => fake()->dateTimeBetween('now', '+6 months'),
            'is_active' => fake()->boolean(85),
        ];
    }
}
