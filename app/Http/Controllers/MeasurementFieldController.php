<?php

namespace App\Http\Controllers;

use App\Models\ProductionStage;
use Illuminate\Http\Request;
use App\Models\MeasurementField;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class MeasurementFieldController extends Controller
{
    public $measurementField;

    /**
     * Method __construct
     *
     * @param MeasurementField $measurementField [explicite description]
     *
     * @return void
     */
    public function __construct(MeasurementField $measurementField)
    {
        $this->measurementField = $measurementField;
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
            $measurement_field = MeasurementField::orderBy('measurement_name', 'ASC')->get();
            return DataTables::of($measurement_field)
                                ->editColumn('value_type', function(MeasurementField $measurement_field) {
                                    switch (!empty($measurement_field->value_type)) {
                                        case $measurement_field->value_type === 'Dropdown Values':
                                            return '<p class="text-center"><button type="button" onclick="showOptions(&quot;' . route('measurement_fields.show', $measurement_field->id) . '&quot, &quot;' . route('measurement.field.option', $measurement_field->id) . '&quot);" class="btn btn-lg bg-light text-dark color-400">'.__('Options').'</button></p>';
                                            break;

                                        default:
                                            return '<p class="text-center">'. $measurement_field->value_type .'</p>';
                                            break;
                                    }
                                })
                                ->editColumn('created_by', function(MeasurementField $measurement_field) {
                                    return '<p class="text-center">'. $measurement_field->user->name .'</p>';
                                })
                                ->addColumn('action', function(MeasurementField $data) {
                                    return '<p class="text-center"><a href=" '.route('measurement_fields.edit', $data->id).' " class="btn btn-link btn-sm color-400"><i class="fa fa-pencil"></i></a> <a href="javascript:void(0);" onclick="deleteAction(&quot;' . route('measurement_fields.destroy', $data->id) . '&quot)" class="btn btn-link btn-sm color-400"><i class="fa fa-trash"></i></a></p>';
                                })
                                ->rawColumns(['value_type', 'created_by', 'action'])
                                ->toJson();
        }
    }

    public function index()
    {
        if(Auth::user()->can('Manage Measurement Field'))
        {
            return view('measurementfield.index');
        }else{
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function create()
    {
        if(Auth::user()->can('Create Measurement Field'))
        {
            $value_type = $this->measurementField->valueType();
            $types      = $this->measurementField->MeasurementType();
            return view('measurementfield.create', compact('value_type', 'types'));
        }else{
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function show(MeasurementField $measurement_field)
    {
        if(Auth::user()->can('Manage Measurement Field'))
        {
            $data = '';
            foreach (json_decode($measurement_field->options, true) as $key => $item)
            {
                $data .= '<div class="row item-row">
                        <div class="col-6">
                            <label class="form-label text-primary">'.__('Options Name').'</label>
                            <input type="text" name="option_name[]" class="form-control form-control-lg" value="'. $item["option"] .'" placeholder="'.__('Options Name').'">
                        </div>
                        <div class="col-3">
                            <label class="form-label text-primary">'.__('Image').'</label>
                            <div class="col-md-12 col-sm-12">
                                <input type="file" class="form-control form-control-lg" name="image[]" id="file-input1">
                                <label for="file-input1" class="shadow text-muted"></label>
                            </div>
                        </div>
                        <div class="col-3">
                            <button href="javascript:void(0);" type="button"  onclick="addButton();" class="btn btn-link btn-lg color-400"><i class="fa fa-plus-circle"></i></button>
                        </div>
                    </div>';
            }
            return response()->json(["status" => true, "msg" => $data]);
        }else{
            return response()->json(["status" => false, "msg" => __("Permission Denied.")], 200);
        }
    }

    public function updateOption(Request $request, $measurementField_ID)
    {
        if(Auth::user()->can('Create Measurement Field'))
        {
            $validator = Validator::make($request->all(), [
                'option_name.*' => 'required|string|max:100',
                'image.*'       => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:20480'
            ]);

            if($validator->fails())
            {
                return redirect()->back()->with('error', $validator->errors()->first());
            }

            foreach (json_decode(MeasurementField::find($measurementField_ID), true) as $key => $item)
            {
                if(asset(Storage::exists($item['image'])))
                {

                }
            }

            $option         = array();
            $option_name    = $request->input('option_name');
            $image          = $request->file('image');
            if(!empty($option_name))
            {
                for ($i=0; $i < count($option_name); $i++)
                {
                    $option[$i]["option"] = $option_name[$i];
                    if($request->hasFile('image'))
                    {
                        $filenameWithExt = $image[$i]->getClientOriginalName();
                        $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                        $extension       = $image[$i]->getClientOriginalExtension();
                        $fileNameToStore = $filename . '_' . time() . '.' . $extension;
                        $filepath        = $image[$i]->storeAs('measurementfield', $fileNameToStore);
                        $option[$i]["filepath"] = $filepath;
                    }
                }
            }
            MeasurementField::where('id', $measurementField_ID)->update([
                'options' => json_encode($option)
            ]);
            return redirect()->back()->with('success', __('Measurement Field Updated Succesfully.'));
        }else{
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function store(Request $request)
    {
        if(Auth::user()->can('Create Measurement Field'))
        {
            $validator = Validator::make($request->all(), [
                'measurement_name.*' => 'required|unique:measurement_fields,measurement_name'
            ]);

            if($validator->fails())
            {
                return redirect()->back()->with('error', $validator->errors()->first())->withInput();
            }

            $measurement_name = $request->input('measurement_name');
            $value_type       = $request->input('value_type');

            if(!empty($measurement_name) && !empty($value_type))
            {
                for ($i=0; $i < count($measurement_name); $i++) {
                    $measurement_field   =  new MeasurementField();
                    $measurement_field->measurement_name = $measurement_name[$i];
                    $measurement_field->value_type = $value_type[$i];
                    $measurement_field->created_by = Auth::user()->id;
                    $measurement_field->save();
                }
            }
            return redirect()->route('measurement_fields.index')->with('success', __('Measurement Field Created Succesfully.'));
        }else{
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }
}
