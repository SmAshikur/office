<?php

namespace App\Http\Controllers;

use App\BankQuatation;
use App\CustomerGroup;
use Illuminate\Http\Request;

class BankQuatationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $business_id = request()->session()->get('user.business_id');
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
        return view('bank_quatation.create',compact('types','customer_groups'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\BankQuatation  $bankQuatation
     * @return \Illuminate\Http\Response
     */
    public function show(BankQuatation $bankQuatation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\BankQuatation  $bankQuatation
     * @return \Illuminate\Http\Response
     */
    public function edit(BankQuatation $bankQuatation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\BankQuatation  $bankQuatation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BankQuatation $bankQuatation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\BankQuatation  $bankQuatation
     * @return \Illuminate\Http\Response
     */
    public function destroy(BankQuatation $bankQuatation)
    {
        //
    }
}
