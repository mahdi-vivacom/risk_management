@extends('backend.layout.master')
@section('custom_css')
@endsection
@section('content')
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body pt-3">
                        <form class="row g-3" action="{{ route($route . '.store') }}" method="post" novalidate>
                            @csrf
                            <input type="hidden" name="type" value="{{ $type }}" />

                            <div class="col-md-6">
                                <label for="score" class="form-label">@lang('admin_fields.score')</label>
                                <input id="score" type="number" name="score"
                                    class="form-control @error('score') is-invalid @enderror" value="{{ old('score') }}"
                                    placeholder="@lang('admin_fields.score')" autocomplete="score">
                                @error('score')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="name" class="form-label">{{ $type }}</label>
                                <input id="name" type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                    placeholder="{{ $type }}" autocomplete="name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="definition" class="form-label">@lang('admin_fields.definition')</label>
                                <textarea id="definition" name="definition" class="form-control @error('definition') is-invalid @enderror">{{ old('definition') }}</textarea>
                                @error('definition')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-success btn-sm">@lang ( 'admin_fields.save' )</button>
                                <button type="reset" class="btn btn-outline-warning btn-sm">@lang ( 'admin_fields.clear'
                                    )</button>
                                <a href="{{ route($route . '.index') }}" type="button"
                                    class="btn btn-outline-secondary btn-sm">@lang ( 'admin_fields.back' )</a>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('custom_js')
@endsection
