<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('home');
    }

    public function GetTokens()
    {
        return view('home.personal-tokens');
    }

    public function GetClientsTokens()
    {
        return view('home.personal-clients');
    }

    public function GetAuthorizedClientsTokens()
    {
        return view('home.personal-authorized-clients');
    }
}
