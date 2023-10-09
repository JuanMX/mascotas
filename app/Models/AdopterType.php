<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdopterType extends Model
{
    use HasFactory;

    protected $table = 'adopter_types';
    protected $primaryKey = 'id';
}
