<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_name',
        'uuid',
        'total_lines',
        'retry',
        'status',
    ];
}
