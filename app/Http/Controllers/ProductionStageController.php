<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductionStage;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;

/**
 *
 */
class ProductionStageController extends Controller
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
            $production_stages = ProductionStage::orderBy('id', 'DESC')->get();
            return Datatables::of($production_stages)
                                ->editColumn('created_by', function(ProductionStage $production_stage) {
                                    return $production_stage->user->name;
                                })
                                ->addColumn('action', function(ProductionStage $data) {
                                    return '<a href=" '.route('production_stages.edit', $data->id).' " class="btn btn-link btn-sm color-400"><i class="fa fa-pencil"></i></a> <a href="javascript:void(0);" onclick="deleteAction(&quot;' . route('production_stages.destroy', $data->id) . '&quot)" class="btn btn-link btn-sm color-400"><i class="fa fa-trash"></i></a>';
                                })
                                ->rawColumns(['created_by', 'action'])
                                ->toJson();
        }

    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function index()
    {
        if(Auth::user()->can('Manage Production Stage'))
        {
            return view('productionstages.index');
        }else{
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        if(Auth::user()->can('Create Production Stage'))
        {
            return view('productionstages.create');
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
        if(Auth::user()->can('Create Production Stage'))
        {
            $validator =  Validator::make($request->all(), [
                'process_name' => 'required|string|max:100|unique:production_stages,process_name'
            ]);

            if($validator->fails())
            {
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            $production_stage = new ProductionStage();
            $production_stage->process_name = $request->input('process_name');
            $production_stage->created_by   = Auth::user()->id;
            $production_stage->save();
            return redirect()->route('production_stages.index')->with('success', __('Process Created Succesfully.'));
        }else{
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * @param Request $request
     * @param ProductionStage $production_stage
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit (Request $request, ProductionStage $production_stage)
    {
        if(Auth::user()->can('Edit Production Stage'))
        {
            return view('productionstages.edit', compact('production_stage'));
        }else{
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * @param Request $request
     * @param ProductionStage $production_stage
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, ProductionStage $production_stage)
    {
        if(Auth::user()->can('Edit Production Stage'))
        {
            $validator =  Validator::make($request->all(), [
                'process_name' => 'required|string|unique:production_stages,process_name,' . $production_stage->id . 'id|max:100'
            ]);

            if($validator->fails())
            {
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            $production_stage->process_name = $request->input('process_name');
            $production_stage->created_by   = Auth::user()->id;
            $production_stage->save();
            return redirect()->route('production_stages.index')->with('success', __('Process Updated Succesfully.'));
        }else{
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }


    /**
     * @param ProductionStage $production_stage
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(ProductionStage $production_stage)
    {
        if(Auth::user()->can('Delete Production Stage'))
        {
            $production_stage->delete();
            return response()->json(["status" => true, "msg" => __("Process Deleted Succesfully.")], 200);
        }else{
            return response()->json(["status" => false, "msg" => __("Permission Denied.")], 200);
        }
    }
}
