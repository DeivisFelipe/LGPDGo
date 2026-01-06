<?php

namespace App\Services;

use App\Models\Company;
use App\Models\DataInventory;
use App\Models\Request;
use App\Models\Risk;
use App\Models\Cookie;
use App\Models\Training;
use App\Models\Department;
use Illuminate\Support\Facades\DB;

class ComplianceScoreService
{
    /**
     * Calcula o score de adequação LGPD da empresa (0-100)
     * 
     * Critérios de avaliação:
     * - 25 pontos: ROPA (Inventário de Dados) completo
     * - 20 pontos: DSAR (Solicitações) respondidas no prazo
     * - 20 pontos: Gestão de Riscos
     * - 15 pontos: Cookies configurados com consentimento
     * - 10 pontos: Treinamentos realizados
     * - 10 pontos: Estrutura organizacional (departamentos, DPO)
     */
    public function calculateScore(Company $company): array
    {
        $score = 0;
        $maxScore = 100;
        $details = [];

        // 1. ROPA - Inventário de Dados (25 pontos)
        $ropaScore = $this->calculateRopaScore($company);
        $score += $ropaScore['score'];
        $details['ropa'] = $ropaScore;

        // 2. DSAR - Solicitações de Titulares (20 pontos)
        $dsarScore = $this->calculateDsarScore($company);
        $score += $dsarScore['score'];
        $details['dsar'] = $dsarScore;

        // 3. Gestão de Riscos (20 pontos)
        $riskScore = $this->calculateRiskScore($company);
        $score += $riskScore['score'];
        $details['risks'] = $riskScore;

        // 4. Cookies e Consentimento (15 pontos)
        $cookieScore = $this->calculateCookieScore($company);
        $score += $cookieScore['score'];
        $details['cookies'] = $cookieScore;

        // 5. Treinamentos (10 pontos)
        $trainingScore = $this->calculateTrainingScore($company);
        $score += $trainingScore['score'];
        $details['trainings'] = $trainingScore;

        // 6. Estrutura Organizacional (10 pontos)
        $structureScore = $this->calculateStructureScore($company);
        $score += $structureScore['score'];
        $details['structure'] = $structureScore;

        // Atualizar score na empresa
        $company->update(['score_adequacao' => round($score)]);

        return [
            'score' => round($score),
            'maxScore' => $maxScore,
            'percentage' => round(($score / $maxScore) * 100),
            'level' => $this->getComplianceLevel($score),
            'details' => $details,
            'nextSteps' => $this->getNextSteps($details, $score),
        ];
    }

    /**
     * ROPA - Inventário de Dados (máx 25 pontos)
     */
    private function calculateRopaScore(Company $company): array
    {
        $inventories = DataInventory::where('company_id', $company->id)->get();
        $total = $inventories->count();
        
        if ($total === 0) {
            return [
                'score' => 0,
                'maxScore' => 25,
                'total' => 0,
                'complete' => 0,
                'message' => 'Nenhum inventário de dados cadastrado. Comece mapeando os dados pessoais que sua empresa processa.',
                'status' => 'critical'
            ];
        }

        // Critérios de completude
        $complete = $inventories->filter(function ($inventory) {
            return !empty($inventory->nome_processo) &&
                   !empty($inventory->finalidade) &&
                   !empty($inventory->base_legal) &&
                   !empty($inventory->categoria_dados) &&
                   !empty($inventory->tempo_retencao) &&
                   !empty($inventory->medidas_seguranca);
        })->count();

        $percentage = ($complete / $total) * 100;
        $score = ($percentage / 100) * 25;

        return [
            'score' => $score,
            'maxScore' => 25,
            'total' => $total,
            'complete' => $complete,
            'percentage' => round($percentage),
            'message' => $complete === $total 
                ? 'Excelente! Todos os inventários estão completos.' 
                : 'Complete os campos obrigatórios em ' . ($total - $complete) . ' inventário(s).',
            'status' => $percentage >= 80 ? 'good' : ($percentage >= 50 ? 'warning' : 'critical')
        ];
    }

