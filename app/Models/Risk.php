<?php

namespace App\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Risk extends Model
{
    use HasFactory, SoftDeletes, BelongsToCompany;

    protected $fillable = [
        'company_id',
        'data_inventory_id',
        'titulo',
        'descricao',
        'probabilidade',
        'impacto',
        'nivel_risco',
        'plano_acao',
        'status',
        'data_revisao',
        'responsavel_id',
    ];

    protected $casts = [
        'data_revisao' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
            $model->calculateNivelRisco();
        });

        static::updating(function ($model) {
            if ($model->isDirty(['probabilidade', 'impacto'])) {
                $model->calculateNivelRisco();
            }
        });
    }

    public function calculateNivelRisco(): void
    {
        $scores = [
            'muito_baixa' => 1, 'muito_baixo' => 1,
            'baixa' => 2, 'baixo' => 2,
            'media' => 3, 'medio' => 3,
            'alta' => 4, 'alto' => 4,
            'muito_alta' => 5, 'muito_alto' => 5,
        ];

        $probScore = $scores[$this->probabilidade] ?? 0;
        $impactoScore = $scores[$this->impacto] ?? 0;
        $total = $probScore * $impactoScore;

        $this->nivel_risco = match(true) {
            $total <= 6 => 'baixo',
            $total <= 12 => 'medio',
            $total <= 20 => 'alto',
            default => 'critico',
        };
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function dataInventory(): BelongsTo
    {
        return $this->belongsTo(DataInventory::class);
    }

    public function responsavel(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsavel_id');
    }
}
