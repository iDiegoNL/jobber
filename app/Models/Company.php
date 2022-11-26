<?php

namespace App\Models;

use App\Enums\CompanyEmployeeCount;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Company extends Model
{
    use HasFactory;

    protected static function boot(): void
    {
        parent::boot();

        static::created(function (Company $company) {
            $company->companyUsers()->firstOrCreate([
                'user_id' => $company->owner_id,
            ]);
        });
    }

    protected $fillable = [
        'name',
        'slug',
        'description',
        'founded_in',
        'country',
        'website',
        'employee_count',
        'owner_id',
    ];

    protected $casts = [
        'employee_count' => CompanyEmployeeCount::class,
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function companyUsers(): HasMany
    {
        return $this->hasMany(CompanyUsers::class);
    }

    public function users(): HasManyThrough
    {
        return $this->hasManyThrough(User::class, CompanyUsers::class, 'company_id', 'id', 'id', 'user_id');
    }
}
