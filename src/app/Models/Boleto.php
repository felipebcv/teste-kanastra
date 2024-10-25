<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Boleto extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'governmentId',
        'email',
        'amount',
        'dueDate',
        'boletoId',
        'generated',
        'sendMail',
    ];

    protected $casts = [
        'dueDate' => 'date',
    ];
}
