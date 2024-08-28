@extends('backend.layout.master')
@section('content')
    <section class="section">
        <div class="row">
            <div class="col-xl-12">
                <div class="card">
                    <div class="card-body table-responsive py-3">
                        {{ $dataTable->table() }}
{{--                        {!! $dataTable->table(['class' => 'table table-bordered']) !!}--}}
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('custom_js')
{{--    {{ $dataTable->scripts(attributes: ['type' => 'module']) }}--}}
{!! $dataTable->scripts() !!}
@endsection
