@extends('layouts.app')
@section('title', __( 'lang_v1.all_sales'))
@section('content')

<section style="padding: 20px;">
  <div class="container">
    <h2>Genarate New Quatation</h2>
    <div style="display: flex; justify-content:space-between">
      <div class="">
        <label for="">Type </label>&nbsp;&nbsp;&nbsp;
        <label for="">Bank</label>&nbsp;
        <input type="radio" name="type" value="bank" checked>&nbsp;
        <label for="">Personal</label>&nbsp;
        <input type="radio" name="type" value="personal">&nbsp;
      </div>
      <div>
        <label for="">Date</label>&nbsp;
        <input type="date">
      </div>
    </div>
    <div class="row">
      <form action="">
        <div class="col-md-12">
          <h3>Customer Information</h3>
          <div class="row type_bank" id="type_bank" style="margin: 20px 0;">
            <div class="col-md-4">
              <label for="">Bank Name</label>
              <select name="" id="" class="form-control">
                <option value="">Agranee Bank</option>
                <option value="">Brak Bank</option>
                <option value="">City Bank</option>
              </select>
            </div>
            <div class="col-md-4">
              <label for="">Bank Amount</label>
              <input type="number" class="form-control">
            </div>
          </div>
          <div class="row">
            <div class="col-md-4" style="margin-bottom:10px">
              <input type="text" class="form-control col-md-4" placeholder="Customer Name">
            </div>
             <div class="col-md-4" style="margin-bottom:10px">
              <input type="text" class="form-control col-md-4" placeholder="Customer Designation">
            </div>
             <div class="col-md-4" style="margin-bottom:10px">
              <input type="text" class="form-control col-md-4" placeholder="Customer Organization">
            </div>
             <div class="col-md-4">
              <input type="text" class="form-control col-md-4" placeholder="Customer Address">
            </div>
             <div class="col-md-4">
              <input type="text" class="form-control col-md-4" placeholder="Customer Contact">
            </div>
             <div class="col-md-4">
              <input type="text" class="form-control col-md-4" placeholder="Customer Email">
            </div>
            
          </div>
        </div>
        <div class="col-md-12">
          <h3>Vehicle Information</h3>
          <div class="row">
            <div class="col-md-6" style="margin-bottom:10px">
              <div class="input-group ">
              <span class="input-group-addon">
                <i class="fa fa-car"></i>
              </span>
              {!! Form::select('vehicle_id',
              [], null, ['class' => 'form-control mousetrap',
              'id' => 'vehicle_id', 'placeholder' => 'Enter Vehicle', 'required']); !!}
            </div>
            </div>
            <div class="col-md-2" style="margin-bottom:10px">
              <select name="" id="" class="form-control">

              </select>
            </div>
            <div class="col-md-2" style="margin-bottom:10px">
              <input type="text" class="form-control col-md-4" placeholder="Vin/chassis No">
            </div>
            <div class="col-md-2" style="margin-bottom:10px">
              <input type="text" class="form-control col-md-4" placeholder="Engine Number">
            </div>
            <div class="col-md-2">
              <select name="" id="" class="form-control">

              </select>
            </div>
            <div class="col-md-2">
              <select name="" id="" class="form-control">

              </select>
            </div>
            <div class="col-md-2">
              <select name="" id="" class="form-control">

              </select>
            </div>
            <div class="col-md-2">
              <input type="text" class="form-control col-md-4" placeholder="Vin/chassis No">
            </div>
            <div class="col-md-2">
              <input type="text" class="form-control col-md-4" placeholder="Vin/chassis No">
            </div>
            <div class="col-md-2">
              <input type="text" class="form-control col-md-4" placeholder="Vin/chassis No">
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <h3>Pricing Information</h3>
          <div class="row">
            <div class="col-md-5">
              <label for="">Unit Price</label>
              <input type="text" class="form-control">
              <div class="row" style="margin-top: 20px;">
                <div class="col-md-4">
                  <select name="" id="cost_name" class="form-control">
                    <option value="">--Select one--</option>
                    @foreach(\App\CostType::where('related_to','sell')->get() as $cost)
                    <option value="{{$cost->name}}">{{$cost->name}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-md-4">
                  <input type="number" class="form-control" id="other_cost">
                </div>
                <div class="col-md-4">
                  <button type="button" class="btn btn-success btn-sm" id="add_other_cost">Add</button>
                </div>
              </div>
            </div>
            <div class="col-md-3" style="padding-top:20px">
              <table class="" id="tr_add_other_cost">

              </table>
            </div>
            <div class="col-md-4">
              <div style="display: flex;  flex-direction:row ; justify-content:space-between; margin-bottom:5px; margin-top:20px">
                <label>Unit Price</label>
                <input type="number" readonly class="form-control" name="other_cost" id="total_of_other_cost" value="0" style="width: 60%;">
              </div>
              <div style="display: flex;  flex-direction:row; justify-content:space-between; margin-bottom:5px">
                <label>Other Cost</label>
                <input type="number" readonly class="form-control" name="other_cost" id="total_of_other_cost" value="0" style="width: 60%;">
              </div>
              <div style="display: flex;  flex-direction:row; justify-content:space-between; margin-bottom:5px">
                <label>Final Price</label>
                <input type="number" readonly class="form-control" name="other_cost" id="total_of_other_cost" value="0" style="width: 60%;">
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="row">
            <div class="col-md-6">
              <h3>Features</h3>
              <input type="checkbox" name="select_all" style="margin-bottom:20px"> Select All
              <div style="margin-bottom:20px">
                <input type="checkbox" name="feature[]" class="" value="Air Conditioning"> Air Conditioning &nbsp;&nbsp;&nbsp;
                <input type="checkbox" name="feature[]" class="" value="AirBags"> AirBags &nbsp;&nbsp;&nbsp;
                <input type="checkbox" name="feature[]" class="" value="Rear View Camera"> Rear View Camera &nbsp;&nbsp;&nbsp;
              </div>
              <div style="margin-bottom:20px">
                <input type="checkbox" name="feature[]" class="" value="Power Steering"> Power Steering &nbsp;&nbsp;&nbsp;
                <input type="checkbox" name="feature[]" class="" value="ABS Brakes"> ABS Brakes &nbsp;&nbsp;&nbsp;
                <input type="checkbox" name="feature[]" class="" value="Alloy Wheel"> Alloy Wheel &nbsp;&nbsp;&nbsp;
              </div>
              <div style="margin-bottom:20px">
                <input type="checkbox" name="feature[]" class="" value="Power window"> Power window &nbsp;&nbsp;&nbsp;
                <input type="checkbox" name="feature[]" class="" value="Multimedia Player"> Multimedia Player &nbsp;&nbsp;&nbsp;
              </div>
            </div>
            <div class="col-md-6">
              <h3>Special Features</h3>
              <textarea name="" id="" cols="60" rows="4" placeholder="Specail feature"></textarea>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <h3>Terms & Conditions</h3>
          <textarea name="" id="" cols="120" rows="3" placeholder="Specail feature"></textarea>
        </div>
        <div class="col-md-12">
          <div class="row">
            <div class="col-md-6">
              <label for=""></label>
              <input type="text" class="form-control">
            </div>
            <div class="col-md-6">
              <label for=""></label>
              <input type="text" class="form-control">
            </div>
          </div>
        </div>
        <div class="col-md-12" style="margin-top:20px; display:flex; justify-content:center">
          <button class="btn btn-info"><i class="fas fa-list"></i> List</button>
          <button class="btn btn-info"><i class="fas fa-print"></i> Print Quataion </button>
          <button class="btn btn-info"><i class="fas fa-mail"></i>Email</button>
          <button class="btn btn-danger"><i class="fas fa-trash"></i> Remove</button>
          <button class="btn btn-danger"><i class="fas fa-close"></i> Rejected</button>
          <button class="btn btn-success"> Accepted</button>
          <button class="btn btn-success"> Update</button>
          <button class="btn "> Print</button>
        </div>
      </form>
    </div>
  </div>
</section>
<div class="modal fade contact_modal" tabindex="-1" role="dialog" aria-labelledby="gridSystemModalLabel">
  @include('contact.create', ['quick_add' => true])
</div>

@stop
@section('javascript')
<style>
    /* Style for larger checkboxes */
    .checkbox-container {
        display: flex;
        align-items: center;
        margin-bottom: 10px; /* Add margin to the container for spacing below */
    }
    input[type="checkbox"] {
        transform: scale(1.3); /* You can adjust the scale factor as needed */
        margin-right: 10px; 
    }
</style>
<script src="{{ asset('js/pos.js?v=' . $asset_v) }}"></script>
<script src="{{ asset('js/product.js?v=' . $asset_v) }}"></script>
<script src="{{ asset('js/opening_stock.js?v=' . $asset_v) }}"></script>

<!-- Call restaurant module if defined -->
@if(in_array('tables' ,$enabled_modules) || in_array('modifiers' ,$enabled_modules) || in_array('service_staff' ,$enabled_modules))
<script src="{{ asset('js/restaurant.js?v=' . $asset_v) }}"></script>
@endif
<script type="text/javascript">
  $('#terms').val($('#terms option:eq(1)').val());
  $(document).ready(function() {
    $(document).on('click', '.confirmSubmit', function(e) {
      if (confirm("Are you sure you want to submit this form?")) {
        $("#myForm").submit();
      }
    })
    $('#status').change(function() {
      if ($(this).val() == 'final') {
        $('#payment_rows_div').removeClass('hide');
      } else {
        $('#payment_rows_div').addClass('hide');
      }
    });
    $('.paid_on').datetimepicker({
      format: moment_date_format + ' ' + moment_time_format,
      ignoreReadonly: true,
    });

    $('#shipping_documents').fileinput({
      showUpload: false,
      showPreview: false,
      browseLabel: LANG.file_browse_label,
      removeLabel: LANG.remove,
    });

    $(document).on('keyup change', '#sale_discount', function(e) {
      var sell_price = $('#sell_price').val()
      var discount = $(this).val()
      var result = sell_price - discount
      $('#dis_sell_price').val(result)
      $('#dis_sell_price2').val(result)
      // alert(sell_price)
    })
    $(document).on('change', '#service_warranty', function(e) {
      var value = $(this).val()
      $('#service_warranty_id').val(value)
      $('#service_warranty_id2').val(value)
      sub_total()
    })
    $(document).on('click', '#add_other_cost', function(e) {
      $other_cost = $('#other_cost').val()
      $cost_name = $('#cost_name').val()
     // alert($other_cost)
      if ($other_cost.length > 0) {
        $('#tr_add_other_cost').append('\
                    <tr>\
                      <td><input type="text" class="form-control" readonly value="' + $cost_name + '"></td>\
                      <td><input type="text" class="form-control other_cost_val" value="' + $other_cost + '"></td>\
                      <td ><i class="fas fa-trash remove_other_cost" style="margin-left: 20px;"></i></td>\
                    </tr>\
       ')



        other_cost_sum()
        sub_total()
        $('#other_cost').val('')
        $('#cost_name').val('')
      }
    })

    function sub_total() {
      var dis_sell_price2 = parseFloat($('#dis_sell_price2').val())

      var total_of_other_cost2 = parseFloat($('#total_of_other_cost2').val())
      var service_warranty_id2 = parseFloat($('#service_warranty_id2').val())
      var sell_vat = parseFloat($('#sell_vat').val())

      var sub_total = parseFloat(dis_sell_price2 + total_of_other_cost2 + service_warranty_id2 + sell_vat)
      $('#sell_sub_total').val(sub_total)
      var less_part_Service = parseFloat($('#less_part_Service').val())
      //alert(less_part_Service)
      $('#total_to_pay').val(sub_total - less_part_Service)
    }

    function other_cost_sum() {
      var sum = 0;
      var elements = $('.other_cost_val');
      elements.each(function() {
        var elementValue = parseFloat($(this).val());
        if (!isNaN(elementValue)) {
          sum += elementValue;
        }
        $('#total_of_other_cost').val(sum)
        $('#total_of_other_cost2').val(sum)
        // alert(sum)
      });

    }
    $(document).on('click', '.remove_other_cost', function(e) {

      var currentRow = $(this).closest('tr');
      currentRow.remove()
      if ($('#tr_add_other_cost tr').length === 0) {
        $('#total_of_other_cost').val('0')
        $('#total_of_other_cost2').val('0')
      }
      other_cost_sum()
      sub_total()
    })

    $(document).on('change', '#prefer_payment_method', function(e) {
      var default_accounts = $('select#select_location_id').length ?
        $('select#select_location_id')
        .find(':selected')
        .data('default_payment_accounts') : $('#location_id').data('default_payment_accounts');
      var payment_type = $(this).val();
      if (payment_type) {
        var default_account = default_accounts && default_accounts[payment_type]['account'] ?
          default_accounts[payment_type]['account'] : '';
        var account_dropdown = $('select#prefer_payment_account');
        if (account_dropdown.length && default_accounts) {
          account_dropdown.val(default_account);
          account_dropdown.change();
        }
      }
    });

    /// here we go

     $(document).on('change', 'input[type="radio"][name="type"]', function(e) {
        value = $(this).val()
        if(value== 'bank'){
          $('.type_bank').show()
          $('.type_personal').hide()
        }else{
          $('.type_bank').hide()
          $('.type_personal').show()
        }
     })
    // $(document).on('change', 'input[type="checkbox"][name="select_all"]', function(e) {
           
    //  })
     var checkboxes = $('input[type="checkbox"][name="feature[]"]');
    
    // Select All checkbox
    var selectAllCheckbox = $('input[type="checkbox"][name="select_all"]');
    
    // Add a change event listener to the "Select All" checkbox
    selectAllCheckbox.change(function() {
        var isChecked = $(this).is(':checked');
        checkboxes.prop('checked', isChecked);
    });
     
   

  });
</script>
<script>


</script>
@endsection