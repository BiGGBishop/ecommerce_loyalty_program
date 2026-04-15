<?php

// app/Models/Product.php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    use HasUuids;
    
    public $incrementing = false;
    protected $keyType = 'string';
    
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
    ];
    
    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class);
    }
}