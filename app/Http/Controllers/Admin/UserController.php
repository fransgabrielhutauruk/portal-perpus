<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->activeRoot = 'pengguna';
        $this->breadCrump[] = ['title' => 'Pengguna', 'link' => url('#')];
    }

    public function index()
    {
        return 'halaman crud pengguna';
    }
}
