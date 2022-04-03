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
use Yajra\DataTables\Facades\DataTables;
use App\Models\Branch;
use App\Models\User;

class ServiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function datatables(Request $request)
    {
        if($request->ajax())
        {
            if(! auth()->user()->isAdmin() && auth()->user()->isUser())
            {
                $services = Service::where('created_by', '=', Auth::user()->CreatedBy())->orderBy('id', 'DESC')->latest();
            }else{
                $services = Service::orderBy('id', 'DESC')->latest();
            }
            return Datatables::of($services)
                                ->editColumn('image', function(Service $service) {
                                    $service_image = !empty(asset(Storage::url($service->image))) ? asset(Storage::url($service->image)) : '';
                                    return '<div class="d-flex align-items-center"><img src="{{ $service_image }}" class="rounded-circle sm" alt=""></div>';
                                })
                                ->editColumn('amount', function(Service $service) {
                                    return '<p class="text-center">'. Auth::user()->getDefaultCurrency().number_format($service->amount, 2) .'</p>';
                                })
                                ->editColumn('description', function(Service $service) {
                                    return '<p>'. Str::limit($service->description, 100, '...') .'</p>';
                                })
                                ->addColumn('status', function($row) {
                                    $service_status = $row->status === 1 ? __("Active") : __("Inactive");
                                    $service_badge  = $row->status === 1 ? __("badge bg-success") : __("badge bg-danger");
                                    return '<p class="text-center"><span onclick="makeDefault(&quot;' . route('services.default', $row->id) . '&quot)" class="'. $service_badge .'">'. $service_status .'</span></p>';
                                })
                                ->filter(function ($instance) use ($request) {
                                    if ($request->get('status') == 0 || $request->get('status') == 1) {
                                        $instance->where('status', '=', $request->get('status'));
                                    }
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
                    'service_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:20480',
                    "service_amount" => "required|numeric"
                ]);
            }else{
                $validator = Validator::make($request->all(), [
                    "service_code" => "required|unique:services,code|max:100",
                    "service_name" => "required|unique:services,name|max:100",
                    'service_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:20480',
                    "service_amount" => "required|numeric",
                ]);
            }

            if($validator->fails())
            {
                return redirect()->back()->with("error", $validator->errors()->first());
            }

            $service        = new Service();
            $service->code  = $request->input("service_code");
            $service->name  = $request->input("service_name");
            $service->amount  = $request->input("service_amount");
            if($request->hasFile('service_image')){
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
            return redirect()->route("services.index")->with("success", __("Service updated successfully."));
        }else{
            return redirect()->back()->with("error", __('Permission Denied.'));
        }
    }

    public function changeServiceStatus($id)
    {
        if(Auth::user()->can('Manage Service'))
        {
            $service = Service::find($id);
            $status = $service->status == 1 ? false : true;
            Service::where("id", $id)->update(array("status" => $status));
            return response()->json(["status" => true, "msg" => __("Service updated successfully.")], 200);
        }else{
            return response()->json(["status" => false, "msg" => __("Permission Denied.")], 200);
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
                    "service_code" => "required|unique:services,code," . $service->id . "id|max:100",
                    "service_name" => "required|unique:services,name," . $service->id . "id|max:100",
                    'service_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:20480',
                    "service_amount" => "required|numeric"
                ]);
            }else{
                $validator = Validator::make($request->all(), [
                    "service_code" => "required|unique:services,code," . $service->id . "id|max:100",
                    "service_name" => "required|unique:services,name," . $service->id . "id|max:100",
                    'service_image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:20480',
                    "service_amount" => "required|numeric",
                ]);
            }

            if($validator->fails())
            {
                return redirect()->back()->with("error", $validator->errors()->first());
            }

            $service->code  = $request->input("service_code");
            $service->name  = $request->input("service_name");
            $service->amount  = $request->input("service_amount");
            if($request->hasFile('service_image')){
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
            return redirect()->route("services.index")->with("success", __("Service updated successfully."));
        }else{
            return redirect()->back()->with("error", __('Permission Denied.'));
        }
    }

    public function destroy(Service $service)
    {
        if(Auth::user()->can('Delete Service'))
        {
            if(asset(Storage::exists($service->image)))
            {
                asset(Storage::delete($service->image));
            }
            $service->delete();
            return response()->json(array(
                "status" => true,
                "msg" => __("Service deleted successfully.")
            ), 200);
        }else{
            return response()->json(["status" => false, "msg" => __("Permission Denied.")], 200);
        }
    }
}
