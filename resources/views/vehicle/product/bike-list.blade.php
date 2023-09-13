@extends('layouts.app')
@section('title',
    'BIKE & 3
    Wheelers List')
    @push('custom-css')
    @endpush
@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>BIKE & 3
            Wheelers List<a href="{{ route('vehicle.add.bike') }}" class="btn btn-primary pull-right">Add BIKE & 3
                Wheelers</a>
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <livewire:vehicle.bike-list-component />
    </section>
    <!-- /.content -->

@endsection
