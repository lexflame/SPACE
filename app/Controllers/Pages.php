<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Pages extends Controller
{
    public function tasks()
    {
        // return view('panelspace').view('tasks');
        return view('tasks');
    }
}
