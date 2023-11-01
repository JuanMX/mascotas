<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Adopter extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'forename',
        'surname',
        'address',
        'phone',
        'email',
        'age',
        'status',
        'type',
        'updated_at',
        'deleted_at',
    ];
}
