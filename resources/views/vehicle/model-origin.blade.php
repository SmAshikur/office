@extends('layouts.app')
@section('title', 'Model Origin')
@push('custom-css')
@endpush
@section('content')

    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Model Origin</h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <livewire:vehicle.model-origin-component />

    </section>
    <!-- /.content -->

@endsection

@push('custom-js')
    <script></script>
@endpush
