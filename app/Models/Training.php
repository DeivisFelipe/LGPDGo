<?php

namespace App\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Training extends Model
{
    use HasFactory, SoftDeletes, BelongsToCompany;

    protected $fillable = [
        'company_id',
        'titulo',
        'descricao',
        'conteudo',
        'duracao_minutos',
        'tipo',
        'obrigatorio',
        'data_inicio',
        'data_fim',
        'is_active',
    ];

    protected $casts = [
        'obrigatorio' => 'boolean',
        'is_active' => 'boolean',
        'data_inicio' => 'date',
        'data_fim' => 'date',
        'duracao_minutos' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class)
            ->withPivot(['completed_at', 'score', 'certificado_url'])
            ->withTimestamps();
    }

    public function completedUsers(): BelongsToMany
    {
        return $this->users()->whereNotNull('training_user.completed_at');
    }

    public function pendingUsers(): BelongsToMany
    {
        return $this->users()->whereNull('training_user.completed_at');
    }
}
