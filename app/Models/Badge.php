<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Badge extends Model
{
    use HasUuids;
    
    protected $keyType = 'string';
    public $incrementing = false;
    
    protected $fillable = [
        'name',
        'key',
        'required_achievements',
        'cashback_amount',
        'description',
    ];
    
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_badges');
    }
}
