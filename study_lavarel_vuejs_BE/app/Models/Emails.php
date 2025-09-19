<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Emails extends Model
{
    use HasFactory, softDeletes;
    protected $fillable = [
        'email',
        'status',
        'subject',
        'to',
        'content'
    ];
}
