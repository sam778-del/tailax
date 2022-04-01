<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Service;
use Yajra\DataTables\DataTables;
use App\Models\User;

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function datatables()
    {
        if(! auth()->user()->isAdmin() && auth()->user()->isUser())
        {
            $services = Service::where('created_by', '=', Auth::user()->CreatedBy())->orderBy('id', 'DESC')->get();
        }else{
            $services = Service::orderBy('id', 'DESC')->get();
        }

        return Datatables::of($services)
            ->addCoumn('branch_name', function(Service $service) {
                return $service->branch->name;
            })
            ->addColumn('created_by', function(Service $service) {
                return $service->user->name;
            })
            ->addColumn('action', function(Service $data) {
                return '<a href=" '.route('services.edit', $data->id).' " class="btn btn-link btn-sm color-400"><i class="fa fa-pencil"></i></a> <a href="javascript:void(0);" onclick="deleteAction(&quot;' . route('services.destroy', $data->id) . '&quot)" class="btn btn-link btn-sm color-400"><i class="fa fa-trash"></i></a>';
            })
            ->rawColumns(['branch_name', 'created_by', 'action'])
            ->toJson();
    }

    public function index()
    {
        if(Auth::user()->can('Manage Service'))
        {
            return view('services.index');
        }else{
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
}
