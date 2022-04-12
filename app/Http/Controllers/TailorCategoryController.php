<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Models\TailorCategory;

/**
 *
 */
class TailorCategoryController extends Controller
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
     */
    public function datatables(Request $request)
    {
        if($request->ajax())
        {
            $tailor_category = TailorCategory::orderBy('id', 'DESC')->get();
            return Datatables::of($tailor_category)
                                ->editColumn('created_by', function(TailorCategory $tailor_category) {
                                    return $tailor_category->user->name;
                                })
                                ->addColumn('action', function(TailorCategory $data) {
                                    return '<a href=" '.route('tailor_categories.edit', $data->id).' " class="btn btn-link btn-sm color-400"><i class="fa fa-pencil"></i></a> <a href="javascript:void(0);" onclick="deleteAction(&quot;' . route('tailor_categories.destroy', $data->id) . '&quot)" class="btn btn-link btn-sm color-400"><i class="fa fa-trash"></i></a>';
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
        if(Auth::user()->can('Manage Tailor Categories'))
        {
            return view('tailorcategory.index');
        }else{
            return redirect()->back()->with('error', __('Permission Denied'));
        }
    }

    /**
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function create()
    {
        if(Auth::user()->can('Create Tailor Categories'))
        {
            return view('tailorcategory.create');
        }else{
            return redirect()->back()->with('error', __('Permission Denied'));
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        if(Auth::user()->can('Create Tailor Categories'))
        {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|unique:tailor_categories,name|max:100'
            ]);

            if($validator->fails())
            {
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            $tailor_category =  new TailorCategory();
            $tailor_category->name = $request->input('name');
            $tailor_category->created_by = Auth::user()->id;
            $tailor_category->save();
            return  redirect()->route('tailor_categories.index')->with('success', __('Tailor Category Created Succesfully.'));
        }else{
            return redirect()->back()->with('error', __('Permission Denied'));
        }
    }

    /**
     * @param TailorCategory $tailor_category
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Http\RedirectResponse
     */
    public function edit(TailorCategory $tailor_category)
    {
        if(Auth::user()->can('Edit Tailor Categories'))
        {
            return view('tailorcategory.edit', compact('tailor_category'));
        }else{
            return redirect()->back()->with('error', __('Permission Denied'));
        }
    }

    /**
     * @param Request $request
     * @param TailorCategory $tailor_category
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, TailorCategory $tailor_category)
    {
        if(Auth::user()->can('Edit Tailor Categories'))
        {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|unique:tailor_categories,name,' . $tailor_category->id . 'id|max:100'
            ]);

            if($validator->fails())
            {
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            $tailor_category->name = $request->input('name');
            $tailor_category->created_by = Auth::user()->id;
            $tailor_category->save();
            return  redirect()->route('tailor_categories.index')->with('success', __('Tailor Category Created Succesfully.'));
        }else{
            return redirect()->back()->with('error', __('Permission Denied'));
        }
    }

    /**
     * @param TailorCategory $tailor_category
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(TailorCategory $tailor_category)
    {
        if(Auth::user()->can('Delete Tailor Categories'))
        {
            $tailor_category->delete();
            return response()->json(["status" => true, "msg" => __("Tailor Category Deleted Succesfully.")], 200);
        }else{
            return response()->json(["status" => false, "msg" => __("Permission Denied.")], 200);
        }
    }
}
