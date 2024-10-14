@extends('backend.layout.master')
@section('custom_css')
@endsection
@section('content')
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body pt-3">
                        <form class="row g-3" action="{{ route($route . '.update', ['risk_level' => $riskLevel->id]) }}"
                            method="post" novalidate>
                            @csrf
                            @method('patch')
                            <div class="col-md-6">
                                <label for="level" class="form-label">@lang('admin_fields.risk_level')</label>
                                <input id="level" type="text" name="level"
                                    class="form-control @error('level') is-invalid @enderror"
                                    value="{{ old('level', $riskLevel->level) }}" placeholder="@lang('admin_fields.risk_level')"
                                    autocomplete="level">
                                @error('level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="color" class="form-label">@lang('admin_fields.color')</label>
                                <input id="color" type="text" name="color"
                                    class="form-control @error('color') is-invalid @enderror"
                                    value="{{ old('color', $riskLevel->color) }}" placeholder="@lang('admin_fields.color')"
                                    autocomplete="color">
                                @error('color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="score_min" class="form-label">@lang('admin_fields.score_min')</label>
                                <input id="score_min" type="number" name="score_min"
                                    class="form-control @error('score_min') is-invalid @enderror"
                                    value="{{ old('score_min', $riskLevel->score_min) }}" placeholder="@lang('admin_fields.score_min')"
                                    autocomplete="score_min">
                                @error('score_min')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="score_max" class="form-label">@lang('admin_fields.score_max')</label>
                                <input id="score_max" type="number" name="score_max"
                                    class="form-control @error('score_max') is-invalid @enderror"
                                    value="{{ old('score_max', $riskLevel->score_max) }}" placeholder="@lang('admin_fields.score_max')"
                                    autocomplete="score_max">
                                @error('score_max')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="action" class="form-label">@lang('admin_fields.action_required')</label>
                                <textarea id="action" type="text" name="action" class="form-control @error('action') is-invalid @enderror">{{ old('action', $riskLevel->action) }}</textarea>
                                @error('action')
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
