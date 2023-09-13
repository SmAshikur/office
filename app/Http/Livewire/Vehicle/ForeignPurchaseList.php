<?php

namespace App\Http\Livewire\Vehicle;

use App\User;
use App\Contact;
use App\TyreSize;
use Carbon\Carbon;
use App\VehicleModel;
use App\CustomerGroup;
use App\ExteriorColor;
use App\InteriorColor;
use App\VehicleProduct;
use Livewire\Component;
use App\ForeginPurchase;
use App\VehiclePreOrder;
use App\PackageTrimVariant;
use App\VehicleManufacture;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;

class ForeignPurchaseList extends Component
{
    use WithFileUploads;
    public $size, $color, $color_code, $name,
        $vehicle_model_id, $opening_stock, $foregin_currency, $exchange_rate,
        $vehicle_manufacture_id, $purchase_date, $purchase_form, $supplier, $lot_number, $purchase_mode, $source_of_product, $vehicle_type, $pre_order_id, $vehicle_condition, $vehicle_id, $packge_trim_id, $chassis_id, $engine_id, $year_of_manufacture, $exterior_color_id, $interior_color_id, $millage, $action_grade, $seating_capacity, $tyre_size_id, $tracking_code, $vehicle_image, $vehicle_description, $pricing_information, $vehicle_price, $vehicle_location_name,
        $wholesale_discount, $wholesale_price, $sale_price_note, $total_estimated_cost, $total_cost, $margin, $sale_price, $vehicle_location_id, $vehicle_location_type, $tracking_field_name, $cost_type = [], $amount = [], $count = 1, $pre_order_show = true;
    public $contact_type, $customer_name, $contact_id, $customer_group, $mobile, $alernative_contact_no, $land_line, $email;
    public $purchase_id, $filter_month, $filter_year, $filter_manufacture, $filter_supplier, $filter_condition, $filter_model, $filter_sale_status, $filter_payment_status, $filter_quick_search;
    public $seating_capacities = [
        2, 4, 5, 6, 7, 8, 9, 10, 11, 12,
        13, 14, 15, 16, 17, 18, 19, 20, 21, 22,
        23, 24, 25, 26, 27, 28, 29, 30, 31, 32,
        33, 34, 35, 36, 37, 38, 39, 40, 41, 42,
        43, 44, 45, 46, 47, 48, 49, 50, 51, 52,
        53, 54, 55, 56, 57, 58, 59, 60
    ];
    protected $rules = [
        'purchase_date' => 'required',
        'supplier' => 'required',
        'lot_number' => 'nullable',
        'source_of_product' => 'required',
        'vehicle_type' => 'required',
        'vehicle_condition' => 'required',
        'vehicle_id' => 'required',
        'chassis_id' => 'required',
        'year_of_manufacture' => 'required',
    ];
    public function mount()
    {
        $this->source_of_product = 'Pre Order';
        $this->vehicle_type = 'Passenger';
        $this->vehicle_condition = 'Recondition';
        $this->purchase_date = Carbon::now()->format('Y-m-d');
        $this->purchase_form = 'Supplier';
        $this->purchase_mode = 'Purchase';
        $this->vehicle_location_type = 'port';
    }
    protected $queryString = [
        'filter_month' => ['except' => ''],
        'filter_year' => ['except' => ''],
        'filter_supplier' => ['except' => ''],
        'filter_condition' => ['except' => ''],
        'filter_model' => ['except' => ''],
        'filter_sale_status' => ['except' => ''],
        'filter_payment_status' => ['except' => ''],
        'filter_quick_search' => ['except' => ''],
    ];
    public function render()
    {
        if ($this->vehicle_id) {
            $packages = PackageTrimVariant::when($this->vehicle_id, function ($query) {
                $vehicle = VehicleProduct::find($this->vehicle_id);
                $query->where('vehicle_manufacture_id',  $vehicle->vehicle_manufacture_id);
            })->get();
        } else {
            $packages = [];
        }
        $business_id = request()->session()->get('user.business_id');
        return view('livewire.vehicle.foreign-purchase-list', [
            'filter_suppliers' => Contact::whereIn('type', ['supplier', 'both'])->get(),
            'filter_manufactures' => VehicleManufacture::all(),
            'filter_models' => VehicleModel::when($this->filter_manufacture, function ($query) {
                $query->where('vehicle_manufacture_id', $this->filter_manufacture);
            })->get(),


            'purchases' => ForeginPurchase::when($this->filter_month, function ($query) {
                [$year, $month] = explode('-', $this->filter_month);
                return $query
                    ->whereYear('purchase_date', $year)
                    ->whereMonth('purchase_date', $month);
            })
                ->when($this->filter_supplier, function ($query) {
                    $query->where('supplier', $this->filter_supplier);
                })
                ->when($this->filter_condition, function ($query) {
                    $query->where('vehicle_condition', $this->filter_condition);
                })
                ->when($this->filter_sale_status, function ($query) {
                    $query->where('sale_status', $this->filter_sale_status);
                })
                ->when($this->filter_payment_status, function ($query) {
                    $query->where('lc_status', $this->filter_payment_status);
                })
                ->when($this->filter_quick_search, function ($query) {
                    $query->orWhere('chassis_id', $this->filter_quick_search)
                        ->orWhere('registration_no', $this->filter_quick_search)
                        ->orWhere('vehicle_condition', $this->vehicle_condition);
                })
                ->paginate(10),
            'customers' => User::all(),
            'recived_users' => User::all(),
            'suppliers' => Contact::whereIn('type', ['supplier', 'both'])->get(),
            'customers' => Contact::whereIn('type', ['customer', 'both'])->get(),
            'products' => VehicleProduct::when($this->vehicle_type, function ($query) {
                $query->where('product_category', 'LIKE', '%' . $this->vehicle_type . '%');
            })->get(),
            'packages' => $packages,
            'exterior_colors' => ExteriorColor::all(),
            'interior_colors' => InteriorColor::all(),
            'tyre_sizes' => TyreSize::all(),
            'pre_orders' => VehiclePreOrder::where('purchase_status', '!=', 'Confirm')->get(),
            'year_of_manufactures' => array_reverse(range(1950, 2024)),
            'vehicle_manufactures' => VehicleManufacture::all(),
            'models' => VehicleModel::when($this->vehicle_manufacture_id, function ($query) {
                $query->where('vehicle_manufacture_id', $this->vehicle_manufacture_id);
            })->get(),
            'types' => [
                'supplier' => __('report.supplier'),
                'customer' => __('report.customer'),
                'both' => __('lang_v1.both_supplier_customer')
            ],
            'customer_groups' => CustomerGroup::forDropdown($business_id),

        ]);
    }
    public function edit($id)
    {
        $res = ForeginPurchase::find($id);
        if (!blank($res)) {
            $this->count = $res->pricing_information != "" ? count($res->pricing_information) : 1;
            $this->cost_type = $res->pricing_information != "" ? array_column($res->pricing_information, 'cost_type') : [];
            $this->amount = $res->pricing_information != "" ? array_column($res->pricing_information, 'amount') : [];
            $this->purchase_id = $id;

            $this->purchase_date = $res->purchase_date;
            $this->purchase_form = $res->purchase_form;
            $this->supplier = $res->supplier;
            $this->lot_number = $res->lot_number;
            $this->purchase_mode = $res->purchase_mode;
            $this->source_of_product = $res->source_of_product;
            $this->vehicle_type = $res->vehicle_type;
            $this->pre_order_id = $res->pre_order_id;
            //Pre Order Settings
            if ($res->source_of_product == 'Pre Order') {
                $this->pre_order_show = false;
            } else {
                $this->pre_order_show = false;
            }
            $this->vehicle_condition = $res->vehicle_condition;
            $this->vehicle_id = $res->vehicle_id;
            $this->packge_trim_id = $res->packge_trim_id;
            $this->chassis_id = $res->chassis_id;
            $this->engine_id = $res->engine_id;
            $this->year_of_manufacture = $res->year_of_manufacture;
            $this->exterior_color_id = $res->exterior_color_id;
            $this->interior_color_id = $res->interior_color_id;
            $this->millage = $res->millage;
            $this->action_grade = $res->action_grade;
            $this->seating_capacity = $res->seating_capacity;
            $this->tyre_size_id = $res->tyre_size_id;
            $this->tracking_code = $res->tracking_code;
            // $this->vehicle_image = $res->vehicle_image;
            $this->vehicle_description = $res->vehicle_description;
            $this->pricing_information = $res->pricing_information;
            $this->vehicle_price = $res->vehicle_price;
            $this->total_estimated_cost = $res->total_estimated_cost;
            $this->total_cost = $res->total_cost;
            $this->margin = $res->margin;
            $this->sale_price = $res->sale_price;
            $this->sale_price_note = $res->sale_price_note;
            $this->wholesale_price = $res->wholesale_price;
            $this->wholesale_discount = $res->wholesale_discount;
            $this->vehicle_location_type = $res->vehicle_location_type;
            $this->vehicle_location_id = $res->vehicle_location_id;
            $this->vehicle_location_name = $res->vehicle_location_name;
            $this->tracking_field_name = $res->tracking_field_name;
            $this->foregin_currency = $res->foregin_currency;
            $this->opening_stock = $res->opening_stock;
            $this->exchange_rate = $res->exchange_rate;
            $this->dispatchBrowserEvent('show-modal', ['id' => 'update-purchase-modal']);
            $this->emit('propertyUpdated');
        }
    }
    public function updated($propertyName)
    {
        $this->emit('propertyUpdated');

        if ($propertyName == 'filter_model') {
            if ($this->filter_manufacture == '') {
                $this->dispatchBrowserEvent('error', ['msg' => 'Please Select Vehicle Manufacture First']);
                $this->filter_model = '';
            }
        }
        if ($propertyName == 'source_of_product') {
            if ($this->source_of_product == 'Pre Order') {
                $this->pre_order_show = true;
                // $this->dispatchBrowserEvent('show-modal', ['id' => 'pre-order-modal']);
            } else {
                $this->pre_order_show = false;
                $this->emit('propertyUpdated');
            }
        }
        if ($propertyName == 'vehicle_type') {
            $this->emit('propertyUpdated');
        }
        //Pre Order
        if ($propertyName == 'pre_order_id') {
            $this->preOrderSelected();
        }
        //Vehicle
        if ($propertyName == 'vehicle_id') {
            $vehicle = VehicleProduct::find($this->vehicle_id);
            $this->chassis_id = $vehicle->chassis_code->name ?? '';
        }
        if ($propertyName == 'vehicle_price') {
            $this->total_cost = intval($this->total_estimated_cost) + intval($this->vehicle_price);
            if ($this->sale_price != null) {
                $this->margin = intval($this->sale_price) - intval($this->total_cost);
                $this->wholesale_price = intval($this->sale_price) - intval($this->wholesale_discount);
            }
        }
        //Margin Price
        if ($propertyName == 'margin') {
            $this->sale_price = intval($this->total_estimated_cost) + intval($this->vehicle_price) + intval($this->margin);
            $this->wholesale_price = (intval($this->total_estimated_cost) + intval(intval($this->vehicle_price)) + intval($this->margin)) - intval(intval($this->wholesale_discount));
            // if ($this->sale_price != null) {
            //     $this->margin = $this->sale_price - $this->total_cost;
            //     $this->wholesale_price = $this->sale_price - $this->wholesale_discount;
            // }
        }
        if ($propertyName == 'sale_price') {
            $this->margin = intval($this->sale_price) - intval($this->total_cost);
            $this->wholesale_price = intval($this->sale_price) - intval($this->wholesale_discount);
        }
        if ($propertyName == 'wholesale_discount') {
            $this->wholesale_price = intval($this->sale_price) - intval($this->wholesale_discount);
        }
    }
    function preOrderSelected()
    {
        $this->pre_order_show = false;
        $pre_order = VehiclePreOrder::find($this->pre_order_id);
        $this->vehicle_type = $pre_order->vehicle_category;
        $this->vehicle_id = $pre_order->product_id;
        $this->millage = $pre_order->preferred_millage;
        $this->action_grade = $pre_order->preferred_year;
        $vehicle = VehicleProduct::find($pre_order->product_id);
        $this->chassis_id = $vehicle->chassis_code->name ?? '';
    }

