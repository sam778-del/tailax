<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
use App\Models\Service;
use Yajra\DataTables\DataTables;
use App\Models\Branch;
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
            ->addColumn('image', function(Service $service) {
                $service_image = !empty(asset(Storage::url($service->image))) ? asset(Storage::url($service->image)) : '';
                return '<div class="d-flex align-items-center"><img src="{{ $service_image }}" class="rounded-circle sm" alt=""></div>';
            })
            ->addColumn('amount', function(Service $service) {
                return '<p class="text-center">'. Auth::user()->getDefaultCurrency().number_format($service->amount, 2) .'</p>';
            })
            ->addColumn('description', function(Service $service) {
                return '<p>'. Str::limit($service->description, 100, '...') .'</p>';
            })
            ->addColumn('status', function(Service $service) {
                $service_status = $service->status === 1 ? __("Active") : __("Inactive");
                $service_badge  = $service->status === 1 ? __("badge bg-success") : __("badge bg-danger");
                return '<p class="text-center"><span onclick="makeDefault(&quot;' . route('currencies.default', $service->id) . '&quot)" class="'. $service_badge .'">'. $service_status .'</span></p>';
            })
            ->addColumn('branch_name', function(Service $service) {
                return $service->branch->name;
            })
            ->addColumn('created_by', function(Service $service) {
                return $service->user->name;
            })
            ->addColumn('action', function(Service $data) {
                return '<a href=" '.route('services.edit', $data->id).' " class="btn btn-link btn-sm color-400"><i class="fa fa-pencil"></i></a> <a href="javascript:void(0);" onclick="deleteAction(&quot;' . route('services.destroy', $data->id) . '&quot)" class="btn btn-link btn-sm color-400"><i class="fa fa-trash"></i></a>';
            })
            ->rawColumns(['image', 'amount', 'description', 'branch_name', 'status' ,'created_by', 'action'])
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

    public function create()
    {
        if(Auth::user()->can('Create Service'))
        {
            $branches = Branch::pluck('name', 'id');
            return view('services.create', compact('branches'));
        }else{
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function edit(Service $service)
    {
        if(Auth::user()->can('Edit Service'))
        {
            $branches = Branch::pluck('name', 'id');
            return view('services.edit', compact('service', 'branches'));
        }else{
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function store(Request $request)
    {
        if(Auth::user()->can("Create Service"))
        {
            if(Auth::user()->isAdmin() && Auth::user()->parent_id === 0)
            {
                $validator = Validator::make($request->all(), [
                    "service_branch" => "required|numeric",
                    "service_code" => "required|unique:services,code|max:100",
                    "service_name" => "required|unique:services,name|max:100",
                    "service_amount" => "required|numeric"
                ]);
            }else{
                $validator = Validator::make($request->all(), [
                    "service_code" => "required|unique:services,code|max:100",
                    "service_name" => "required|unique:services,name|max:100",
                    "service_amount" => "required|numeric",
                ]);
            }

            if($validator->fails())
            {
                return response()->json(["status" => false, "msg" => $validator->errors()->first()]);
            }

            $service        = new Service();
            $service->code  = $request->input("service_code");
            $service->name  = $request->input("service_name");
            $service->amount  = $request->input("service_amount");
            if($request->hasFile('service_image')){
                $validator = Validator::make($request->all(), [
                    'service_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:20480',
                ]);

                if($validator->fails())
                {
                    return response()->json(["status" => false, "msg" => $validator->errors()->first()]);
                }

                $filenameWithExt = $request->file('service_image')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('service_image')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                $filepath        = $request->file('service_image')->storeAs('services', $fileNameToStore);
                $service->image  = $filepath;
            }
            $service->description = $request->input("service_description");
            $service->status      = true;
            if(Auth::user()->isAdmin() && Auth::user()->parent_id === 0)
            {
                $service->branch_id = $request->input("service_branch");
            }else{
                $service->branch_id = Auth::user()->branch_id;
            }
            $service->created_by = Auth::user()->CreatedBy();
            $service->save();
            return response()->json(["status" => true, "msg" => __("Service created successfully.")]);
        }else{
            return response()->json(["status" => false, "msg" => __('Permission Denied.')]);
        }
    }

    public function update(Request $request, Service $service)
    {
        if(Auth::user()->can("Edit Service"))
        {
            if(Auth::user()->isAdmin() && Auth::user()->parent_id === 0)
            {
                $validator = Validator::make($request->all(), [
                    "service_branch" => "required|numeric",
                    "service_code" => "required|unique:services,code|max:100",
                    "service_name" => "required|unique:services,name|max:100",
                    "service_amount" => "required|numeric"
                ]);
            }else{
                $validator = Validator::make($request->all(), [
                    "service_code" => "required|unique:services,code|max:100",
                    "service_name" => "required|unique:services,name|max:100",
                    "service_amount" => "required|numeric",
                ]);
            }

            if($validator->fails())
            {
                return response()->json(["status" => false, "msg" => $validator->errors()->first()]);
            }

            $service->code  = $request->input("service_code");
            $service->name  = $request->input("service_name");
            $service->amount  = $request->input("service_amount");
            if($request->hasFile('service_image')){
                $validator = Validator::make($request->all(), [
                    'service_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:20480',
                ]);

                if($validator->fails())
                {
                    return response()->json(["status" => false, "msg" => $validator->errors()->first()]);
                }

                if(asset(Storage::exists($service->image)))
                {
                    asset(Storage::delete($service->image));
                }

                $filenameWithExt = $request->file('service_image')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('service_image')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                $filepath        = $request->file('service_image')->storeAs('services', $fileNameToStore);
                $service->image  = $filepath;
            }
            $service->description = $request->input("service_description");
            $service->status      = true;
            if(Auth::user()->isAdmin() && Auth::user()->parent_id === 0)
            {
                $service->branch_id = $request->input("service_branch");
            }else{
                $service->branch_id = Auth::user()->branch_id;
            }
            $service->created_by = Auth::user()->CreatedBy();
            $service->save();
            return response()->json(["status" => true, "msg" => __("Service created successfully.")]);
        }else{
            return response()->json(["status" => false, "msg" => __('Permission Denied.')]);
        }
    }
}
