@extends('layouts.app')
@section('title', 'Passenger Vehicle List')
@push('custom-css')
@endpush
@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Passenger Vehicle List<a href="{{ route('vehicle.add.passenger.vehicle') }}"
                class="btn btn-primary pull-right">Add Passenger Vehicle</a></h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <livewire:vehicle.passenger-vehicle-list />
    </section>
    <!-- /.content -->

@endsection
