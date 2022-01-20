<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

/**
 * Class SpaController
 * @package App\Http\Controllers
 */
class SpaController
{
    /**
     * @return Application|Factory|View
     */
    public function index()
    {
        return view('app');
    }
}
