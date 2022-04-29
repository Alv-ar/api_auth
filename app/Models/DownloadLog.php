<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DownloadLog extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $table = 'downloadlog';

    protected $fillable = [
        'email',
        'date',
    ];

}
