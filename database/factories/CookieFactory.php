<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Cookie>
 */
class CookieFactory extends Factory
{
    public function definition(): array
    {
        $cookies = [
            [
                'nome' => '_ga',
                'categoria' => 'analitico',
                'provedor' => 'Google Analytics',
                'duracao' => '2 anos',
                'finalidade' => 'Rastrear visitantes e sessões para análise estatística',
                'requer' => true,
            ],
            [
                'nome' => 'PHPSESSID',
                'categoria' => 'necessario',
                'provedor' => 'Sistema',
                'duracao' => 'Sessão',
                'finalidade' => 'Manter a sessão do usuário ativa',
                'requer' => false,
            ],
            [
                'nome' => '_fbp',
                'categoria' => 'marketing',
                'provedor' => 'Facebook',
                'duracao' => '3 meses',
                'finalidade' => 'Rastreamento de conversões e remarketing',
                'requer' => true,
            ],
            [
                'nome' => 'intercom-session',
                'categoria' => 'funcional',
                'provedor' => 'Intercom',
                'duracao' => '7 dias',
                'finalidade' => 'Chat de suporte ao cliente',
                'requer' => true,
            ],
        ];

        $cookie = fake()->randomElement($cookies);

        return [
            'uuid' => (string) Str::uuid(),
            'company_id' => Company::factory(),
            'nome' => $cookie['nome'],
            'descricao' => 'Cookie ' . $cookie['categoria'] . ' utilizado por ' . $cookie['provedor'],
            'categoria' => $cookie['categoria'],
            'provedor' => $cookie['provedor'],
            'duracao' => $cookie['duracao'],
            'finalidade' => $cookie['finalidade'],
            'script_path' => '/js/cookies/' . strtolower($cookie['nome']) . '.js',
            'requer_consentimento' => $cookie['requer'],
            'is_active' => fake()->boolean(90),
        ];
    }
}
