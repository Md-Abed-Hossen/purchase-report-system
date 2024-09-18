<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    protected $fillable = [
        'order_no',   // Add this line
        'user_id',
        'product_id',
        'quantity',
        'created_at',
    ];
}
