<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customers extends Model
{
    public $timestamps = false;

    use HasFactory;

    protected $fillable = [
        'name',
        'federal_document',
        'email',
        'phone',
        'zip_code',
        'state',
        'city',
        'address',
        'is_active',
        'create'
    ];
}