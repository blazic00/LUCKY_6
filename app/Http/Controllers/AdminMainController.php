<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminMainController extends Controller
{
    public function showMainPage(){
        return view('admin.index');
    }
}
