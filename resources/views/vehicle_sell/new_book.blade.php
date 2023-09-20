@extends('layouts.app')
@section('title', __( 'lang_v1.all_sales'))

@section('content')

<section>
  <div class="container">
    <!-- <h1>manu  = {{$manufacture_id}}</h1>
   <h1>model  = {{$model_id}}</h1>
   <h1>color  = {{$color_id}}</h1>
   <h1>year  = {{$year}}</h1> -->
    

    <form action="{{url('vehicle/book')}}" method="get">
      <div class="row m-10" style="margin-top: 50px;">
        <div class="col-md-3 p-2">
          <select name="manufacture_id" class="form-control" id="Manufacturer">
            <option value="">Select Manufacturer</option>
            
            @foreach(\App\VehicleManufacture::get() as $manufacturer)
            <option value="{{$manufacturer->id}}" @if($manufacturer->id == $manufacture_id) selected @endif>{{$manufacturer->name}}</option >
            @endforeach
          </select>
        </div>
        <div class="col-md-3 p-2">
          <select name="model_id" class="form-control" id="model">
            <option value="">Select Model</option>
          </select>
        </div>
        <div class="col-md-3 p-2">
          <select name="year" class="form-control" id="">
            <option value="">Select year</option>
          
            @foreach($data as $datas)
              @if(isset($datas->year_of_manufacture))
                <option value="{{$datas->year_of_manufacture}}" @if($datas->year_of_manufacture == $year) selected @endif>{{$datas->year_of_manufacture}} </option>
              @endif
            @endforeach
          </select>
        </div>
        <div class="col-md-3 p-2">
          <select name="color_id" class="form-control" id="">
            <option value="">Select color</option>
            @foreach(\App\ExteriorColor::get() as $color)
              <option value="{{$color->id}}" @if($color->id == $color_id) selected @endif>{{$color->color}}</option>
            @endforeach
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
            <!-- <input type="search" class="form-control" style="flex: 1;" name="search" value=""> -->
            <button style="margin-left: 5px;" class="btn btn-block"> Filter </button>
          </div>
        </div>
      </div>
    </form>


    <!-- <div class="row" style="margin: 0px 20px 0px 30px;">
      @foreach($data as $lp)
      @if(isset($lp->sell) && $lp->sell->is_sell != 1 || !isset($lp->sell->is_sell) )

      <div style="background-color: #fff; padding:20px; margin:20px">
        <div>
          <h4>
            {{$lp->vehicle->manufacture->name??''}}-{{$lp->vehicle->vehicle_model->name??''}}(
            {{$lp->vehicle->chassis_code->name??''}},
            {{$lp->vehicle->displacement_engine->name??''}},
            {{$lp->vehicle->transmission??''}},
            {{$lp->vehicle->drive_system??''}},
            {{$lp->vehicle->fuel_type??''}}
            )-{{$lp->registration_year}}
            &nbsp;
            @if(isset($lp->sell)&& $lp->sell->is_book == 1 )
            <i class="fas fa-check-circle text-success bg-success"></i>
            @endif
          </h4>
        </div>
        <table class="table">
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
      </div>
      @endif
      @endforeach
       
    </div> -->
     <div style="background-color: #f4f4f4; padding:30px 20px; margin-top:10px; margin-bottom: 30px;">
      <div class="table-responsive">
        <table class="table " id="purchase_table" style="width: 100%;">

        </table>
      </div>
    </div>
  </div>
</section>

@stop

@section('javascript')
  <script type="text/javascript">
  var purchase_table = $('#purchase_table').DataTable({
    processing: true,
    serverSide: true,
    ajax: '/vehicle/book',
    columnDefs: [{
      targets: 0,
      orderable: false,
      searchable: true,
    }, ],
    columns: [

      {
        data: 'type',
        name: 'type'
      },
    
    ],
    lengthMenu: [
      [10, 25, 50, -1],
      [10, 25, 50, "All"]
    ],
  });
  $(document).ready(function() {
    $(document).on('change', '#Manufacturer', function(e) {
      var id = $(this).val()
     
        $.ajax({
            method: 'POST',
            url: '/manufacture_model',
            dataType: 'json',
            data: { id:id },
            beforeSend: function (xhr) {
                $('#something').hide()
                // __disable_submit_button('button[type="submit"]');
            },
            success: function (result) {
                if (result.success == true) {
                    //  $('div.unit_modal').modal('hide');
                    $('#model option').remove()
                     $('#model').append(
                        '<option value=""> --Select Model-- </option>'
                      )
                    $.each(result.model, function(index, value) {
                      $('#model').append(
                        '<option value="'+value.id+'">'+value.name+' </option>'
                      )
                    })
                    toastr.success(result.msg);
                } else {
                    toastr.error(result.msg);
                    $('#something').show()
                }
            },
        });
      
    })
  });
</script>
@endsection