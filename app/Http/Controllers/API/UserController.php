<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function getCurrentUser(){
        return $this->successApiResponse('Current authenticated', ['user'=>Auth::user()]);
    }
}
