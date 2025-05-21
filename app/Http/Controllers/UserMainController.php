<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserMainController extends Controller
{
    public function showMainPage(){
        return view('index');
    }
    public function showGamePage(){
        return view('lucky6');
    }

}