    // function generateInvoiceNumber($prefix = 'INV')
    // {
    //     // Get the current date in the format YYYYMMDD.
    //     $date = now()->format('Ymd');

    //     // Get the latest invoice number from the database.
    //     $latestInvoice = DB::table('local_purchases')
    //         ->select('invoice_number')
    //         ->orderBy('id', 'desc')
    //         ->first();

    //     if ($latestInvoice) {
    //         // Extract the sequential number from the latest invoice number.
    //         $lastSeqNumber = substr($latestInvoice->invoice_number, -4);
    //         $newSeqNumber = str_pad($lastSeqNumber + 1, 4, '0', STR_PAD_LEFT);
    //     } else {
    //         // If no previous invoice, start with 0001.
    //         $newSeqNumber = '0001';
    //     }

    //     // Combine the prefix, date, and sequential number to create the new invoice number.
    //     $invoiceNumber = $prefix . $date . $newSeqNumber;

    //     $this->invoice_number = $invoiceNumber;
    // }
    public function addRow()
    {
        $this->total_estimated_cost = 0;
        $new_count = 1;
        for ($i = 0; $i < $this->count; $i++) {
            if (!isset($this->cost_type[$i]) || !isset($this->amount[$i])) {
                $this->dispatchBrowserEvent('error', ['msg' => 'Please enter cost type or amount data']);
            } else {
                $this->total_estimated_cost += $this->amount[$i];
                $new_count++;
            }
        }
        $this->total_cost = $this->total_estimated_cost + intval($this->vehicle_price);
        if ($this->sale_price != null) {
            $this->margin = $this->sale_price - $this->total_cost;
            $this->wholesale_price = $this->sale_price - $this->wholesale_discount;
        }
        $this->count = $new_count;
    }

