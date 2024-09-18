<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Ureg extends Controller
{
  public function reg(){
    return view("reg");
  }
  public function register(Request $req){
    print_r($req->all());
  }
}
