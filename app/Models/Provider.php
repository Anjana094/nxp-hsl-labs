<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Provider extends Model
{
    use HasFactory; 
    protected $fillable = ['name', 'email'];

    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
