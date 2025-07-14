<?php
namespace App\Controllers;

class Navigation extends BaseController
{
    public function index()
    {
        return view('navigation_map');
    }
}
