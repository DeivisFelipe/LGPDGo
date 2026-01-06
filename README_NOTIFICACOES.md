# Sistema de Notificações Automáticas DSAR

## 📋 Visão Geral

Sistema automatizado de verificação e notificação de prazos para solicitações DSAR (Data Subject Access Request) conforme LGPD Art. 18 e 19.

## 🚀 Funcionalidades

### 1. Verificação Automática de Prazos

-   **Agendamento**: Executa a cada 6 horas automaticamente
-   **Níveis de Urgência**:
    -   🔴 **VENCIDA** (overdue): Prazo expirado
    -   🟠 **CRÍTICA** (critical): ≤ 3 dias restantes
    -   🟡 **ALTA** (high): 4-7 dias restantes
    -   🔵 **NORMAL** (normal): > 7 dias restantes
    -   ✅ **CONCLUÍDA** (completed): Status finalizado

### 2. Notificações por E-mail

-   Enviadas para administradores da empresa + superusers
-   **Frequência**: Máximo 1 notificação a cada 24h por solicitação
-   **Conteúdo do E-mail**:
    -   Protocolo e tipo de solicitação
    -   Dados do titular
    -   Dias restantes/atrasados
    -   Status atual
    -   Link direto para a solicitação
    -   Base legal LGPD (Arts. 18 e 19)

### 3. Rastreamento de Notificações

Campos adicionados à tabela `requests`:

-   `last_notification_sent_at`: Timestamp da última notificação
-   `notification_count`: Contador de notificações enviadas

## 🛠️ Comandos Artisan

### Verificar Prazos Manualmente

```bash
php artisan dsar:check-deadlines
```

**Saída Exemplo:**

```
🔍 Verificando prazos de solicitações DSAR...
⚠️  VENCIDA: DSAR-ABC123 (Empresa XYZ) - 5 dias atrasado
🔔 CRÍTICA: DSAR-DEF456 (Empresa XYZ) - 2 dias restantes

✓ Verificação concluída!
📊 Estatísticas:
   • Total de solicitações: 4
   • Vencidas: 1
   • Críticas: 1
   • Notificações enviadas: 2
```

### Listar Comandos Agendados

```bash
php artisan schedule:list
```

### Testar Agendamento (Desenvolvimento)

```bash
php artisan schedule:work
```

## 📅 Agendamento Automático

Configurado em `routes/console.php`:

```php
Schedule::command('dsar:check-deadlines')
    ->everySixHours()
    ->withoutOverlapping()
    ->onSuccess(function () {
        info('✓ Verificação de prazos DSAR concluída com sucesso.');
    })
    ->onFailure(function () {
        logger()->error('✗ Falha na verificação de prazos DSAR.');
    });
```

### Configurar em Produção

#### Cron (Linux)

Adicione ao crontab:

```bash
* * * * * cd /caminho/para/projeto && php artisan schedule:run >> /dev/null 2>&1
```

#### Systemd Timer (Linux)

Crie `/etc/systemd/system/lgpdgo-scheduler.service`:

```ini
[Unit]
Description=LGPDGo Task Scheduler

[Service]
Type=oneshot
User=www-data
WorkingDirectory=/var/www/lgpdgo
ExecStart=/usr/bin/php artisan schedule:run
```

Crie `/etc/systemd/system/lgpdgo-scheduler.timer`:

```ini
[Unit]
Description=Run LGPDGo Scheduler Every Minute

[Timer]
OnCalendar=*:0/1
Persistent=true

[Install]
WantedBy=timers.target
```

Ative:

```bash
sudo systemctl enable lgpdgo-scheduler.timer
sudo systemctl start lgpdgo-scheduler.timer
```

## 📧 Configuração de E-mail

### Desenvolvimento (.env)

```env
MAIL_MAILER=log
MAIL_FROM_ADDRESS=noreply@lgpdgo.com
MAIL_FROM_NAME="LGPDGo Sistema"
```

### Produção - SMTP (.env)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=seu-email@gmail.com
MAIL_PASSWORD=sua-senha-app
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@lgpdgo.com
MAIL_FROM_NAME="LGPDGo Sistema"
```

### Produção - SES (AWS)

```env
MAIL_MAILER=ses
AWS_ACCESS_KEY_ID=sua-key
AWS_SECRET_ACCESS_KEY=seu-secret
AWS_DEFAULT_REGION=us-east-1
MAIL_FROM_ADDRESS=noreply@lgpdgo.com
```

## 🧪 Testes

### Criar Solicitações de Teste

```bash
php artisan db:seed --class=DsarTestSeeder
```

Cria:

-   1 solicitação VENCIDA (5 dias atrasado)
-   1 solicitação CRÍTICA (2 dias restantes)
-   1 solicitação ALTA (5 dias restantes)
-   1 solicitação NORMAL (10 dias restantes)
-   1 solicitação CONCLUÍDA (não notifica)

### Testar Envio de E-mail

```bash
php artisan tinker
```

```php
$user = App\Models\User::first();
$request = App\Models\Request::where('status', 'pendente')->first();
$user->notify(new App\Notifications\DsarDeadlineAlert($request, 'critical', 2));
```

Verifique o log: `storage/logs/laravel.log`

## 📊 Monitoramento

### Logs de Execução

```bash
# Em tempo real
tail -f storage/logs/laravel.log | grep "DSAR"

