<?php

namespace App\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Request extends Model
{
    use HasFactory, SoftDeletes, BelongsToCompany;

    protected $fillable = [
        'company_id',
        'protocolo',
        'tipo',
        'tipo_solicitacao',
        'nome_titular',
        'email_titular',
        'email',
        'telefone_titular',
        'telefone',
        'documento_titular',
        'cpf',
        'detalhes',
        'descricao',
        'status',
        'resposta',
        'responsavel_id',
        'prazo_legal',
        'prazo_resposta',
        'data_conclusao',
        'verificado',
        'codigo_verificacao',
        'preferencia_contato',
        'observacoes_internas',
        'last_notification_sent_at',
        'notification_count',
    ];

    protected $casts = [
        'prazo_legal' => 'datetime',
        'prazo_resposta' => 'datetime',
        'data_conclusao' => 'datetime',
        'last_notification_sent_at' => 'datetime',
        'verificado' => 'boolean',
        'notification_count' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
            if (empty($model->protocolo)) {
                $model->protocolo = 'DSAR-' . strtoupper(Str::random(8));
            }
            if (empty($model->prazo_legal)) {
                $model->prazo_legal = now()->addDays(15);
            }
            if (empty($model->codigo_verificacao)) {
                $model->codigo_verificacao = strtoupper(Str::random(6));
            }
        });
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function responsavel(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsavel_id');
    }

    public function isPrazoVencido(): bool
    {
        return $this->prazo_legal && now()->isAfter($this->prazo_legal) && $this->status !== 'concluido';
    }

    public function diasRestantes(): int
    {
        return $this->prazo_legal ? now()->diffInDays($this->prazo_legal, false) : 0;
    }
}
