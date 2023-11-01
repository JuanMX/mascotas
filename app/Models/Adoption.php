<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Adoption extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'adoptions';
    protected $primaryKey = 'id';

    protected $fillable = [
        'adopter_id',
        'pet_id',
        'status',
        'note',
        'updated_at',
        'deleted_at',
    ];
}
