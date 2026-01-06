<?php

namespace Database\Factories;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Request>
 */
class RequestFactory extends Factory
{
    public function definition(): array
    {
        $tipo = fake()->randomElement(['exclusao', 'consulta', 'portabilidade', 'correcao', 'oposicao', 'revogacao_consentimento']);
        
        $detalhes = [
            'exclusao' => 'Solicito a exclusão de todos os meus dados pessoais do sistema.',
            'consulta' => 'Gostaria de saber quais dados pessoais vocês possuem sobre mim.',
            'portabilidade' => 'Solicito a portabilidade dos meus dados em formato estruturado.',
            'correcao' => 'Preciso corrigir informações cadastrais incorretas.',
            'oposicao' => 'Me opoõo ao tratamento dos meus dados para fins de marketing.',
            'revogacao_consentimento' => 'Revogo o consentimento previamente fornecido.',
        ];

        return [
            'uuid' => (string) Str::uuid(),
            'protocolo' => 'DSAR-' . strtoupper(Str::random(8)),
            'company_id' => Company::factory(),
            'tipo' => $tipo,
            'nome_titular' => fake('pt_BR')->name(),
            'email_titular' => fake()->unique()->safeEmail(),
            'telefone_titular' => fake('pt_BR')->cellphone(),
            'documento_titular' => fake('pt_BR')->cpf(false),
            'detalhes' => $detalhes[$tipo],
            'status' => fake()->randomElement(['aberto', 'em_andamento', 'aguardando_titular', 'concluido']),
            'resposta' => fake()->boolean(40) ? fake()->paragraph() : null,
            'responsavel_id' => null,
            'data_conclusao' => fake()->boolean(30) ? fake()->dateTimeBetween('-30 days', 'now') : null,
            'verificado' => fake()->boolean(70),
        ];
    }
}
