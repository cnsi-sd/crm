<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeAbstractController extends AbstractController
{
    public function home(Request $request): View
    {
        return view('index');
    }
}
