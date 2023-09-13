<?php

namespace App\Http\Controllers;

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
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

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

    public function new_book()
    {
    // Fetch data from LocalPurchase model
    $localPurchases = LocalPurchase::all();
    // Fetch data from ForeignPurchase model
    $foreignPurchases = ForeginPurchase::all();
    // Combine the collections
    $data = $localPurchases->concat($foreignPurchases);
    // Paginate the combined collection
    $perPage = 10; // Number of items per page
    $currentPage = request()->input('page', 1); // Get the current page from the request
    $pagedData = $data->slice(($currentPage - 1) * $perPage, $perPage)->all();
    $data = new LengthAwarePaginator(
        $pagedData,
        $data->count(),
        $perPage,
        $currentPage,
         ['path' => Paginator::resolveCurrentPath()]
    );
    return view('vehicle_sell.new_book', compact('data'));
    }
    public function new_book_add(Request $request,$id)
    {  
        //dd($request->all());
        $type= $request->type;
        if($type=='local'){
            $data = LocalPurchase::with('vehicle')->find($id);
        }else{
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
        return view('vehicle_sell.create', compact('data', 'walk_in_customer', 'types', 'customer_groups'));
    }

    public function index()
    {
        return view('vehicle_sell.index');
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
        dd($request->all());
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
    public function update(Request $request, VehicleSell $vehicleSell)
    {
        //
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
}
