<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomerMail;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;
use App\Models\Branch;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function datatables(Request $request)
    {
        if($request->ajax())
        {
            switch (auth()->user()) {
                case ! auth()->user()->isAdmin() && auth()->user()->isUser():
                    $customers = Customer::where('created_by', Auth::user()->CreatedBy())->orderBy('id', 'DESC')->latest();
                    break;

                default:
                    $customers = Customer::orderBy('id', 'DESC')->latest();
                    break;
            }

            return Datatables::of($customers)
                                ->addColumn('measurement', function(Customer $customer) {
                                    return '<p class="text-center"><a href=" '.route('customers.edit', $customer->id).' " class="btn btn-link btn-sm color-400"><i class="fa fa-eye"></i></a></p>';
                                })
                                ->addColumn('created_by', function(Customer $customer) {
                                    return $customer->user->name;
                                })
                                ->editColumn('image', function(Customer $customer) {
                                    $customer_image = !empty(asset(Storage::url($customer->imgae))) ? asset(Storage::url($customer->imgae)) : asset(STorage::url('avatar/avatar.jpg'));
                                    return '<div class="d-flex align-items-center"><img src="{{ $customer_image }}" class="rounded-circle sm" alt=""></div>';
                                })
                                ->editColumn('amount', function(Customer $customer) {
                                    return '<p class="text-center">'. Auth::user()->getDefaultCurrency().number_format($customer->amount, 2) .'</p>';
                                })
                                ->editColumn('description', function(Customer $customer) {
                                    return '<p>'. Str::limit($customer->description, 100, '...') .'</p>';
                                })
                                ->editColumn('address', function(Customer $customer) {
                                    return '<p>'. Str::limit($customer->address, 100, '...') .'</p>';
                                })
                                ->addColumn('action', function(Customer $data) {
                                    return '<a href=" '.route('customers.edit', $data->id).' " class="btn btn-link btn-sm color-400"><i class="fa fa-pencil"></i></a> <a href="javascript:void(0);" onclick="deleteAction(&quot;' . route('customers.destroy', $data->id) . '&quot)" class="btn btn-link btn-sm color-400"><i class="fa fa-trash"></i></a>';
                                })
                                ->rawColumns(['measurement', 'address', 'description', 'amount', 'image', 'created_by', 'action'])
                                ->toJson();
        }
    }

    public function index()
    {
        if(Auth::user()->can('Manage Customer'))
        {
            $branches = Branch::pluck('name', 'id');
            $branches->prepend(__('All Branches'), '');
            return view('customers.index', compact('branches'));
        }else{
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function create()
    {
        if(Auth::user()->can('Create Customer'))
        {
            $branches = Branch::pluck('name', 'id');
            return view('customers.create', compact('branches'));
        }else{
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function store(Request $request)
    {
        if(Auth::user()->can('Create Customer'))
        {
            if(Auth::user()->isAdmin() && Auth::user()->parent_id === 0)
            {
                $validator = Validator::make($request->all(), [
                    "customer_branch" => "required|numeric",
                    "customer_name"   => "required|string|max:100",
                    "customer_email"  => "required|email|unique:customers,email|max:100",
                    "customer_phone_number" => "required|numeric",
                    "opening_amount"  => "required|numeric",
                ]);
            }else{
                $validator = Validator::make($request->all(), [
                    "customer_name"   => "required|string|max:100",
                    "customer_email"  => "required|email|unique:customers,email|max:100",
                    "customer_phone_number" => "required|numeric",
                    "opening_amount"  => "required|numeric",
                ]);
            }

            if($validator->fails())
            {
                return redirect()->back()->with("error", $validator->errors()->first());
            }

            $customer               = new Customer();
            if(Auth::user()->isAdmin() && Auth::user()->parent_id === 0)
            {
                $customer->branch_id = $request->input("customer_branch");
            }else{
                $customer->branch_id = Auth::user()->branch_id;
            }
            $customer->name          = $request->input("customer_name");
            $customer->email         = $request->input("customer_email");
            $customer->phone_number  = $request->input("phone_number");
            $customer->amount        = $request->input("opening_amount");
            if($request->hasFile('photo')){
                $validator = Validator::make($request->all(), [
                    'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:20480',
                ]);

                if($validator->fails())
                {
                    return redirect()->back()->with("error", $validator->errors()->first());
                }

                $filenameWithExt = $request->file('photo')->getClientOriginalName();
                $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                $extension       = $request->file('photo')->getClientOriginalExtension();
                $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                $filepath        = $request->file('photo')->storeAs('customers', $fileNameToStore);
                $customer->imgae  = $filepath;
            }
            $customer->address        = $request->input("customer_address");
            $customer->description    = $request->input("customer_description");
            $customer->created_by     = Auth::user()->CreatedBy();
            $customer->save();

            try {
                //code...
            } catch (\Exception $e) {
                //throw $th;
            }
            return redirect()->route("customers.index")->with("success", __("Customer created successfully."));
        }else{
            return redirect()->back()->with("error", __("Permission Denied."));
        }
    }

    public function edit(Customer $customer)
    {
        if(Auth::user()->can('Edit Branch'))
        {
            return view('customers.create', compact('branches', 'customer'));
        }else{
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function destroy(Customer $customer)
    {
        if(Auth::user()->can('Delete Customer'))
        {
            if(asset(Storage::exists($customer->image)))
            {
                asset(Storage::delete($customer->image));
            }
            $customer->delete();
            return response()->json(array(
                "status" => true,
                "msg" => __("Customer deleted successfully.")
            ), 200);
        }else{
            return response()->json(["status" => false, "msg" => __("Permission Denied.")], 200);
        }
    }
}
