<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class LanguageController extends Controller
{
    public function changeLanguage(Request $request)
    {
        $lang = $request->language;
        if (in_array($lang, ['ar', 'en'])) {
            session(['locale' => $lang]);
            App::setLocale($lang);

            $user = Auth::user();
            $user->locale = $lang;
            $user->update();
        }
        return response()->json(['status' => 'ok']);
    }
}
