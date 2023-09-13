@extends('layouts.app')
@section('title',
    'Add BIKE & 3
    Wheelers')
    @push('custom-css')
    @endpush
@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Add BIKE & 3
            Wheelers <a href="{{ route('vehicle.list.bike') }}" class="btn btn-primary pull-right">BIKE & 3
                Wheelers List</a></h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <livewire:vehicle.add-bike-component />
    </section>
    <!-- /.content -->

@endsection

@push('custom-js')
    <script></script>
@endpush
