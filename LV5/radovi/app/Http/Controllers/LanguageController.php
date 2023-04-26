<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function changeLanguage(Request $request)
    {
        $locale = $request->language;
        Session::put('my_locale', $locale);
        return redirect()->back();
    }
}
