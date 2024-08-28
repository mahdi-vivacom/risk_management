@extends('Admin.layouts.app')
@section('title','Blank')
@push('styles')
    <style>
        /*Page Styles*/
    </style>
@endpush
@section('page_pretitle', 'Blank Page')
@section('page_title', 'Blank')
@section('content')


    {{--Page Content--}}


@endsection
@push('scripts')
    @include('Admin.partials.dashboard_scripts')
    <script>
        // Page Scripts
    </script>
@endpush
