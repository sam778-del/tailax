<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MeasurementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function datatables(Request $request)
    {
        if($request->ajax())
        {

        }
    }

    public function index()
    {
        if(Auth::user()->can('Manage Measurement'))
        {
            return view('measurement.index');
        }else{
            return redirect()->back()->with('error', __('Permsission Denied.'));
        }
    }
}
