<?php

namespace App\Notifications;

use App\Models\Request as DsarRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DsarDeadlineAlert extends Notification implements ShouldQueue
{
    use Queueable;

    public $request;
    public $urgency;
    public $daysRemaining;

    /**
     * Create a new notification instance.
     */
    public function __construct(DsarRequest $request, string $urgency, int $daysRemaining)
    {
        $this->request = $request;
        $this->urgency = $urgency;
        $this->daysRemaining = $daysRemaining;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $tipoLabels = [
            'acesso' => 'Acesso aos Dados',
            'retificacao' => 'Retificação',
            'exclusao' => 'Exclusão',
            'portabilidade' => 'Portabilidade',
            'oposicao' => 'Oposição ao Tratamento',
            'informacao' => 'Informação sobre Tratamento',
        ];

        $tipo = $tipoLabels[$this->request->tipo_solicitacao] ?? $this->request->tipo_solicitacao;

        if ($this->urgency === 'overdue') {
            $subject = '🔴 URGENTE: Solicitação DSAR Vencida';
            $greeting = 'Alerta de Prazo Vencido!';
            $intro = sprintf(
                'A solicitação **%s** está **%d dias atrasada** e requer ação imediata para evitar penalidades da LGPD.',
                $this->request->protocolo,
                abs($this->daysRemaining)
            );
            $actionText = 'Responder Imediatamente';
            $color = 'red';
        } else {
            $subject = '🟠 ATENÇÃO: Solicitação DSAR Crítica';
            $greeting = 'Alerta de Prazo Crítico!';
            $intro = sprintf(
                'A solicitação **%s** vence em **%d dias** e requer sua atenção urgente.',
                $this->request->protocolo,
                $this->daysRemaining
            );
            $actionText = 'Revisar Solicitação';
            $color = 'orange';
        }

        return (new MailMessage)
            ->subject($subject)
            ->greeting($greeting)
            ->line($intro)
            ->line('')
            ->line('**Detalhes da Solicitação:**')
            ->line("• **Protocolo:** {$this->request->protocolo}")
            ->line("• **Tipo:** {$tipo}")
            ->line("• **Titular:** {$this->request->nome_titular}")
            ->line("• **E-mail:** {$this->request->email}")
            ->line("• **Status:** {$this->request->status}")
            ->line("• **Prazo LGPD:** {$this->request->prazo_resposta->format('d/m/Y H:i')}")
            ->line('')
            ->line('⚖️ **Base Legal:** Art. 18 e 19 da LGPD - O titular tem direito a resposta em até 15 dias.')
            ->action($actionText, url('/requests/' . $this->request->id))
            ->line('')
            ->line('Esta é uma notificação automática do sistema LGPDGo.');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'request_id' => $this->request->id,
            'protocolo' => $this->request->protocolo,
            'urgency' => $this->urgency,
            'days_remaining' => $this->daysRemaining,
            'tipo_solicitacao' => $this->request->tipo_solicitacao,
        ];
    }
}
