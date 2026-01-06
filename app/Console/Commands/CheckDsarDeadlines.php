<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Request as DsarRequest;
use App\Models\User;
use App\Notifications\DsarDeadlineAlert;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class CheckDsarDeadlines extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dsar:check-deadlines';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifica prazos DSAR e envia notificações para solicitações críticas ou vencidas';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Verificando prazos de solicitações DSAR...');

        // Buscar solicitações pendentes ou em andamento
        $requests = DsarRequest::with(['company'])
            ->whereIn('status', ['pendente', 'em_andamento'])
            ->get();

        if ($requests->isEmpty()) {
            $this->info('✓ Nenhuma solicitação pendente encontrada.');
            return 0;
        }

        $notificationsSent = 0;
        $overdueCount = 0;
        $criticalCount = 0;

        foreach ($requests as $request) {
            $daysRemaining = now()->diffInDays($request->prazo_resposta, false);
            $urgency = $this->calculateUrgency($daysRemaining);

            // Só notifica se crítico ou vencido
            if (!in_array($urgency, ['overdue', 'critical'])) {
                continue;
            }

            // Evita spam: só envia notificação a cada 24h
            if ($request->last_notification_sent_at && 
                $request->last_notification_sent_at->diffInHours(now()) < 24) {
                continue;
            }

            // Buscar administradores da empresa
            $admins = User::where('company_id', $request->company_id)
                ->where('is_super_user', false) // Super users recebem de todas empresas
                ->get();

            // Adicionar super users
            $superUsers = User::where('is_super_user', true)->get();
            $recipients = $admins->merge($superUsers);

            if ($recipients->isNotEmpty()) {
                Notification::send(
                    $recipients,
                    new DsarDeadlineAlert($request, $urgency, $daysRemaining)
                );

                // Atualizar registro de notificação
                $request->increment('notification_count');
                $request->update(['last_notification_sent_at' => now()]);

                $notificationsSent++;

                if ($urgency === 'overdue') {
                    $overdueCount++;
                    $this->warn("⚠️  VENCIDA: {$request->protocolo} ({$request->company->name}) - {$daysRemaining} dias atrasado");
                } else {
                    $criticalCount++;
                    $this->warn("🔔 CRÍTICA: {$request->protocolo} ({$request->company->name}) - {$daysRemaining} dias restantes");
                }
            }
        }

        $this->newLine();
        $this->info("✓ Verificação concluída!");
        $this->info("📊 Estatísticas:");
        $this->info("   • Total de solicitações: {$requests->count()}");
        $this->info("   • Vencidas: {$overdueCount}");
        $this->info("   • Críticas: {$criticalCount}");
        $this->info("   • Notificações enviadas: {$notificationsSent}");

        return 0;
    }

    /**
     * Calculate urgency level
     */
    private function calculateUrgency(int $daysRemaining): string
    {
        if ($daysRemaining < 0) {
            return 'overdue';
        } elseif ($daysRemaining <= 3) {
            return 'critical';
        } elseif ($daysRemaining <= 7) {
            return 'high';
        } else {
            return 'normal';
        }
    }
}
