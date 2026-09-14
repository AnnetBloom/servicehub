<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Contact;
use App\Models\Site;
use App\Models\Contract;
use Illuminate\Database\Eloquent\SoftDeletes;

#[Fillable(['name', 'website'])]
class Company extends Model
{
    /** @use HasFactory<\Database\Factories\CompanyFactory> */
    use HasFactory, SoftDeletes;

    public function contacts(): HasMany
    {
        return $this->hasMany(Contact::class);
    }

    public function sites(): HasMany
    {
        return $this->hasMany(Site::class);
    }

    public function contracts(): HasMany
    {
        return $this->hasMany(Contract::class);
    }
}