    /**
     * DSAR - Solicitações de Titulares (máx 20 pontos)
     */
    private function calculateDsarScore(Company $company): array
    {
        $requests = Request::where('company_id', $company->id)->get();
        $total = $requests->count();

        if ($total === 0) {
            return [
                'score' => 20, // Pontuação máxima se não houver pendências
                'maxScore' => 20,
                'total' => 0,
                'pending' => 0,
                'overdue' => 0,
                'message' => 'Nenhuma solicitação DSAR registrada.',
                'status' => 'good'
            ];
        }

        $pending = $requests->where('status', 'pendente')->count();
        $overdue = $requests->filter(function ($request) {
            return $request->status === 'pendente' && $request->prazo_legal < now();
        })->count();

        $responded = $requests->whereIn('status', ['concluido', 'parcialmente_atendido'])->count();
        $onTime = $requests->filter(function ($request) {
            return in_array($request->status, ['concluido', 'parcialmente_atendido']) &&
                   ($request->data_conclusao <= $request->prazo_legal);
        })->count();

        if ($responded === 0) {
            $score = $overdue > 0 ? 0 : 10;
        } else {
            $percentage = ($onTime / $responded) * 100;
            $score = ($percentage / 100) * 20;
            
            // Penalidade por atrasados
            if ($overdue > 0) {
                $score -= ($overdue * 2);
                $score = max(0, $score);
            }
        }

        return [
            'score' => $score,
            'maxScore' => 20,
            'total' => $total,
            'pending' => $pending,
            'overdue' => $overdue,
            'onTime' => $onTime,
            'responded' => $responded,
            'message' => $overdue > 0 
                ? "Atenção! {$overdue} solicitação(ões) atrasada(s). Prazo legal: 15 dias." 
                : ($pending > 0 ? "{$pending} solicitação(ões) pendente(s)." : 'Todas as solicitações estão em dia!'),
            'status' => $overdue > 0 ? 'critical' : ($pending > 0 ? 'warning' : 'good')
        ];
    }

    /**
     * Gestão de Riscos (máx 20 pontos)
     */
    private function calculateRiskScore(Company $company): array
    {
        $risks = Risk::where('company_id', $company->id)->get();
        $total = $risks->count();

        if ($total === 0) {
            return [
                'score' => 0,
                'maxScore' => 20,
                'total' => 0,
                'message' => 'Nenhum risco mapeado. Identifique e documente os riscos à privacidade.',
                'status' => 'critical'
            ];
        }

        $critical = $risks->where('nivel_risco', 'critico')->count();
        $high = $risks->where('nivel_risco', 'alto')->count();
        $mitigated = $risks->whereIn('status', ['mitigado', 'resolvido'])->count();
        $withPlan = $risks->where('plano_acao', '!=', null)->count();

        // Score baseado em mitigação
        $mitigationPercentage = ($mitigated / $total) * 100;
        $planPercentage = ($withPlan / $total) * 100;
        
        $score = (($mitigationPercentage * 0.6) + ($planPercentage * 0.4)) / 100 * 20;

        // Penalidade por riscos críticos não mitigados
        $criticalUnmitigated = $risks->where('nivel_risco', 'critico')
                                     ->whereNotIn('status', ['mitigado', 'resolvido'])
                                     ->count();
        if ($criticalUnmitigated > 0) {
            $score -= ($criticalUnmitigated * 3);
            $score = max(0, $score);
        }

        return [
            'score' => $score,
            'maxScore' => 20,
            'total' => $total,
            'critical' => $critical,
            'high' => $high,
            'mitigated' => $mitigated,
            'withPlan' => $withPlan,
            'criticalUnmitigated' => $criticalUnmitigated,
            'message' => $criticalUnmitigated > 0 
                ? "URGENTE: {$criticalUnmitigated} risco(s) crítico(s) sem mitigação!" 
                : ($critical + $high > 0 ? 'Atenção aos riscos de alto impacto.' : 'Gestão de riscos adequada.'),
            'status' => $criticalUnmitigated > 0 ? 'critical' : (($critical + $high) > 0 ? 'warning' : 'good')
        ];
    }

