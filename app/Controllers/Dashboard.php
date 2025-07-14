<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public function index()    { return view('dashboard'); }
    public function tasks()    { return view('tasks'); }
    public function maps()     { return view('maps'); }
    public function notes()    { return view('notes'); }
    public function debugger() { return view('debugger'); }
    public function picker()   { return view('picker'); }
}
