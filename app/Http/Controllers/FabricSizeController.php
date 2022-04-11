<?php

namespace App\Http\Controllers;

use App\Models\ProductionStage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use App\Models\FabricSize;

/**
 *
 */
class FabricSizeController extends Controller
{
    /**
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param Request $request
     * @return void
     * @throws \Exception
     */
    public function datatables(Request $request)
    {
        if($request->ajax())
        {
            $fabric_size = FabricSize::orderBy('id', 'DESC')->get();
            return DataTables::of($fabric_size)
                                ->editColumn('name', function (FabricSize $fabric_size) {
                                    return '<p class="text-success">'. $fabric_size->size .'</p>';
                                })
                                ->editColumn('created_by', function(FabricSize $fabric_size) {
                                    return $fabric_size->user->name;
                                })
                                ->addColumn('action', function(FabricSize $data) {
                                    return '<a href=" '.route('fabric_sizes.edit', $data->id).' " class="btn btn-link btn-sm color-400"><i class="fa fa-pencil"></i></a> <a href="javascript:void(0);" onclick="deleteAction(&quot;' . route('fabric_sizes.destroy', $data->id) . '&quot)" class="btn btn-link btn-sm color-400"><i class="fa fa-trash"></i></a>';
                                })
                                ->rawColumns(['name', 'created_by','action'])
                                ->toJson();
        }
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        if(Auth::user()->can('Manage Fabric Size'))
        {
            return view('fabricsizes.index');
        }else{
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        if(Auth::user()->can('Create Fabric Size'))
        {
            return view('fabricsizes.create');
        }else{
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if(Auth::user()->can('Create Fabric Size'))
        {
            $validator =  Validator::make($request->all(), [
                'name' => 'required|string|max:100|unique:fabric_sizes,size'
            ]);

            if($validator->fails())
            {
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            $fabric_size = new FabricSize();
            $fabric_size->size = $request->input('name');
            $fabric_size->created_by = Auth::user()->id;
            $fabric_size->save();
            return redirect()->route('fabric_sizes.index')->with('success', __('Fabric Size Created Succesfully.'));
        }else{
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * @param FabricSize $fabric_size
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(FabricSize $fabric_size)
    {
        if(Auth::user()->can('Edit Fabric Size'))
        {
            return view('fabricsizes.edit', compact('fabric_size'));
        }else{
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * @param Request $request
     * @param FabricSize $fabric_size
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, FabricSize $fabric_size)
    {
        if(Auth::user()->can('Edit Fabric Size'))
        {
            $validator =  Validator::make($request->all(), [
                'name' => 'required|string|unique:fabric_sizes,size,' . $fabric_size->id . 'id|max:100'
            ]);

            if($validator->fails())
            {
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            $fabric_size->size = $request->input('name');
            $fabric_size->created_by = Auth::user()->id;
            $fabric_size->save();
            return redirect()->route('fabric_sizes.index')->with('success', __('Fabric Size Updated Succesfully.'));
        }else{
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * @param FabricSize $fabric_size
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(FabricSize $fabric_size) {
        if(Auth::user()->can('Delete Fabric Size'))
        {
            $fabric_size->delete();
            return response()->json(["status" => true, "msg" => __("Fabric Size Deleted Succesfully.")], 200);
        }else{
            return response()->json(["status" => false, "msg" => __("Permission Denied.")], 200);
        }
    }
}
