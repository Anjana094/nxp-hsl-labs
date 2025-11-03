<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory; 
    protected $fillable = [
        'provider_id',
        'quantity',
        'status',
        'ordered_at'
    ];

    protected $casts = [
        'ordered_at' => 'datetime'
    ];

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}
