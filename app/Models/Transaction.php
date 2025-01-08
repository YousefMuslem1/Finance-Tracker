<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'category_id',
        'type',
        'amount',
        'description',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function commission()
    {
        return $this->hasOne(Commission::class, 'transaction_id');
    }
    public function totalAmount()
    {
        $commissionAmount = $this->commission ? $this->commission->amount : 0;

        if ($this->type == 'income') {
            return $this->amount - $commissionAmount;

        }
        return $this->amount + $commissionAmount;

    }
}
