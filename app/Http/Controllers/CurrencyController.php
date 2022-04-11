<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Currency;

class CurrencyController extends Controller
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
            $currencies = Currency::orderBy('id', 'DESC')->get();
            return Datatables::of($currencies)
                                ->addColumn('exchange_rate', function(Currency $currency) {
                                    return '<p class="text-success text-center">'. number_format($currency->amount, 3) .'</p>';
                                })
                                ->addColumn('base_currency', function(Currency $currency) {
                                    $currency_status = $currency->is_default === 1 ? __("Yes") : __("No");
                                    $currency_badge  = $currency->is_default === 1 ? __("badge bg-success") : __("badge bg-danger");
                                    return '<p class="text-center"><span onclick="makeDefault(&quot;' . route('currencies.default', $currency->id) . '&quot)" class="'. $currency_badge .'">'. $currency_status .'</span></p>';
                                })
                                ->addColumn('action', function(Currency $data) {
                                    return '<a href=" '.route('currencies.edit', $data->id).' " class="btn btn-link btn-sm color-400"><i class="fa fa-pencil"></i></a> <a href="javascript:void(0);" onclick="deleteAction(&quot;' . route('currencies.destroy', $data->id) . '&quot)" class="btn btn-link btn-sm color-400"><i class="fa fa-trash"></i></a>';
                                })
                                ->rawColumns(['exchange_rate', 'base_currency', 'action'])
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
        if(Auth::user()->can('Manage Currency'))
        {
            return view('currency.index');
        }else{
            return redirect()->back()->with('error', __("Permission Denied."));
        }
    }

    /**
     * Method create
     *
     * @return void
     */
    public function create()
    {
        if(Auth::user()->can('Create Currency'))
        {
            return view('currency.create');
        }else{
            return redirect()->back()->with('error', __('Permission Denied.'));
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
        if(Auth::user()->can('Create Currency'))
        {
            $validator = Validator::make($request->all(), [
                'currency_name' => 'required|unique:currencies,currecny_name|max:100',
                'currency_symbol' => 'required|max:100',
                'exchange_rate' => 'required|numeric',
            ]);

            if($validator->fails())
            {
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            $currency['currecny_name']   = $request->input('currency_name');
            $currency['currency_symbol'] = $request->input('currency_symbol');
            $currency['amount']          = $request->input('exchange_rate');
            $currency['is_default']      = false;
            Currency::create($currency);
            return redirect()->route('currencies.index')->with("success", __("Currency added successfully."));
        }else{
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Method edit
     *
     * @param Currency $currency [explicite description]
     *
     * @return void
     */
    public function edit(Currency $currency)
    {
        if(Auth::user()->can('Edit Currency'))
        {
            return view('currency.edit', compact('currency'));
        }else{
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Method update
     *
     * @param Request $request [explicite description]
     * @param Currency $currency [explicite description]
     *
     * @return void
     */
    public function update(Request $request, Currency $currency)
    {
        if(Auth::user()->can('Create Currency'))
        {
            $validator = Validator::make($request->all(), [
                'currency_name' => 'required|unique:currencies,currecny_name,'. $currency->id .'id|max:100',
                'currency_symbol' => 'required|max:100',
                'exchange_rate' => 'required|numeric',
            ]);

            if($validator->fails())
            {
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            $currency->currecny_name   = $request->input('currency_name');
            $currency->currency_symbol = $request->input('currency_symbol');
            $currency->amount          = $request->input('exchange_rate');
            $currency->is_default      = $currency->is_default;
            $currency->save();
            return redirect()->route('currencies.index')->with("success", __("Currency updated successfully."));
        }else{
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    /**
     * Method changeCurrencyStatus
     *
     * @param int $currency_id [explicite description]
     *
     * @return void
     */
    public function changeCurrencyStatus(int $currency_id)
    {
        if(Auth::user()->can('Manage Currency'))
        {
            $currency = Currency::findorfail($currency_id);
            $currency->is_default = true;
            $currency->save();
            $currency = Currency::where('id','!=',$currency_id)->update(['is_default' => false]);
            return response()->json(["status" => true, "msg" => __("Currency updated successfully.")], 200);
        }else{
            return response()->json(["status" => false, "msg" => __("Permission Denied.")], 200);
        }
    }

    /**
     * Method destroy
     *
     * @param Currency $currency [explicite description]
     *
     * @return void
     */
    public function destroy(Currency $currency)
    {
        if(Auth::user()->can('Delete Currency'))
        {
            switch (!empty($currency)) {
                case $currency->is_default === 1:
                    return response()
                            ->json(
                                ["status" => false,
                                "msg" => __("Cannot delete currency.")
                            ], 200);
                    break;
                default:
                    $currency->delete();
                    return response()->json(array(
                        "status" => true,
                        "msg" => __("Currency deleted successfully.")
                    ), 200);
                    break;
            }
        }else{
            return response()->json(["status" => false, "msg" => __("Permission Denied.")], 200);
        }
    }
}
