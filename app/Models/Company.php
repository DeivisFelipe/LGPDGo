<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'cnpj',
        'email',
        'phone',
        'address',
        'active',
        'logo',
        'score_adequacao',
        'status_selo',
    ];

    protected $casts = [
        'active' => 'boolean',
        'status_selo' => 'boolean',
        'score_adequacao' => 'integer',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function departments(): HasMany
    {
        return $this->hasMany(Department::class);
    }

    public function dataInventories(): HasMany
    {
        return $this->hasMany(DataInventory::class);
    }

    public function dataSubjects(): HasMany
    {
        return $this->hasMany(DataSubject::class);
    }

    public function risks(): HasMany
    {
        return $this->hasMany(Risk::class);
    }

    public function requests(): HasMany
    {
        return $this->hasMany(Request::class);
    }

    public function cookies(): HasMany
    {
        return $this->hasMany(Cookie::class);
    }

    public function trainings(): HasMany
    {
        return $this->hasMany(Training::class);
    }
}
