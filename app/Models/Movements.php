<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movements extends Model
{
    public $timestamps = false;

    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity',
        'movement',
        'create'
    ];
}