    /**
     * Cookies e Consentimento (máx 15 pontos)
     */
    private function calculateCookieScore(Company $company): array
    {
        $cookies = Cookie::where('company_id', $company->id)->get();
        $total = $cookies->count();

        if ($total === 0) {
            return [
                'score' => 0,
                'maxScore' => 15,
                'total' => 0,
                'message' => 'Nenhum cookie cadastrado. Se usa analytics/marketing, configure o banner de consentimento.',
                'status' => 'warning'
            ];
        }

        $necessary = $cookies->where('categoria', 'necessario')->count();
        $nonNecessary = $total - $necessary;
        $withConsent = $cookies->where('requer_consentimento', true)->count();
        $active = $cookies->where('is_active', true)->count();

        // Score: cookies não-necessários devem ter consentimento configurado
        if ($nonNecessary === 0) {
            $score = 15; // Só cookies necessários, ok
        } else {
            $consentPercentage = ($withConsent / $nonNecessary) * 100;
            $score = ($consentPercentage / 100) * 15;
        }

        return [
            'score' => $score,
            'maxScore' => 15,
            'total' => $total,
            'necessary' => $necessary,
            'nonNecessary' => $nonNecessary,
            'withConsent' => $withConsent,
            'active' => $active,
            'message' => $nonNecessary > 0 && $withConsent < $nonNecessary
                ? 'Configure consentimento para cookies não-essenciais.'
                : 'Gestão de cookies adequada.',
            'status' => ($nonNecessary > 0 && $withConsent < $nonNecessary) ? 'warning' : 'good'
        ];
    }

    /**
     * Treinamentos (máx 10 pontos)
     */
    private function calculateTrainingScore(Company $company): array
    {
        $trainings = Training::where('company_id', $company->id)->get();
        $total = $trainings->count();

        if ($total === 0) {
            return [
                'score' => 0,
                'maxScore' => 10,
                'total' => 0,
                'message' => 'Nenhum treinamento cadastrado. Educar a equipe é essencial para conformidade.',
                'status' => 'warning'
            ];
        }

        $active = $trainings->where('is_active', true)->count();
        $mandatory = $trainings->where('obrigatorio', true)->count();
        
        // Verificar conclusões (via relacionamento pivot)
        $totalUsers = $company->users()->count();
        if ($totalUsers === 0) {
            $completionRate = 0;
        } else {
            $completions = DB::table('training_user')
                ->whereIn('training_id', $trainings->pluck('id'))
                ->whereNotNull('completed_at')
                ->count();
            
            $expectedCompletions = $mandatory * $totalUsers;
            $completionRate = $expectedCompletions > 0 ? ($completions / $expectedCompletions) * 100 : 50;
        }

        $score = ($completionRate / 100) * 10;

        return [
            'score' => $score,
            'maxScore' => 10,
            'total' => $total,
            'active' => $active,
            'mandatory' => $mandatory,
            'completionRate' => round($completionRate),
            'message' => $completionRate < 50 
                ? 'Baixa taxa de conclusão. Incentive a equipe a completar os treinamentos.'
                : 'Boa adesão aos treinamentos!',
            'status' => $completionRate >= 70 ? 'good' : ($completionRate >= 40 ? 'warning' : 'critical')
        ];
    }

    /**
     * Estrutura Organizacional (máx 10 pontos)
     */
    private function calculateStructureScore(Company $company): array
    {
        $departments = Department::where('company_id', $company->id)->where('is_active', true)->count();
        $users = $company->users()->count();
        $hasDPO = $company->users()->whereNotNull('is_super_user')->exists(); // Simplificado
        
        $score = 0;
        
        // 4 pontos: tem departamentos
        if ($departments >= 3) {
            $score += 4;
        } elseif ($departments > 0) {
            $score += 2;
        }
        
        // 3 pontos: tem usuários cadastrados
        if ($users >= 5) {
            $score += 3;
        } elseif ($users >= 2) {
            $score += 1.5;
        }
        
        // 3 pontos: tem DPO definido
        if ($hasDPO) {
            $score += 3;
        }

        return [
            'score' => $score,
            'maxScore' => 10,
            'departments' => $departments,
            'users' => $users,
            'hasDPO' => $hasDPO,
            'message' => $departments === 0 
                ? 'Crie departamentos para organizar o mapeamento de dados.'
                : ($hasDPO ? 'Estrutura organizacional adequada.' : 'Defina um DPO (Encarregado de Dados).'),
            'status' => $score >= 7 ? 'good' : ($score >= 4 ? 'warning' : 'critical')
        ];
    }

