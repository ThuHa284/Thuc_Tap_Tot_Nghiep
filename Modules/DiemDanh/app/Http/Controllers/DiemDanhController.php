<?php

namespace Modules\DiemDanh\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DiemDanhController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('diemdanh::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('diemdanh::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {}

    /**
     * Show the specified resource.
     */
    public function show($id)
    {
        return view('diemdanh::show');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('diemdanh::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id) {}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id) {}
}