    public function resetAmount()
    {
        unset($this->cost_type[0]);
        unset($this->amount[0]);

        $this->total_estimated_cost = 0;
        $this->total_estimated_cost = 0;
        $this->count = 1;
    }
    public function removeRow($index)
    {
        if (isset($this->cost_type[$index]) && isset($this->amount[$index])) {
            $this->total_estimated_cost -= $this->amount[$index];
        }

        unset($this->cost_type[$index]);
        unset($this->amount[$index]);

        $this->total_cost = $this->total_estimated_cost + intval($this->vehicle_price);
        if ($this->sale_price != null) {
            $this->margin = $this->sale_price - $this->total_cost;
            $this->wholesale_price = $this->sale_price - $this->wholesale_discount;
        }

        $this->count = count($this->cost_type);
    }
    public function update()
    {
        $purchase = ForeginPurchase::find($this->purchase_id);
        $validatedData = $this->validate();
        if ($this->vehicle_image) {
            $vehicle_image = $this->vehicle_image->store('purchase', 'vehicle');
        } else {
            $vehicle_image = $purchase->vehicle_image;
        }
        if ($this->count > 1) {

            for ($i = 0; $i < ($this->count - 1); $i++) {
                $jsonData[] = [
                    'cost_type' => $this->cost_type[$i],
                    'amount' => $this->amount[$i],
                ];
            }
        }

        $purchase->update([
            'purchase_date' => $this->purchase_date,
            'supplier' => $this->supplier,
            'lot_number' => $this->lot_number,
            'source_of_product' => $this->source_of_product,
            'vehicle_type' => $this->vehicle_type,
            'vehicle_condition' => $this->vehicle_condition,
            'vehicle_id' => $this->vehicle_id,
            'packge_trim_id' => $this->packge_trim_id,
            'chassis_id' => $this->chassis_id,
            'engine_id' => $this->engine_id,
            'year_of_manufacture' => $this->year_of_manufacture,
            'exterior_color_id' => $this->exterior_color_id,
            'interior_color_id' => $this->interior_color_id,
            'millage' => $this->millage,
            'action_grade' => $this->action_grade,
            'seating_capacity' => $this->seating_capacity,
            'tyre_size_id' => $this->tyre_size_id,
            'tracking_code' => $this->tracking_code,
            'vehicle_image' => $vehicle_image,
            'vehicle_description' => $this->vehicle_description,
            'pricing_information' => $this->pricing_information,
            'vehicle_price' => $this->vehicle_price,
            'total_estimated_cost' => $this->total_estimated_cost,
            'total_cost' => $this->total_cost,
            'margin' => $this->margin,
            'sale_price' => $this->sale_price,
            'vehicle_location_id' => $this->vehicle_location_id,
            'created_by' => auth()->user()->id,
        ]);
        $this->dispatchBrowserEvent('hide-modal', ['id' => 'update-purchase-modal']);

        $this->dispatchBrowserEvent('success', ['msg' => 'Foregin Purchase Updated Successfully']);
    }
    public function delete($id)
    {
        $res = ForeginPurchase::find($id);
        if ($res->sale_status == 'Unsold' && $res->lc_status == 'Pending') {
            $res->delete();
            $this->dispatchBrowserEvent('success', ['msg' => 'Foregin Purchase Deleted Successfully']);
        } else {
            $this->dispatchBrowserEvent('error', ['msg' => 'Delete Not Possible']);
        }
    }
    //Package Trim
    public function addPackageTrim()
    {
        $this->dispatchBrowserEvent('show-modal', ['id' => 'package-trim-modal']);
    }
    public function storePackageTrim()
    {
        PackageTrimVariant::create([
            'name' => $this->name,
            'vehicle_model_id' => $this->vehicle_model_id,
            'vehicle_manufacture_id' => $this->vehicle_manufacture_id,
            'created_by' => auth()->user()->id
        ]);
        $this->dispatchBrowserEvent('success', ['msg' => 'Package/Trim Added Successfully']);
        $this->dispatchBrowserEvent('hide-modal', ['id' => 'package-trim-modal']);
    }
    //Exterior Color
    public function addExteriorColor()
    {
        $this->dispatchBrowserEvent('show-modal', ['id' => 'exterior-color-modal']);
    }
    public function storeExteriorColor()
    {
        ExteriorColor::create([
            'color' => $this->color,
            'color_code' => $this->color_code,
            'created_by' => auth()->user()->id
        ]);
        $this->dispatchBrowserEvent('success', ['msg' => 'Exterior Color Added Successfully']);
        $this->dispatchBrowserEvent('hide-modal', ['id' => 'exterior-color-modal']);
    }
    //Interior Color
    public function addInteriorColor()
    {
        $this->dispatchBrowserEvent('show-modal', ['id' => 'interior-color-modal']);
    }
    public function storeInteriorColor()
    {
        InteriorColor::create([
            'color' => $this->color,
            'color_code' => $this->color_code,
            'created_by' => auth()->user()->id
        ]);
        $this->dispatchBrowserEvent('success', ['msg' => 'Interior Color Added Successfully']);
        $this->dispatchBrowserEvent('hide-modal', ['id' => 'interior-color-modal']);
    }
    //Exterior Color
    public function addTyreSize()
    {
        $this->dispatchBrowserEvent('show-modal', ['id' => 'tyre-size-modal']);
    }
    public function storeTyreSize()
    {
        TyreSize::create([
            'size' => $this->size,
            'created_by' => auth()->user()->id
        ]);
        $this->dispatchBrowserEvent('success', ['msg' => 'Tyre Added Successfully']);
        $this->dispatchBrowserEvent('hide-modal', ['id' => 'tyre-size-modal']);
    }
    public function addSupplier()
    {
        $this->contact_type = 'supplier';
        $this->dispatchBrowserEvent('show-modal', ['id' => 'supplier-modal']);
    }
    public function storeSupplier()
    {
        Contact::create([
            'business_id' => request()->session()->get('user.business_id'),
            'type' => $this->contact_type,
            'name' => $this->customer_name,
            'contact_id' => $this->contact_id,
            'email' => $this->email,
            'mobile' => $this->mobile,
            'landline' => $this->land_line,
            'alternative_number' => $this->alernative_contact_no,
            'created_by' => auth()->user()->id,
        ]);
        $this->dispatchBrowserEvent('hide-modal', ['id' => 'supplier-modal']);
        $this->dispatchBrowserEvent('success', ['msg' => 'Supplier Added Successfully']);
    }
}
