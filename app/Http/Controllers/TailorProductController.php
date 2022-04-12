<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\TailorCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use App\Models\Tailor\Product;

class TailorProductController extends Controller
{
    /**
     * @var TailorProduct
     */
    public $tailorProduct;

    /**
     * @param TailorProduct $tailorProduct
     */
    public function __construct(TailorProduct $tailorProduct)
    {
        $this->middleware('auth');
        $this->tailorProduct = $tailorProduct;
    }

    public function datatables(Request $request)
    {
        if($request->ajax())
        {
            switch (auth()->user()) {
                case ! auth()->user()->isAdmin() && auth()->user()->isUser():
                    $tailor_product = Tailor::where('branch_id', Auth::user()->branch_id)->orderBy('id', 'DESC')->latest();
                    break;

                default:
                    $tailor_product = Tailor::orderBy('id', 'DESC')->latest();
                    break;
            }
            DataTables::of($tailor_product)
                        ->addColumn('action', function(TailorCategory $data) {
                            return '<a href=" '.route('tailor_products.edit', $data->id).' " class="btn btn-link btn-sm color-400"><i class="fa fa-pencil"></i></a> <a href="javascript:void(0);" onclick="deleteAction(&quot;' . route('tailor_products.destroy', $data->id) . '&quot)" class="btn btn-link btn-sm color-400"><i class="fa fa-trash"></i></a>';
                        })
                        ->rawColumns(['action'])
                        ->toJson();
        }
    }
}
