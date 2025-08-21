<?php

namespace App\Controllers;

class Language extends BaseController
{
    public function switch($locale = 'en')
    {
        // Check if the locale is valid
        $validLocales = config('App')->supportedLocales;
        if (!in_array($locale, $validLocales)) {
            $locale = config('App')->defaultLocale;
        }
        
        // Set the locale in the session
        session()->set('locale', $locale);
        
        // Redirect back to the previous page
        return redirect()->back();
    }
}