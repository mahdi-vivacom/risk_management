@extends('backend.layout.master')
@section('custom_css')
@endsection
@section('content')
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body pt-3">
                        <form class="row g-3"
                            action="{{ route($route . '.update', ['threat_scenario' => $threatScenario->id]) }}"
                            method="post" novalidate>
                            @csrf
                            @method('patch')

                            <div class="col-md-6">
                                <label for="type" class="form-label">@lang('admin_fields.threat_to')</label>
                                <select name="type" id="type"
                                    class="form-select @error('type') is-invalid @enderror">
                                    <option value="Threat to movement"
                                        {{ $threatScenario->type == 'Threat to movement' ? 'selected' : '' }}>Threat to
                                        movement</option>
                                    <option value="Threat to work site"
                                        {{ $threatScenario->type == 'Threat to work site' ? 'selected' : '' }}>Threat to
                                        work site</option>
                                    <option value="Threat to local population"
                                        {{ $threatScenario->type == 'Threat to local population' ? 'selected' : '' }}>Threat
                                        to local population</option>
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="name" class="form-label">@lang('admin_fields.threat_type')</label>
                                <input id="name" type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $threatScenario->name) }}" placeholder="@lang('admin_fields.threat_type')"
                                    autocomplete="name">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-12">
                                <label for="definition" class="form-label">@lang('admin_fields.definition')</label>
                                <textarea id="definition" name="definition" class="form-control @error('definition') is-invalid @enderror"
                                    rows="5">{{ old('definition', $threatScenario->definition) }}</textarea>
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
