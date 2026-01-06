<?php

namespace App\Models;

use App\Traits\BelongsToCompany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class DataInventory extends Model
{
    use HasFactory, SoftDeletes, BelongsToCompany;

    protected $fillable = [
        'company_id',
        'department_id',
        'nome_processo',
        'finalidade',
        'base_legal',
        'categoria_dados',
        'tempo_retencao',
        'quem_acessa',
        'medidas_seguranca',
        'origem_dados',
        'destinatarios_dados',
        'transferencia_internacional',
        'pais_destino',
    ];

    protected $casts = [
        'categoria_dados' => 'array',
        'quem_acessa' => 'array',
        'origem_dados' => 'array',
        'destinatarios_dados' => 'array',
        'transferencia_internacional' => 'boolean',
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

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function risks(): HasMany
    {
        return $this->hasMany(Risk::class);
    }
}
