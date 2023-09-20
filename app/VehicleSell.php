<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleSell extends Model
{
    use HasFactory;

     public function pre_order()
    {
        return $this->belongsTo(VehiclePreOrder::class, 'pre_order_id', 'id');
    }
    public function customer()
    {
        return $this->belongsTo(Contact::class, 'customer_id', 'id');
    }
    public function soldBy()
    {
        return $this->belongsTo(User::class, 'sold_by', 'id');
    }
    public function vehicle()
    {
        return $this->belongsTo(VehicleProduct::class, 'vehicle_id', 'id')
        ->with(['manufacture','vehicle_model','body_type','chassis_code','displacement_engine']);
    }
    public function exterior_color()
    {
        return $this->belongsTo(ExteriorColor::class, 'exterior_color_id', 'id');
    }
    public function interior_color()
    {
        return $this->belongsTo(ExteriorColor::class, 'interior_color_id', 'id');
    }
    public function sell()
    {
        return $this->belongsTo(VehicleSell::class, 'purchase_id', 'id');
    }
}
