<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Physician;

class MapController extends Controller
{
    public function map()
    {
    	$data = Physician::get();
    	return view('map', compact('data'));
    }

    public function mapPost(Request $request)
    {
    	Physician::create($request->all());
    	return response()->json($request->all());
    }
}
