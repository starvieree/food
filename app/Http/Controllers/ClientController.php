<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function ClientLogin()
    {
        return view('client.login');
    }

    public function ClientRegister()
    {
        return view('client.register');
    }
}
