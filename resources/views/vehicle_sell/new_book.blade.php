@extends('layouts.app')
@section('title', __( 'lang_v1.all_sales'))

@section('content')

<section>
  <div class="container">
    <form action="">
      <div class="row m-10" style="margin-top: 50px;">
        <div class="col-md-3 p-2">
          <select name="" class="form-control" id="">
            <option value="">Select Manufacturer</option>
              @foreach(\App\VehicleManufacture::get() as $cost)
                <option value="{{$cost->id}}">{{$cost->name}}</option>
              @endforeach
          </select>
        </div>
        <div class="col-md-3 p-2">
          <select name="" class="form-control" id="">
            <option value="">Select </option>
          </select>
        </div>
        <div class="col-md-3 p-2">
          <select name="" class="form-control" id="">
            <option value="">Select year</option>
          </select>
        </div>
        <div class="col-md-3 p-2">
          <select name="" class="form-control" id="">
            <option value="">Select color</option>
          </select>
        </div>
        <hr>
        <div class="col-md-3">
          <select name="condition" class="form-control" id="">
            <option value="">Select Condition</option>
            <option value="new">Brand new</option>
            <option value="recondition">Recondition</option>
            <option value="used">Used</option>
          </select>
        </div>
        <div class="col-md-3">
          <select name="" class="form-control" id="">
            <option value="">Select status</option>
            <option value="recondition">Pre order </option>
            <option value="used">Booked </option>
          </select>
        </div>
        <div class="col-md-3">
          <select name="" class="form-control" id="">
            <option value="">Select location</option>
            <option value="recondition">Showroom </option>
            <option value="used">Partner Showroom </option>
          </select>
        </div>
        <div class="col-md-3">
          <div style="display: flex; flex-direction: row;">
            <input type="search" class="form-control" style="flex: 1;">
            <button style="margin-left: 5px;" class="btn btn-primary"><i class="fas fa-search"></i> Search</button>
          </div>
        </div>
      </div>
    </form>


    <div class="row">
      @foreach($data as $lp)
      {{$lp->type}}
      <table class="table">
        <div>
          <h4>
            {{$lp->vehicle->manufacture->name??''}}-{{$lp->vehicle->vehicle_model->name??''}}(
            {{$lp->vehicle->chassis_code->name??''}},
            {{$lp->vehicle->displacement_engine->name??''}},
            {{$lp->vehicle->transmission??''}},
            {{$lp->vehicle->drive_system??''}},
            {{$lp->vehicle->fuel_type??''}}
            )-{{$lp->registration_year}}
          </h4>
        </div>
        <tr>
          <td style="width:20%">
            Vin/Chassis No: {{$lp->vehicle->chassis_code->name??''}} <br>
            Engine No: NA <br>
            Code:
          </td>
          <td style="width:20%">
            Condition: {{$lp->vehicle_condition??''}} <br>
            Reg. No: {{$lp->registration_no??''}}<br>
            Color: {{$lp->registration_no??''}}
          </td>
          <td style="width:20%">
            Auction Grade: 5<br>
            Mileage: 78000.00<br>
            Location: Showroom
          </td>
          <td style="width:20%">
            Description:
          </td>
          <td>

          </td>
          <td style="width:20%">
            <form action="{{ url('vehicle/book', ['id' => $lp->id]) }}" method="get"> @csrf
              <input type="hidden" value="{{$lp->type}}" name="type">
              <button type="submit" class="btn btn-blue">Book Now</button>
            </form>

            <div class="dropdown">

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
      @endforeach
      {{ $data->links() }}
    </div>
  </div>
</section>

@stop