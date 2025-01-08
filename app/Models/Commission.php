<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Commission extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_id',
        'amount',
    ];
    public function commission()
{
    return $this->hasOne(Commission::class, 'transaction_id');
}
}
