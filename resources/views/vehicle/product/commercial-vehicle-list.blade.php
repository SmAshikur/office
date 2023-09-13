@extends('layouts.app')
@section('title', 'Commercial Vehicle List')
@push('custom-css')
@endpush
@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Commercial Vehicle List<a href="{{ route('vehicle.list.commercial.vehicle') }}"
                class="btn btn-primary pull-right">Add Commercial Vehicle</a></h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <livewire:vehicle.commercial-vehicle-list />
    </section>
    <!-- /.content -->

@endsection
