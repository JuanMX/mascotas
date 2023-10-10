<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Pet;
use App\Models\Adoption;
use Illuminate\View\View;

class AdoptionController extends Controller
{
    public function index(): View
    {
        return view('adoption.indexhistorical');
    }
}
