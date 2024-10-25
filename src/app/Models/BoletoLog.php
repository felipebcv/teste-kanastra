<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BoletoLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'boleto_id'
    ];
}
