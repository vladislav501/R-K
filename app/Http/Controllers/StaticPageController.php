<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class StaticPageController extends Controller
{
    public function home()
    {
        return view('home');
    }

    public function delivery()
    {
        return view('delivery');
    }

    public function payment()
    {
        return view('payment');
    }

    public function pickup()
    {
        return view('pickup');
    }

    public function about()
    {
        return view('about');
    }

    public function stores()
    {
        return view('stores');
    }

    public function contact()
    {
        return view('contact');
    }

    public function privacy()
    {
        return view('privacy');
    }
}