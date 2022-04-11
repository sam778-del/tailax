<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Branch;
use Yajra\DataTables\DataTables;
use App\Models\User;

class BranchController extends Controller
{
    /**
     * Method __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Method datatables
     *
     * @param Request $request [explicite description]
     *
     * @return void
     */
    public function datatables(Request $request)
    {
        if($request->ajax())
        {
            if(! auth()->user()->isAdmin() && auth()->user()->isUser())
            {
                $branches = Branch::where('created_by', '=', Auth::user()->CreatedBy())->orderBy('id', 'DESC')->get();
            }else{
                $branches = Branch::orderBy('id', 'DESC')->get();
            }
            return Datatables::of($branches)
                                ->editColumn('created_by', function(Branch $branch) {
                                    return $branch->user->name;
                                })
                                ->addColumn('action', function(Branch $data) {
                                    return '<a href=" '.route('branches.edit', $data->id).' " class="btn btn-link btn-sm color-400"><i class="fa fa-pencil"></i></a> <a href="javascript:void(0);" onclick="deleteAction(&quot;' . route('branches.destroy', $data->id) . '&quot)" class="btn btn-link btn-sm color-400"><i class="fa fa-trash"></i></a>';
                                })
                                ->rawColumns(['created_by', 'action'])
                                ->toJson();
        }
    }

    /**
     * Method index
     *
     * @return void
     */
    public function index()
    {
        if(Auth::user()->can('Manage Branch'))
        {
            return view('branches.index');
        }else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Method create
     *
     * @return void
     */
    public function create()
    {
        if(Auth::user()->can('Create Branch'))
        {
            return view('branches.create');
        }else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Method edit
     *
     * @param Branch $branch [explicite description]
     *
     * @return void
     */
    public function edit(Branch $branch)
    {
        if(Auth::user()->can('Edit Branch'))
        {
            return view('branches.edit', compact('branch'));
        }else{
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Method store
     *
     * @param Request $request [explicite description]
     *
     * @return void
     */
    public function store(Request $request)
    {
        if(Auth::user()->can('Create Branch'))
        {
            $validator = Validator::make($request->all(), [
                'branch_name' => 'required|unique:branches,name,NULL,id,created_by,' . Auth::user()->CreatedBy(),
            ]);

            if($validator->fails())
            {
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            $branch['name']        = $request->input('branch_name');
            $branch['created_by'] = Auth::user()->CreatedBy();
            Branch::create($branch);
            return redirect()->route('branches.index')->with('success', __('Branch added successfully.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    /**
     * Method update
     *
     * @param Request $request [explicite description]
     * @param Branch $branch [explicite description]
     *
     * @return void
     */
    public function update(Request $request, Branch $branch)
    {
        if(Auth::user()->can('Edit Branch'))
        {
            $validator = Validator::make(
                $request->all(), [
                    'branch_name' => 'required|unique:branches,name,' . $branch->id . ',id,created_by,' . Auth::user()->CreatedBy(),
                ]
            );
            if($validator->fails())
            {
                return redirect()->back()->with('error', $validator->errors()->first());
            }
            $branch->name        = $request->branch_name;
            $branch->save();

            return redirect()->route('branches.index')->with('success', __('Branch updated successfully.'));
        }else{
            return redirect()->back()->with('error', __("Permission Denied"));
        }
    }

    /**
     * Method destroy
     *
     * @param Branch $branch [explicite description]
     *
     * @return void
     */
    public function destroy(Branch $branch)
    {
        if(Auth::user()->can('Delete Branch'))
        {
            // check how many user is in same branch
            if(! count($branch->checkUserBranch)){
                $branch->delete();
                return response()->json(["status" => true, "msg" => __('Branch deleted successfully.')], 200);
            }else{
                return response()->json(["status" => false, "msg" => __("Cannot delete branch.")], 200);
            }
        }else{
            return response()->json(["status" => false, "msg" => __("Permission Denied.")], 200);
        }
    }
}
