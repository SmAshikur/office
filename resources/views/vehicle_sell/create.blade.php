@extends('layouts.app')
@section('title', __( 'lang_v1.all_sales'))

@section('content')
<section>
  <div class="container-fluid" style="">
    <div class="row">
      <form action="{{url('vehicle/sell')}}" method="post">@csrf
        <div class="col-md-8" style="">
          <div class="row" style="background-color: #fff; padding:20px;margin:20px">
            <h4>Customer Information</h4>
            <div>
              <div class="input-group">
                <span class="input-group-addon">
                  <i class="fa fa-user"></i>
                </span>
                <input type="hidden" id="default_customer_id" value="{{ $walk_in_customer['id']}}">
                <input type="hidden" id="default_customer_name" value="{{ $walk_in_customer['name']}}">
                <input type="hidden" id="default_customer_balance" value="{{ $walk_in_customer['balance'] ?? ''}}">
                <input type="hidden" id="default_customer_address" value="{{ $walk_in_customer['shipping_address'] ?? ''}}">
                @if(!empty($walk_in_customer['price_calculation_type']) && $walk_in_customer['price_calculation_type'] == 'selling_price_group')
                <input type="hidden" id="default_selling_price_group" value="{{ $walk_in_customer['selling_price_group_id'] ?? ''}}">
                @endif
                {!! Form::select('contact_id',
                [], null, ['class' => 'form-control mousetrap',
                'id' => 'customer_id', 'placeholder' => 'Enter Customer name / phone', 'required']); !!}
                <span class="input-group-btn">
                  <button type="button" class="btn btn-default bg-white btn-flat add_new_customer" data-name=""><i class="fa fa-plus-circle text-primary fa-lg"></i></button>
                </span>
              </div>
              <small class="text-danger hide contact_due_text"><strong>@lang('account.customer_due'):</strong> <span></span></small>
            </div>
          </div>
          <div class="row" style="background-color: #fff; padding:20px;margin:20px">
            <h4>
              <input type="hidden" name="purchase_id" value="{{$data->id}}">
              {{$data->vehicle->manufacture->name??''}}-{{$data->vehicle->vehicle_model->name??''}}(
              {{$data->vehicle->chassis_code->name??''}},
              {{$data->vehicle->displacement_engine->name??''}},
              {{$data->vehicle->transmission??''}},
              {{$data->vehicle->drive_system??''}},
              {{$data->vehicle->fuel_type??''}}
              )-{{$data->registration_year}}
            </h4>
            <div>
              Vin/Chassis No: {{$data->vehicle->chassis_code->name??''}} &nbsp;
              Engine No: NA &nbsp;
              Code: &nbsp;
              Condition: {{$data->vehicle_condition??''}} &nbsp;
              Reg. No: {{$data->registration_no??''}} &nbsp;
              Color: {{$data->registration_no??''}} &nbsp;
              Auction Grade: 5 &nbsp;
              Mileage: 78000.00 &nbsp;
              Location: Showroom &nbsp;
              <div class="row" style="margin-top: 20px;">
                <div class="col-md-4">
                  <label for="" class="form-label">Vehicle Price</label>
                  <input type="text" class="form-control" value="{{$data->sale_price}}" name="sale_price" readonly id="sell_price">
                </div>
                <div class="col-md-4">
                  <label for="" class="form-label">Discount</label>
                  <input type="number" class="form-control" name="discount" id="sale_discount">
                </div>
                <div class="col-md-4">
                  <label for="" class="form-label">Sell Price</label>
                  <input type="text" class="form-control" readonly name="discounted_price" value="{{$data->sale_price}}" id="dis_sell_price">
                </div>
              </div>
            </div>
          </div>
          <div class="row" style="background-color: #fff; padding:20px;margin:20px">
            <h4>Others Cost</h4>
            <div class="col-md-8">
              <div class="row">
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
            <div class="col-md-12" style="margin-top: 30px;">
              <div class="row">
                <div class="col-md-8">
                  <table class="" id="tr_add_other_cost">

                  </table>
                </div>
                <div class="col-md-3 ">
                  <label>Total of Other Cost</label>
                  <input type="number" readonly class="form-control" name="other_cost" id="total_of_other_cost">
                </div>
              </div>
            </div>
          </div>
          <div class="row" style="background-color: #fff; padding:20px;margin:20px">
            <h4>After sales & Warranty Information</h4>
            <div class="col-md-8">
              <select name="" id="service_warranty" class="form-control">
                @foreach(\App\VehicleService::get() as $cost)
                <option value="{{$cost->price}}">{{$cost->title}}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-3 ">
              <label>Price of Service & Warranty</label>
              <input type="text" readonly class="form-control" name="service_price" id="service_warranty_id">
            </div>
          </div>
        </div>
        <div class="col-md-4" style="">
          <div class="row" style="background-color: #fff; padding:20px;margin:20px">
            <div style="display: flex; justify-content:end">
              <span class="btn btn-primary"> Status:Book</span>
            </div>
            <div class="form-group">
              <label for="" class="form-label">Sale Date</label>
              <input type="date" class="form-control" name="sale_date">
            </div>
            <div class="form-group">
              <label for="" class="form-label">Booked/Sold By</label>
              <select name="book" id="" class="form-control">
                <option value="">--Select one--</option>
              </select>
            </div>
            <div class="form-group">
              <label for="" class="form-label">Part Exchange</label>
              <select name="part_exchange" id="" class="form-control">
                <option value="">--Select one--</option>
              </select>
            </div>
            <div class="form-group">
              <input type="text" readonly id="less_part_Service" class="form-control">
            </div>
          </div>
          <div class="row" style="background-color: #fff; padding:20px;margin:20px">

            <div class="col-md-6" style="margin-bottom: 5px;">Vehicle Sell price</div>
            <div class="col-md-6" style="margin-bottom: 5px;">
              <input type="text" class="form-control" readonly id="dis_sell_price2" value="{{$data->sale_price}}" name="sale_price">
            </div>

            <div class="col-md-6" style="margin-bottom: 5px;">Total of Other Cost</div>
            <div class="col-md-6" style="margin-bottom: 5px;">
              <input type="text" class="form-control" readonly id="total_of_other_cost2" value="0" name="total_of_other_cost">
            </div>

            <div class="col-md-6" style="margin-bottom: 5px;">Service & warranty</div>
            <div class="col-md-6" style="margin-bottom: 5px;">
              <input type="text" class="form-control" readonly id="service_warranty_id2" value="0" name="service_warranty">
            </div>

            <div class="col-md-6" style="margin-bottom: 5px;">Vat</div>
            <div class="col-md-6" style="margin-bottom: 5px;">
              <input type="text" class="form-control" name="vat" id="sell_vat" value="0">
            </div>

            <div class="col-md-6" style="margin-bottom: 5px;">Sub Total</div>
            <div class="col-md-6" style="margin-bottom: 5px;">
              <input type="text" class="form-control" readonly name="sub_total" id="sell_sub_total">
            </div>

            <div class="col-md-6" style="margin-bottom: 5px;">Less Part Exchange</div>
            <div class="col-md-6" style="margin-bottom: 5px;">
              <input type="text" class="form-control" name="less_part_Service" readonly id="less_part_Service2" value="0">
            </div>

            <div class="col-md-6" style="margin-bottom: 10px;">
              <h5>Total to Pay</h5>
            </div>
            <div class="col-md-6" style="margin-bottom: 10px;">
              <input type="text" class="form-control" name="total_to_pay" readonly id="total_to_pay">
            </div>
            <div class="col-md-12" style="margin-bottom: 10px;">
              <label for="" class="form-label">Terms & Conditions</label>
              <select name="" id="terms_id" class="form-control" name="terms">
                <option value="">--Select one--</option>
                @foreach(\App\Terms::get() as $cost)
                  <option value="{{$cost->id}}">{{$cost->title}}</option>
                @endforeach
              </select>
            </div>
            <div class="col-md-4" style="margin-bottom: 10px;"> <input type="checkbox">&nbsp send sms</div>
            <div class="col-md-4" style="margin-bottom: 10px;"> <input type="checkbox">&nbsp send email</div>
            <div class="col-md-4" style="margin-bottom: 10px;">remarks</div>
            <button class="col-md-12 btn btn-success" style="margin-bottom: 5px;" type="submit"> Proceed to Booking</button>
            <button class="col-md-4 btn btn-primary" style="margin-bottom: 5px;"> Print</button>
            <button class="col-md-4 btn btn-warning" style="margin-bottom: 5px;"> Edit</button>
            <button class="col-md-4 btn btn-danger" style="margin-bottom: 5px;"> Cancel</button>
            <button class="col-md-12 btn btn-success"> Recive payment</button>
          </div>

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
<script src="{{ asset('js/pos.js?v=' . $asset_v) }}"></script>
<script src="{{ asset('js/product.js?v=' . $asset_v) }}"></script>
<script src="{{ asset('js/opening_stock.js?v=' . $asset_v) }}"></script>

