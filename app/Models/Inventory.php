<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Inventory extends Model
{
     use HasFactory; 
    protected $fillable = ['provider_id', 'stock'];

    public function provider()
    {
        return $this->belongsTo(Provider::class);
    }
}
