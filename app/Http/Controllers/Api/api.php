<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class api extends Controller
{
    
    public function home(){
        return "Hello wrold";
    }

}