<!-- Call restaurant module if defined -->
@if(in_array('tables' ,$enabled_modules) || in_array('modifiers' ,$enabled_modules) || in_array('service_staff' ,$enabled_modules))
<script src="{{ asset('js/restaurant.js?v=' . $asset_v) }}"></script>
@endif
<script type="text/javascript">
  $(document).ready(function() {
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
      $('#tr_add_other_cost').append('\
                    <tr>\
                      <td><input type="text" class="form-control" readonly value="' + $cost_name + '"></td>\
                      <td><input type="text" class="form-control other_cost_val" value="' + $other_cost + '"></td>\
                      <td ><i class="fas fa-trash remove_other_cost" style="margin-left: 20px;"></i></td>\
                    </tr>\
       ')
      other_cost_sum()
      sub_total()
    })

    function sub_total() {
      var dis_sell_price2 = parseFloat($('#dis_sell_price2').val())

      var total_of_other_cost2 = parseFloat($('#total_of_other_cost2').val())
      var service_warranty_id2 = parseFloat($('#service_warranty_id2').val())
      var sell_vat = parseFloat($('#sell_vat').val())

      var sub_total = parseFloat(dis_sell_price2 + total_of_other_cost2 + service_warranty_id2 + sell_vat)
      $('#sell_sub_total').val(sub_total)
      var less_part_Service = parseFloat($('#less_part_Service').val())
     // alert(sub_total)
      $('#total_to_pay').val(sub_total-less_part_Service)
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

    function setPreferredPaymentMethodDropdown() {
      var payment_settings = $('#location_id').data('default_payment_accounts');
      payment_settings = payment_settings ? payment_settings : [];
      enabled_payment_types = [];
      for (var key in payment_settings) {
        if (payment_settings[key] && payment_settings[key]['is_enabled']) {
          enabled_payment_types.push(key);
        }
      }
      if (enabled_payment_types.length) {
        $("#prefer_payment_method > option").each(function() {
          if (enabled_payment_types.indexOf($(this).val()) != -1) {
            $(this).removeClass('hide');
          } else {
            $(this).addClass('hide');
          }
        });
      }
    }

    setPreferredPaymentMethodDropdown();

    $('#is_export').on('change', function() {
      if ($(this).is(':checked')) {
        $('div.export_div').show();
      } else {
        $('div.export_div').hide();
      }
    });

    if ($('.payment_types_dropdown').length) {
      $('.payment_types_dropdown').change();
    }

  });
</script>
@endsection