    /**
     * Determina o nível de conformidade
     */
    private function getComplianceLevel(float $score): array
    {
        if ($score >= 85) {
            return [
                'name' => 'Excelente',
                'color' => 'green',
                'icon' => '🏆',
                'description' => 'Sua empresa está em alto nível de conformidade com a LGPD!'
            ];
        } elseif ($score >= 70) {
            return [
                'name' => 'Bom',
                'color' => 'blue',
                'icon' => '✅',
                'description' => 'Boa conformidade! Pequenos ajustes farão você chegar à excelência.'
            ];
        } elseif ($score >= 50) {
            return [
                'name' => 'Regular',
                'color' => 'yellow',
                'icon' => '⚠️',
                'description' => 'Conformidade parcial. Priorize as ações críticas.'
            ];
        } elseif ($score >= 30) {
            return [
                'name' => 'Baixo',
                'color' => 'orange',
                'icon' => '⚡',
                'description' => 'Nível crítico! Ação imediata necessária.'
            ];
        } else {
            return [
                'name' => 'Crítico',
                'color' => 'red',
                'icon' => '🚨',
                'description' => 'Risco alto de não-conformidade. Comece o mapeamento urgentemente!'
            ];
        }
    }

    /**
     * Gera sugestões de próximos passos baseadas no score
     */
    private function getNextSteps(array $details, float $score): array
    {
        $steps = [];

        // Prioridade por impacto e urgência
        if ($details['ropa']['total'] === 0 || $details['ropa']['percentage'] < 50) {
            $steps[] = [
                'priority' => 1,
                'title' => 'Complete o ROPA (Inventário de Dados)',
                'description' => 'Mapeie todos os dados pessoais que sua empresa processa.',
                'route' => 'data-inventories.create',
                'icon' => '📋',
                'impact' => 'high'
            ];
        }

        if ($details['dsar']['overdue'] > 0) {
            $steps[] = [
                'priority' => 1,
                'title' => 'Responda Solicitações DSAR Atrasadas',
                'description' => "{$details['dsar']['overdue']} pedido(s) ultrapassaram o prazo legal de 15 dias!",
                'route' => 'requests.index',
                'icon' => '🚨',
                'impact' => 'critical'
            ];
        }

        if (isset($details['risks']['criticalUnmitigated']) && $details['risks']['criticalUnmitigated'] > 0) {
            $steps[] = [
                'priority' => 1,
                'title' => 'Mitigue Riscos Críticos',
                'description' => "{$details['risks']['criticalUnmitigated']} risco(s) crítico(s) sem plano de ação.",
                'route' => 'risks.index',
                'icon' => '⚠️',
                'impact' => 'critical'
            ];
        }

        if ($details['structure']['departments'] < 2) {
            $steps[] = [
                'priority' => 2,
                'title' => 'Crie Departamentos',
                'description' => 'Organize as áreas da empresa para melhor gestão de dados.',
                'route' => 'departments.create',
                'icon' => '🏛️',
                'impact' => 'medium'
            ];
        }

        if ($details['cookies']['nonNecessary'] > 0 && $details['cookies']['withConsent'] < $details['cookies']['nonNecessary']) {
            $steps[] = [
                'priority' => 2,
                'title' => 'Configure Banner de Cookies',
                'description' => 'Implemente consentimento para cookies não-essenciais.',
                'route' => 'cookies.index',
                'icon' => '🍪',
                'impact' => 'medium'
            ];
        }

        if ($details['trainings']['completionRate'] < 50) {
            $steps[] = [
                'priority' => 3,
                'title' => 'Incentive Treinamentos',
                'description' => 'Aumente a taxa de conclusão dos treinamentos obrigatórios.',
                'route' => 'trainings.index',
                'icon' => '📚',
                'impact' => 'low'
            ];
        }

        // Se score >= 85, sugerir geração de selo
        if ($score >= 85) {
            $steps[] = [
                'priority' => 0,
                'title' => 'Gere seu Selo LGPD',
                'description' => 'Parabéns! Você atingiu o nível necessário para gerar o selo de conformidade.',
                'route' => 'seal.generate',
                'icon' => '🏅',
                'impact' => 'achievement'
            ];
        }

        // Ordenar por prioridade
        usort($steps, function ($a, $b) {
            return $a['priority'] - $b['priority'];
        });

        return array_slice($steps, 0, 5); // Máximo 5 sugestões
    }
}
