<?php

namespace App\Http\Controllers;

use App\BodyType;
use App\Business;
use App\CustomerGroup;
use App\ForeginPurchase;
use App\Http\Livewire\Vehicle\AddForeignPurchase;
use App\LocalPurchase;
use App\VehicleSell;
use App\Utils\BusinessUtil;
use App\Utils\ContactUtil;
use App\Utils\ModuleUtil;
use App\Utils\ProductUtil;
use App\Utils\TransactionUtil;
use App\VehicleManufacture;
use App\VehicleModel;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

use function Termwind\render;

class VehicleSellController extends Controller
{
    /**
     * All Utils instance.
     */
    protected $contactUtil;

    protected $businessUtil;

    protected $transactionUtil;

    protected $productUtil;

    /**
     * Constructor
     *
     * @param  ProductUtils  $product
     * @return void
     */
    public function __construct(ContactUtil $contactUtil, BusinessUtil $businessUtil, TransactionUtil $transactionUtil, ModuleUtil $moduleUtil, ProductUtil $productUtil)
    {
        $this->contactUtil = $contactUtil;
        $this->businessUtil = $businessUtil;
        $this->transactionUtil = $transactionUtil;
        $this->moduleUtil = $moduleUtil;
        $this->productUtil = $productUtil;

        $this->dummyPaymentLine = [
            'method' => '', 'amount' => 0, 'note' => '', 'card_transaction_number' => '', 'card_number' => '', 'card_type' => '', 'card_holder_name' => '', 'card_month' => '', 'card_year' => '', 'card_security' => '', 'cheque_number' => '', 'bank_account_number' => '',
            'is_return' => 0, 'transaction_no' => '',
        ];

        $this->shipping_status_colors = [
            'ordered' => 'bg-yellow',
            'packed' => 'bg-info',
            'shipped' => 'bg-navy',
            'delivered' => 'bg-green',
            'cancelled' => 'bg-red',
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function new_book(Request $request)
    {

        if ($request->has('manufacture_id')) {
            session()->put('manufacture_id', $request->manufacture_id);
        }

        if ($request->has('model_id')) {
            session()->put('model_id', $request->model_id);
        }
        if ($request->has('year')) {
            session()->put('year', $request->year);
        }

        if ($request->has('color_id')) {
            session()->put('color_id', $request->color_id);
        }



        $manufacture_id = session()->get('manufacture_id') ?? '';
        $model_id = session()->get('model_id') ?? '';
        $year = session()->get('year') ?? '';
        $color_id = session()->get('color_id') ?? '';

        // Fetch data from LocalPurchase model
        $localPurchases = LocalPurchase::get();
        // Fetch data from ForeignPurchase model
        $foreignPurchases = ForeginPurchase::get();
        // Combine the collections
        $data = $localPurchases->concat($foreignPurchases);


        if (isset($manufacture_id) && $manufacture_id != '') {

            //$manufacture_id = $request->manufacture_id;
            $data = $data->filter(function ($item) use ($manufacture_id) {
                return stripos($item->vehicle_manufacture_id, $manufacture_id) !== false;
            });
        }
        if (isset($model_id)) {
            //$model_id = $request->model_id;
            $data = $data->filter(function ($item) use ($model_id) {
                return stripos($item->vehicle_model_id, $model_id) !== false;
            });
        }
        if (isset($year)) {
            // $year = $request->year;
            $data = $data->filter(function ($item) use ($year) {
                return stripos($item->year_of_manufacture, $year) !== false;
            });
        }
        if (isset($color_id)) {
            //  $color_id = $request->color_id;
            $data = $data->filter(function ($item) use ($color_id) {
                return stripos($item->exterior_color_id, $color_id) !== false;
            });
        }
        //dd($data);
        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');

            return DataTables::of($data)
                ->editColumn('type', function ($row) {
                    $htmlContent = '';
                    if(isset($row->sell) && $row->sell->is_sell != 1 || !isset($row->sell->is_sell)){
                        $htmlContent = '.   <div style="background-color: #fff; padding:5px 20px; margin: 0px 20px;">
                                <div>
                                    <h4>
                                        ' . ($row->vehicle->manufacture->name ?? '') . '-' . ($row->vehicle->model_origin->model ?? '') . '(
                                        ' . ($row->vehicle->chassis_code->name ?? '') . ',
                                        ' . ($row->vehicle->displacement_engine->cc ?? '') . 'cc , 
                                        ' . ($row->vehicle->transmission ?? '') . ',
                                        ' . ($row->vehicle->drive_system ?? '') . ',
                                        ' . ($row->vehicle->fuel_type ?? '') . '
                                        )-' . ($row->year_of_manufacture ?? '') . '
                                        &nbsp;
                        ';

                        if (isset($row->sell) && $row->sell->is_book == 1) {
                            $htmlContent .= '<i class="fas fa-check-circle text-success bg-success"></i>';
                        }elseif (isset($row->pre_order_id)) {
                            $htmlContent .= '<i class="fas fa-check-circle text-success bg-success"></i>';
                        }
                        $htmlContent .= '
                                    </h4>
                                </div>
                                <table class="table">
                                    <tr>
                                        <td style="width: 20%">
                                            Vin/Chassis No: ' . ($row->vehicle->chassis_code->name ?? '') . ' <br>
                                            Engine No: NA <br>
                                            Code:
                                        </td>
                                        <td style="width: 20%">
                                            Condition: ' . ($row->vehicle_condition ?? '') . ' <br>
                                            Reg. No: ' . ($row->registration_no ?? '') . '<br>
                                            Color: ' . ($row->registration_no ?? '') . '
                                        </td>
                                        <td style="width: 20%">
                                            Auction Grade: 5<br>
                                            Mileage: 78000.00<br>
                                            Location: Showroom
                                        </td>
                                        <td style="width: 20%">
                                            Description:
                                        </td>
                                        <td></td>
                                        <td style="width: 20% ;display:flex; flex-direction:row">
                                            <form action="' . url('vehicle/book', ['id' => $row->id]) . '" method="get">
                                                ' . csrf_field() . '
                                                <input type="hidden" value="' . ($row->type ?? '') . '" name="type">';
                                                if (isset($row->sell) && $row->sell->is_book == 1 || isset($row->pre_order_id)) {
                                                    $htmlContent .= '<button type="submit" class="btn btn-sm">Proceed to Sell</button>';
                                                } else{
                                                    $htmlContent .= '<button type="submit" class="btn btn-sm">Book Now</button>';
                                                }                                          
                                            $htmlContent .= ' </form>
                                            <div class="dropdown" style="margin-left:5px">
                                                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    More Actions
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item" href="#">Action 1</a>
                                                    <a class="dropdown-item" href="#">Action 2</a>
                                                    <a class="dropdown-item" href="#">Action 3</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        ';
                    }
                    return $htmlContent;
                })

                ->rawColumns(['type'])
                ->make(true);
        }
        return view('vehicle_sell.new_book', compact('data', 'manufacture_id', 'model_id', 'color_id', 'year'));
    }



    public function new_book_add(Request $request, $id)
    {
        //dd($request->all());
        $business_id = $business_id = request()->session()->get('user.business_id');
        //dd($business_id);
        $type = $request->type;
        if ($type == 'local') {
            $data = LocalPurchase::with('vehicle')->find($id);
        } else {
            $data = ForeginPurchase::find($id);
        }

        $business_id = request()->session()->get('user.business_id');
        $walk_in_customer = $this->contactUtil->getWalkInCustomer($business_id);
        $types = [];
        if (auth()->user()->can('supplier.create')) {
            $types['supplier'] = __('report.supplier');
        }
        if (auth()->user()->can('customer.create')) {
            $types['customer'] = __('report.customer');
        }
        if (auth()->user()->can('supplier.create') && auth()->user()->can('customer.create')) {
            $types['both'] = __('lang_v1.both_supplier_customer');
        }
        $customer_groups = CustomerGroup::forDropdown($business_id);
        // dd($data);
        return view('vehicle_sell.create', compact('data', 'walk_in_customer', 'types', 'customer_groups','business_id'));
    }

    public function index()
    {
        $manufacture_id = session()->get('manufacture_id') ?? '';
        $model_id = session()->get('model_id') ?? '';
        $year = session()->get('year') ?? '';
        $color_id = session()->get('color_id') ?? '';

          if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');

            $unit = VehicleSell::get();

            return Datatables::of($unit)
                
                ->addColumn('name',function ($row) {
                     $htmlContent =  ''. ($row->vehicle->manufacture->name ?? '') . '-' . ($row->vehicle->model_origin->model ?? '') . '(
                                        ' . ($row->vehicle->chassis_code->name ?? '') . ',
                                        ' . ($row->vehicle->displacement_engine->cc ?? '') . 'cc , 
                                        ' . ($row->vehicle->transmission ?? '') . ',
                                        ' . ($row->vehicle->drive_system ?? '') . ',
                                        ' . ($row->vehicle->fuel_type ?? '') . '
                                        )-' . ($row->year_of_manufacture ?? '') . '
                                        &nbsp;
                        ';

                     return $htmlContent;
                })
                ->addColumn(
                    'action',
                    '@can("terms.update")
                    <span data-href="{{action(\'App\Http\Controllers\TermsController@destroy\', 
                        [$id])}}"  data-id="{{ $id }}" class="btn btn-xs btn-success delete_terms_button" id="delete_terms_button">
                        <i class="glyphicon glyphicon-eye-open"></i>
                    </span>
                    <span data-href="{{action(\'App\Http\Controllers\TermsController@edit\', 
                    [$id])}}" data-id="{{ $id }}" class="btn btn-xs btn-primary edit_terms_button" id="edit_terms_button"><i class="glyphicon glyphicon-edit"></i> 
                    </span>
                    &nbsp;
                    @endcan
                    @can("terms.delete")
                       
                        <span data-href="{{action(\'App\Http\Controllers\TermsController@destroy\', 
                        [$id])}}"  data-id="{{ $id }}" class="btn btn-xs btn-primary delete_terms_button" id="delete_terms_button">
                        <i class="glyphicon glyphicon-file"></i> 
                        </span>
                         <span data-href="{{action(\'App\Http\Controllers\TermsController@destroy\', 
                        [$id])}}"  data-id="{{ $id }}" class="btn btn-xs btn-danger delete_terms_button" id="delete_terms_button">
                        <i class="glyphicon glyphicon-trash"></i> 
                        </span>
                        
                    @endcan'
                )
                ->editColumn('customer_id',function ($row) {return $row->customer->name;})
                ->editColumn('sold_by',function ($row) {
                    if(isset($row->soldBy)){
                        $dd = $row->soldBy->first_name.' '.$row->soldBy->last_name;
                    }else{
                         $dd = '';
                    }
                    return $dd;
                })
                 ->editColumn('is_sell',function ($row) {
                    if($row->is_sell== 0){
                        $row->is_book== 1?$dd="Booked":$dd="";
                    }else{
                         $dd = 'Sold';
                    }
                    return $dd;
                })
                 ->editColumn('is_delivered',function ($row) {
                    if($row->is_delivered== 0){
                        $dd=" ";
                    }else{
                         $dd = 'Delivered';
                    }
                    return $dd;
                })
               // ->removeColumn('id')
                ->rawColumns(['action','name'])
                ->make(true);
        }
        return view('vehicle_sell.index',compact('manufacture_id','model_id','year','color_id'));
    }
    public function pending()
    {
        $manufacture_id = session()->get('manufacture_id') ?? '';
        $model_id = session()->get('model_id') ?? '';
        $year = session()->get('year') ?? '';
        $color_id = session()->get('color_id') ?? '';

          if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');

            $unit = VehicleSell::where('is_delivered',0)->get();

            return Datatables::of($unit)
                
                ->addColumn('name',function ($row) {
                     $htmlContent =  ''. ($row->vehicle->manufacture->name ?? '') . '-' . ($row->vehicle->model_origin->model ?? '') . '(
                                        ' . ($row->vehicle->chassis_code->name ?? '') . ',
                                        ' . ($row->vehicle->displacement_engine->cc ?? '') . 'cc , 
                                        ' . ($row->vehicle->transmission ?? '') . ',
                                        ' . ($row->vehicle->drive_system ?? '') . ',
                                        ' . ($row->vehicle->fuel_type ?? '') . '
                                        )-' . ($row->year_of_manufacture ?? '') . '
                                        &nbsp;
                        ';

                     return $htmlContent;
                })
                ->addColumn(
                    'action',
                    '@can("terms.update")
                    <span data-href="{{action(\'App\Http\Controllers\TermsController@destroy\', 
                        [$id])}}"  data-id="{{ $id }}" class="btn btn-xs btn-success delete_terms_button" id="delete_terms_button">
                        <i class="glyphicon glyphicon-eye-open"></i>
                    </span>
                    <span data-href="{{action(\'App\Http\Controllers\TermsController@edit\', 
                    [$id])}}" data-id="{{ $id }}" class="btn btn-xs btn-primary edit_terms_button" id="edit_terms_button"><i class="glyphicon glyphicon-edit"></i> 
                    </span>
                    &nbsp;
                    @endcan
                    @can("terms.delete")
                       
                        <span data-href="{{action(\'App\Http\Controllers\TermsController@destroy\', 
                        [$id])}}"  data-id="{{ $id }}" class="btn btn-xs btn-primary delete_terms_button" id="delete_terms_button">
                        <i class="glyphicon glyphicon-file"></i> 
                        </span>
                         <span data-href="{{action(\'App\Http\Controllers\TermsController@destroy\', 
                        [$id])}}"  data-id="{{ $id }}" class="btn btn-xs btn-danger delete_terms_button" id="delete_terms_button">
                        <i class="glyphicon glyphicon-trash"></i> 
                        </span>
                        
                    @endcan'
                )
                ->editColumn('customer_id',function ($row) {return $row->customer->name;})
                ->editColumn('sold_by',function ($row) {
                    if(isset($row->soldBy)){
                        $dd = $row->soldBy->first_name.' '.$row->soldBy->last_name;
                    }else{
                         $dd = '';
                    }
                    return $dd;
                })
                 ->editColumn('is_sell',function ($row) {
                    if($row->is_sell== 0){
                        $row->is_book== 1?$dd="Booked":$dd="";
                    }else{
                         $dd = 'Sold';
                    }
                    return $dd;
                })
                 ->editColumn('is_delivered',function ($row) {
                    if($row->is_delivered== 0){
                        $dd=" ";
                    }else{
                         $dd = 'Delivered';
                    }
                    return $dd;
                })
               // ->removeColumn('id')
                ->rawColumns(['action','name'])
                ->make(true);
        }
        return view('vehicle_sell.pending',compact('manufacture_id','model_id','year','color_id'));
    }
    public function delivered()
    {
        $manufacture_id = session()->get('manufacture_id') ?? '';
        $model_id = session()->get('model_id') ?? '';
        $year = session()->get('year') ?? '';
        $color_id = session()->get('color_id') ?? '';

          if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');

            $unit = VehicleSell::where('is_delivered',1)->get();

            return Datatables::of($unit)
                
                ->addColumn('name',function ($row) {
                     $htmlContent =  ''. ($row->vehicle->manufacture->name ?? '') . '-' . ($row->vehicle->model_origin->model ?? '') . '(
                                        ' . ($row->vehicle->chassis_code->name ?? '') . ',
                                        ' . ($row->vehicle->displacement_engine->cc ?? '') . 'cc , 
                                        ' . ($row->vehicle->transmission ?? '') . ',
                                        ' . ($row->vehicle->drive_system ?? '') . ',
                                        ' . ($row->vehicle->fuel_type ?? '') . '
                                        )-' . ($row->year_of_manufacture ?? '') . '
                                        &nbsp;
                        ';

                     return $htmlContent;
                })
                ->addColumn(
                    'action',
                    '@can("terms.update")
                    <span data-href="{{action(\'App\Http\Controllers\TermsController@destroy\', 
                        [$id])}}"  data-id="{{ $id }}" class="btn btn-xs btn-success delete_terms_button" id="delete_terms_button">
                        <i class="glyphicon glyphicon-eye-open"></i>
                    </span>
                    <span data-href="{{action(\'App\Http\Controllers\TermsController@edit\', 
                    [$id])}}" data-id="{{ $id }}" class="btn btn-xs btn-primary edit_terms_button" id="edit_terms_button"><i class="glyphicon glyphicon-edit"></i> 
                    </span>
                    &nbsp;
                    @endcan
                    @can("terms.delete")
                       
                        <span data-href="{{action(\'App\Http\Controllers\TermsController@destroy\', 
                        [$id])}}"  data-id="{{ $id }}" class="btn btn-xs btn-primary delete_terms_button" id="delete_terms_button">
                        <i class="glyphicon glyphicon-file"></i> 
                        </span>
                         <span data-href="{{action(\'App\Http\Controllers\TermsController@destroy\', 
                        [$id])}}"  data-id="{{ $id }}" class="btn btn-xs btn-danger delete_terms_button" id="delete_terms_button">
                        <i class="glyphicon glyphicon-trash"></i> 
                        </span>
                        
                    @endcan'
                )
                ->editColumn('customer_id',function ($row) {return $row->customer->name;})
                ->editColumn('sold_by',function ($row) {
                    if(isset($row->soldBy)){
                        $dd = $row->soldBy->first_name.' '.$row->soldBy->last_name;
                    }else{
                         $dd = '';
                    }
                    return $dd;
                })
                 ->editColumn('is_sell',function ($row) {
                    if($row->is_sell== 0){
                        $row->is_book== 1?$dd="Booked":$dd="";
                    }else{
                         $dd = 'Sold';
                    }
                    return $dd;
                })
                 ->editColumn('is_delivered',function ($row) {
                    if($row->is_delivered== 0){
                        $dd=" ";
                    }else{
                         $dd = 'Delivered';
                    }
                    return $dd;
                })
               // ->removeColumn('id')
                ->rawColumns(['action','name'])
                ->make(true);
        }
        return view('vehicle_sell.delivered',compact('manufacture_id','model_id','year','color_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
          $validationRules = [
                'sale_date' => 'required|date',
                'contact_id' => 'required|integer',
                'invoice_number' => 'required|string',
                'book_by' => 'required|string',
                'terms' =>'required'
                // Add more rules for other fields
            ];
          $validator = Validator::make($request->all(), $validationRules);

            // Check if validation fails
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        // dd($request->all());
        $id = $request->purchase_id;
        if ($request->purchase_type == 'local') {
            $purchase = LocalPurchase::find($id);
        } else {
            $purchase = ForeginPurchase::find($id);
        }

        $v_sell = new VehicleSell();
        $v_sell->sell_date = $request->sale_date;
        $v_sell->customer_id = $request->contact_id;
        $v_sell->invoice_number = $request->invoice_number;
        $v_sell->pre_order_id = $request->pre_order_id;
        $v_sell->vehicle_type = $purchase->vehicle_type;
        $v_sell->vehicle_condition = $purchase->vehicle_condition;
        $v_sell->vehicle_id = $purchase->vehicle_id;
        $v_sell->packge_trim_id = $purchase->packge_trim_id;
        $v_sell->chassis_id = $purchase->chassis_id;
        $v_sell->year_of_manufacture = $purchase->year_of_manufacture;
        $v_sell->exterior_color_id = $purchase->exterior_color_id;
        $v_sell->interior_color_id = $purchase->interior_color_id;
        $v_sell->millage = $purchase->millage;
        $v_sell->action_grade = $purchase->action_grade;
        $v_sell->registration_no = $purchase->registration_no;
        $v_sell->seating_capacity = $purchase->seating_capacity;
        $v_sell->tyre_size_id = $purchase->tyre_size_id;
        $v_sell->tracking_code = $purchase->tracking_code;
        $v_sell->vehicle_description = $purchase->vehicle_description;
        $v_sell->vehicle_location_id = $purchase->vehicle_location_id;
        $v_sell->vehicle_location_name = $purchase->vehicle_location_name;
        $v_sell->tracking_field_name = $purchase->tracking_field_name;

        $v_sell->discounted_price = $request->discounted_price;
        $v_sell->total_of_other_cost = $request->total_of_other_cost;
        $v_sell->service_warranty = $request->service_warranty;
        $v_sell->less_part_Service = $request->less_part_Service;
        $v_sell->part_exchange = $request->part_exchange;
        $v_sell->total_to_pay = $request->total_to_pay;
        $v_sell->vat = $request->vat;
        $v_sell->discount = $request->discount;
        $v_sell->service_price = $request->service_price;
        $v_sell->sub_total = $request->sub_total;
        $v_sell->sale_price = $request->sale_price;
        $v_sell->purchase_type = $request->purchase_type;
        $v_sell->purchase_id = $request->purchase_id;
        $v_sell->is_book = 1;
        $v_sell->save();
        // dd($v_sell->id);
        $purchase->sell_id = $v_sell->id;
        $purchase->save();
        return redirect()->back()->with('status', [
            'success' => 1,
            'msg' => 'Successfully Booked',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\VehicleSell  $vehicleSell
     * @return \Illuminate\Http\Response
     */
    public function show(VehicleSell $vehicleSell)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\VehicleSell  $vehicleSell
     * @return \Illuminate\Http\Response
     */
    public function edit(VehicleSell $vehicleSell)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\VehicleSell  $vehicleSell
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $purchase_id = $request->purchase_id;
        if ($request->purchase_type == 'local') {
            $purchase = LocalPurchase::find($purchase_id);
        } else {
            $purchase = ForeginPurchase::find($purchase_id);
        }
        $v_sell = VehicleSell::find($id);
        $v_sell->is_sell = 1;
        $v_sell->save();
        return redirect()->back()->with('status', [
            'success' => 1,
            'msg' => 'Successfully Done',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\VehicleSell  $vehicleSell
     * @return \Illuminate\Http\Response
     */
    public function destroy(VehicleSell $vehicleSell)
    {
        //
    }
    public function manufacture_model(Request $request)
    {
        if (request()->ajax()) {
            try {

                $id = $request->id;
                $model =  VehicleModel::where('vehicle_manufacture_id', $id)->get();

                $output = [
                    'success' => true,
                    'model' => $model,
                    // 'msg' => 'New Terms & Condition Added Successfully',
                ];
            } catch (\Exception $e) {
                \Log::emergency('File:' . $e->getFile() . 'Line:' . $e->getLine() . 'Message:' . $e->getMessage());

                $output = [
                    'success' => false,
                    'msg' => __('messages.something_went_wrong'),
                ];
            }

            return $output;
        }
    }
}
