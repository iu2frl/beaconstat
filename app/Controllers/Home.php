<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        $db = db_connect();
        $db -> listTables();
        
        return view('welcome_message');
    }
}
