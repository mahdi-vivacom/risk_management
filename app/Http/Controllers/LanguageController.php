<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class LanguageController extends Controller
{
    function changeLanguage(Request $request) {
        App::setLocale($request->language);
        session()->put('lang_code',$request->language);
        return redirect()->back();
    }
}
