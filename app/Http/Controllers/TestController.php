<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;

use Helper;
Use Exception;


class TestController extends Controller
{
    public function testDatatableDetailsControlAllOpen(): View
    {
        return view('test.indexDatatableDetailsControllAllOpen');
    }
}
