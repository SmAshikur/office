@extends('layouts.app')
@section('title', __( 'lang_v1.all_sales'))

@section('content')
<section>
  <div class="container">
    <div>
      <h3>Pending Delivery List</h3>
    </div>
    <form action="{{url('vehicle/sell')}}" method="get">
      <div class="row m-10" style="margin-top: 20px;">
        <div class="col-md-3 p-2">
          <label for="">Month and year</label>
          <input type="date" class="form-control">
        </div>
        <div class="col-md-3 p-2">
          <label for="">Sales Status</label>
          <select name="" class="form-control" id="">
            <option value="">Sales satus</option>

          </select>
        </div>
        <div class="col-md-3 p-2">
          <label for="">Select Manufacture</label>
          <select name="manufacture_id" class="form-control" id="Manufacturer">
            <option value="">Select Manufacturer</option>
            @foreach(\App\VehicleManufacture::get() as $manufacturer)
            <option value="{{$manufacturer->id}}" @if($manufacturer->id == $manufacture_id) selected @endif>{{$manufacturer->name}}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3 p-2">
          <label for=""> Model</label>
          <select name="model_id" class="form-control" id="model">
            <option value="">Select Model</option>
          </select>
        </div>
        <div class="col-md-12" style="height: 10px;"></div>
        <div class="col-md-3">
          <label for=""> Fuel Type</label>
          <select name="condition" class="form-control" id="">
            <option value="">Select Condition</option>
            <option value="new">Brand new</option>
            <option value="recondition">Recondition</option>
            <option value="used">Used</option>
          </select>
        </div>
        <div class="col-md-3">
          <label for=""> Chassis Code</label>
          <select name="" class="form-control" id="">
            <option value="">Select status</option>
            <option value="recondition">Pre order </option>
            <option value="used">Booked </option>
          </select>
        </div>
        <div class="col-md-3">
          <label for="">Dalivery Status</label>
          <select name="" class="form-control" id="">
            <option value="">Select location</option>
            <option value="recondition">Showroom </option>
            <option value="used">Partner Showroom </option>
          </select>
        </div>
        <div class="col-md-3" style="margin-top: 25px;">
          <div style="display: flex; flex-direction: row;">
            <!-- <input type="search" class="form-control" style="flex: 1;" name="search" value=""> -->
            <button style="margin-left: 5px;" class="btn btn-block"> Filter </button>
          </div>
        </div>
      </div>
    </form>

    <div style="background-color: #f4f4f4; padding:30px 20px; margin-top:10px; margin-bottom: 30px;">
      <div class="table-responsive">
        <table class="table table-bordered table-striped" id="sell_table" style="width: 100%;">
          <thead>
            <tr>
              <th>ID</th>
              <th>Date</th>
              <th>Vehicle</th>
              <th>VinChassisNO <br> / Reg No</th>
              <th>Customer</th>
              <th>Sold By</th>
              <th>Sales Price</th>
              <th>Sale Status</th>
              <th>Delivery Status</th>
              <th>Action</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</section>
@stop

@section('javascript')
<script type="text/javascript">
  var sell_table = $('#sell_table').DataTable({
    processing: true,
    serverSide: true,
    ajax: '/vehicle/sell',
    columnDefs: [{
      targets: 9,
      orderable: false,
      searchable: true,
    }, ],
    columns: [

      {
        data: 'id',
        name: 'id'
      },
      {
        data: 'sell_date',
        name: 'sell_date'
      },
      {
        data: 'name',
        name: 'name'
      },
      {
        data: 'chassis_id',
        name: 'chassis_id'
      },
      {
        data: 'customer_id',
        name: 'customer_id'
      },
      {
        data: 'sold_by',
        name: 'sold_by'
      },
      {
        data: 'total_to_pay',
        name: 'total_to_pay'
      },
      {
        data: 'is_sell',
        name: 'is_sell'
      },
      {
        data: 'is_delivered',
        name: 'is_delivered'
      },

      {
        data: 'action',
        name: 'action'
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
        data: {
          id: id
        },
        beforeSend: function(xhr) {
          $('#something').hide()
          // __disable_submit_button('button[type="submit"]');
        },
        success: function(result) {
          if (result.success == true) {
            //  $('div.unit_modal').modal('hide');
            $('#model option').remove()
            $('#model').append(
              '<option value=""> --Select Model-- </option>'
            )
            $.each(result.model, function(index, value) {
              $('#model').append(
                '<option value="' + value.id + '">' + value.name + ' </option>'
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