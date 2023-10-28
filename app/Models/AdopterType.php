<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AdopterType extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'adopter_types';
    protected $primaryKey = 'id';
    
    protected $fillable = [
        'name',
        'updated_at',
    ];
}
