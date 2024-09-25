@extends('backend.layout.master')
@section('custom_css')
@endsection
@section('content')
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body pt-3">
                        <form action="{{ route('scrape-targets.update', ['scrape_target' => $scrapeTarget->id]) }}"
                            method="post" class="row g-3" novalidate>
                            @csrf
                            @method('patch')

                            <div class="col-md-6">
                                <label for="url" class="form-label">@lang('admin_fields.url')</label>
                                <input id="url" type="url" name="url"
                                    class="form-control @error('url') is-invalid @enderror"
                                    value="{{ old('url', $scrapeTarget->url) }}" placeholder="@lang('admin_fields.url')"
                                    autocomplete="url">
                                @error('url')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="area_id" class="form-label">@lang ( 'admin_fields.area' )</label>
                                <select name="area_id" id="area_id"
                                    class="form-select select2 @error('area_id') is-invalid @enderror">
                                    <option value="">@lang ( 'admin_fields.select' )</option>
                                    @foreach ($areas as $area)
                                        <option value="{{ $area->id }}"
                                            {{ old('area_id', $scrapeTarget->area_id) == $area->id ? 'selected' : '' }}>
                                            {{ $area->name }}</option>
                                    @endforeach
                                </select>
                                @error('area_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="keywords" class="form-label">@lang ( 'admin_fields.keywords' )</label>
                                <textarea name="keywords" id="keywords" class="form-select @error('keywords') is-invalid @enderror">{{ $scrapeTarget->keywords }}</textarea>
                                @error('keywords')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="status" class="form-label">@lang ( 'admin_fields.status' )</label>
                                <select name="status" id="status"
                                    class="form-select @error('status') is-invalid @enderror">
                                    <option value="1" {{ $scrapeTarget->status == 1 ? 'selected' : '' }}>@lang (
                                        'admin_fields.active'
                                        )</option>
                                    <option value="0" {{ $scrapeTarget->status == 0 ? 'selected' : '' }}>@lang (
                                        'admin_fields.inactive' )</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="text-center">
                                <button type="submit" class="btn btn-success btn-sm">@lang ( 'admin_fields.update'
                                    )</button>
                                <a href="{{ route('scrape-targets.index') }}" type="button"
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
