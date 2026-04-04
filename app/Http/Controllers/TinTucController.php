<?php

use App\Http\Controllers\Controller;

class TinTucController extends Controller{
    public function index()
    {
        return view('.index');
    }
}