# Último dia
grep "DSAR" storage/logs/laravel.log
```

### Verificar Filas (Queue)

```bash
php artisan queue:work
```

### Estatísticas

```php
// Via Tinker
php artisan tinker

// Total de notificações enviadas
App\Models\Request::sum('notification_count');

// Solicitações sem notificação
App\Models\Request::whereNull('last_notification_sent_at')
    ->whereIn('status', ['pendente', 'em_andamento'])
    ->count();

// Última verificação
App\Models\Request::max('last_notification_sent_at');
```

## 🔧 Troubleshooting

### Notificações não estão sendo enviadas

1. Verificar configuração de e-mail:

    ```bash
    php artisan config:cache
    ```

2. Testar conexão SMTP:

    ```bash
    php artisan tinker
    Mail::raw('Teste', function($message) {
        $message->to('seu-email@example.com')->subject('Teste SMTP');
    });
    ```

3. Verificar queue:
    ```bash
    php artisan queue:failed
    php artisan queue:retry all
    ```

### Comando não executa automaticamente

1. Verificar cron:

    ```bash
    crontab -l
    ```

2. Testar manualmente:

    ```bash
    php artisan schedule:run
    ```

3. Verificar logs:
    ```bash
    tail -f storage/logs/laravel.log
    ```

### Duplicação de notificações

O sistema previne duplicação verificando:

-   Última notificação enviada < 24h
-   Implementado em `CheckDsarDeadlines::handle()`

## 📚 Base Legal

### LGPD - Lei Geral de Proteção de Dados

**Art. 18**. O titular dos dados pessoais tem direito a obter do controlador:

-   I - confirmação da existência de tratamento;
-   II - acesso aos dados;
-   III - correção de dados incompletos, inexatos ou desatualizados;
-   IV - anonimização, bloqueio ou eliminação;
-   V - portabilidade dos dados;
-   VI - eliminação dos dados tratados com o consentimento;
-   VII - informação das entidades com as quais seus dados foram compartilhados;
-   VIII - informação sobre a possibilidade de não fornecer consentimento;
-   IX - revogação do consentimento.

**Art. 19**. A confirmação de existência ou o acesso aos dados pessoais serão providenciados, mediante requisição do titular:

-   § 1º Os dados pessoais serão armazenados em formato que favoreça o exercício do direito de acesso.
-   § 2º As informações e os dados poderão ser fornecidos, a critério do titular:
    -   I - imediatamente;
    -   II - em até 15 (quinze) dias, por meio de declaração clara e completa.

## 🤝 Integração com Sistema

### Controller

-   `RequestController::store()`: Cria solicitação com prazo de 15 dias
-   `RequestController::update()`: Atualiza status e resposta

### Model

-   `Request::$fillable`: Inclui campos de notificação
-   `Request::$casts`: Cast automático de timestamps

### Frontend

-   `Requests/Index.vue`: Badge de urgência
-   `Requests/Show.vue`: Timeline e prazo destacado

## 📈 Métricas Sugeridas

### Dashboard Admin

```php
// Solicitações vencidas
$overdue = Request::where('prazo_resposta', '<', now())
    ->whereIn('status', ['pendente', 'em_andamento'])
    ->count();

// Taxa de resposta no prazo
$onTime = Request::where('status', 'concluida')
    ->where('prazo_resposta', '>', 'updated_at')
    ->count();

$total = Request::where('status', 'concluida')->count();
$onTimeRate = $total > 0 ? ($onTime / $total) * 100 : 0;
```

## 🔐 Segurança

-   Notificações enviadas apenas para usuários autorizados
-   Dados sensíveis não incluídos no e-mail (apenas protocolo)
-   Link com autenticação obrigatória
-   Logs auditáveis

## 📞 Suporte

Para dúvidas ou problemas:

1. Verificar logs: `storage/logs/laravel.log`
2. Testar comando manualmente: `php artisan dsar:check-deadlines`
3. Revisar configuração de e-mail no `.env`
