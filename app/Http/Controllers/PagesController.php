<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index(){
        $title = "Reservasi";
        return view('pages.index')->with('title', $title);
    }
    public function jadwal(){
        $title = "Jadwal";
        return view('pages.jadwal')->with('title', $title);
    }
    public function about(){
        $title = "About us";
        return view('pages.about')->with('title', $title);
    }
    public function login(){
        $title = "Login";
        return view('pages.login')->with('title', $title);
    }
    public function register(){
        $title = "Register";
        return view('pages.register')->with('title', $title);
    }
}
