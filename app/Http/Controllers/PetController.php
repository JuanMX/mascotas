<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\PetType;
use Illuminate\View\View;

class PetController extends Controller
{
    public function index(): View
    {
        return view('catalogue.pettype', [
            'pettype' => PetType::all()
        ]);
    }
}
