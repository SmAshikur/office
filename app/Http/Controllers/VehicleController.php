<?php

namespace App\Http\Controllers;

use App\ForeginPurchase;
use App\LocalPurchase;
use App\VehicleProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VehicleController extends Controller
{
    public function getVehicles()
    {
        if (request()->ajax()) {
            $term = request()->input('q', '');
            // $localPurchases = LocalPurchase::get();
            // // Fetch data from ForeignPurchase model
            // $foreignPurchases = ForeginPurchase::get();
            // // Combine the collections
            // $data = $localPurchases->concat($foreignPurchases);
            // $vehicles = $data->query();

            // if (!empty($term)) {
            //     $vehicles->where(function ($query) use ($term) {
            //             $query ->whereHas('vehicle', function ($vehicle) use ($term) {
            //                 $vehicle
            //                 ->whereHas('manufacture', function ($subQuery) use ($term) {
            //                     $subQuery->where('name', 'like', '%' . $term . '%');
            //                 })
            //                 ->orWhereHas('model_origin', function ($subQuery) use ($term) {
            //                     $subQuery->where('model', 'like', '%' . $term . '%');
            //                 })
            //                 ->orWhereHas('chassis_code', function ($subQuery) use ($term) {
            //                     $subQuery->where('name', 'like', '%' . $term . '%');
            //                 })
            //                 ->orWhereHas('displacement_engine', function ($subQuery) use ($term) {
            //                     $subQuery->where('cc', 'like', '%' . $term . '%');
            //                 })
            //                 ->orWhere('transmission', 'like', '%' . $term . '%')
            //                 ->orWhere('drive_system', 'like', '%' . $term . '%')
            //                 ->orWhere('fuel_type', 'like', '%' . $term . '%')
            //                 ->orWhere('year_of_manufacture', 'like', '%' . $term . '%');
            //             });

            //     });
            // }



            $vehicle = VehicleProduct::query();

            if (!empty($term)) {
                $vehicle->where(function ($query) use ($term) {
                  //  $query->whereHas('vehicle', function ($vehicle) use ($term) {
                        $query
                            ->whereHas('manufacture', function ($subQuery) use ($term) {
                                $subQuery->where('name', 'like', '%' . $term . '%');
                            })
                            ->orWhereHas('model_origin', function ($subQuery) use ($term) {
                                $subQuery->where('model', 'like', '%' . $term . '%');
                            })
                            ->orWhereHas('chassis_code', function ($subQuery) use ($term) {
                                $subQuery->where('name', 'like', '%' . $term . '%');
                            })
                            ->orWhereHas('displacement_engine', function ($subQuery) use ($term) {
                                $subQuery->where('cc', 'like', '%' . $term . '%');
                            })
                            ->orWhere('transmission', 'like', '%' . $term . '%')
                            ->orWhere('drive_system', 'like', '%' . $term . '%')
                            ->orWhere('fuel_type', 'like', '%' . $term . '%')
                             ;
                    });
                //});
            }
            $foreignPurchases = ForeginPurchase::query();

            if (!empty($term)) {
                $foreignPurchases->where(function ($query) use ($term) {
                    $query->whereHas('vehicle', function ($vehicle) use ($term) {
                        $vehicle
                            ->whereHas('manufacture', function ($subQuery) use ($term) {
                                $subQuery->where('name', 'like', '%' . $term . '%');
                            })
                            ->orWhereHas('model_origin', function ($subQuery) use ($term) {
                                $subQuery->where('model', 'like', '%' . $term . '%');
                            })
                            ->orWhereHas('chassis_code', function ($subQuery) use ($term) {
                                $subQuery->where('name', 'like', '%' . $term . '%');
                            })
                            ->orWhereHas('displacement_engine', function ($subQuery) use ($term) {
                                $subQuery->where('cc', 'like', '%' . $term . '%');
                            })
                            ->orWhere('transmission', 'like', '%' . $term . '%')
                            ->orWhere('drive_system', 'like', '%' . $term . '%')
                            ->orWhere('fuel_type', 'like', '%' . $term . '%')
                            ->orWhere('year_of_manufacture', 'like', '%' . $term . '%');
                    });
                });
            }
            $vehicle
                // ->join('vehicle_products', 'local_purchases.vehicle_id', '=', 'vehicle_products.id')
                ->join('vehicle_manufactures', 'vehicle_products.vehicle_manufacture_id', '=', 'vehicle_manufactures.id')
                ->join('model_origins', 'vehicle_products.model_origin_id', '=', 'model_origins.id')
                ->join('chassis_codes', 'vehicle_products.chassis_code_id', '=', 'chassis_codes.id')
                ->join('displacement_engine_ccs', 'vehicle_products.displacement_engine_cc_id', '=', 'displacement_engine_ccs.id')
                ->select(
                    'vehicle_products.id',
                    'vehicle_products.vehicle_manufacture_id',
                    'vehicle_products.model_origin_id',
                    DB::raw("CONCAT(vehicle_manufactures.name, ' - ',model_origins.model , '(' ,chassis_codes.name,',' , displacement_engine_ccs.cc,'-cc,' ,
                    vehicle_products.transmission, ',', vehicle_products.fuel_type, ') -', vehicle_products.id) AS text")   
            );
            // $foreignPurchases->join('vehicle_products', 'foregin_purchases.vehicle_id', '=', 'vehicle_products.id')
            //     ->join('vehicle_manufactures', 'vehicle_products.vehicle_manufacture_id', '=', 'vehicle_manufactures.id')
            //     ->join('model_origins', 'vehicle_products.model_origin_id', '=', 'model_origins.id')
            //     ->join('chassis_codes', 'vehicle_products.chassis_code_id', '=', 'chassis_codes.id')
            //     ->join('displacement_engine_ccs', 'vehicle_products.displacement_engine_cc_id', '=', 'displacement_engine_ccs.id')
            //     ->select(
            //         'foregin_purchases.id',
            //         'foregin_purchases.vehicle_id',
            //         'foregin_purchases.type',
            //         'vehicle_products.vehicle_manufacture_id',
            //         'vehicle_products.model_origin_id',
            //         DB::raw("CONCAT(vehicle_manufactures.name, ' - ',model_origins.model , '(' ,chassis_codes.name,',' , displacement_engine_ccs.cc,'-cc,' ,
            //         vehicle_products.transmission, ',', vehicle_products.fuel_type, ') -', foregin_purchases.year_of_manufacture) AS text")   
            // );

            $localResults = $vehicle->get();
           // $foreignResults = $foreignPurchases->with('vehicle')->get();
           // $data = $localResults->concat($foreignResults);
            $data = $localResults;
            // $vehicles->select('id'); // Adjust this according to your data structure

            // $vehicles = $vehicles->get();

            return response()->json($data);
        }
    }
    public function manufacture()
    {
        return view('vehicle.manufacture');
    }
    public function model()
    {
        return view('vehicle.model');
    }
    public function bodyType()
    {
        return view('vehicle.body-type');
    }
    public function chassisCode()
    {
        return view('vehicle.chassis-code');
    }
    public function packageTrimVariant()
    {
        return view('vehicle.package-trim-variant');
    }
    public function displacementEngningCc()
    {
        return view('vehicle.displacement-engine-cc');
    }
    public function tyreSize()
    {
        return view('vehicle.tyre-size');
    }
    public function interiorColor()
    {
        return view('vehicle.interior-color');
    }
    public function exteriorColor()
    {
        return view('vehicle.exterior-color');
    }
    public function modelOrigin()
    {
        return view('vehicle.model-origin');
    }
    public function addBike()
    {
        return view('vehicle.product.add-bike');
    }
    public function bikeList()
    {
        return view('vehicle.product.bike-list');
    }
    public function addCommercialVehicle()
    {
        return view('vehicle.product.add-commercial-vehicle');
    }
    public function commercialVehicleList()
    {
        return view('vehicle.product.commercial-vehicle-list');
    }
    public function addPassengerVehicle()
    {
        return view('vehicle.product.add-passenger-vehicle');
    }
    public function passengerVehicleList()
    {
        return view('vehicle.product.passenger-vehicle-list');
    }
    public function vehiclePreOrder()
    {
        return view('vehicle.pre-order.add-pre-order');
    }
    public function vehiclePreOrderList()
    {
        return view('vehicle.pre-order.pre-order-list');
    }
    public function vehicleLocalPurchase()
    {
        return view('vehicle.purchase.add-local-purchase');
    }
    public function vehicleLocalPurchaseList()
    {
        return view('vehicle.purchase.local-purchase-list');
    }
    public function vehicleForeignPurchase()
    {
        return view('vehicle.purchase.add-foreign-purchase');
    }
    public function vehicleForeignPurchaseList()
    {
        return view('vehicle.purchase.foreign-purchase-list');
    }
}
