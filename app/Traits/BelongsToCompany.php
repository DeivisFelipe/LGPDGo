<?php

namespace App\Traits;

use App\Models\Company;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

trait BelongsToCompany
{
    /**
     * Boot the trait.
     */
    protected static function bootBelongsToCompany(): void
    {
        // Automatically scope queries to the current user's company
        static::addGlobalScope('company', function (Builder $builder) {
            if (auth()->check() && !auth()->user()->is_super_user) {
                $builder->where('company_id', auth()->user()->company_id);
            }
        });

        // Automatically set company_id when creating
        static::creating(function (Model $model) {
            if (auth()->check() && !$model->company_id && !auth()->user()->is_super_user) {
                $model->company_id = auth()->user()->company_id;
            }
        });
    }

    /**
     * Get the company that the model belongs to.
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
