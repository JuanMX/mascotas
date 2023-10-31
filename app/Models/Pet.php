<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pet extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'pets';
    protected $primaryKey = 'id';

    protected $fillable = [
        'name',
        'type',
        'age',
        'status',
        'note',
        'updated_at',
        'deleted_at',
    ];
}
