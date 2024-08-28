@extends('backend.layout.master')
@section('custom_css')
@endsection
@section('content')
    <section class="section">
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body pt-3">
                        <form class="row g-3" action="{{ route('documents.update', [ 'document' => $document->id ] ) }}" method="post" novalidate>
                            @csrf
                            @method('patch')
                            <div class="col-md-6">
                                <label for="title" class="form-label">@lang('admin_fields.document_title')</label>
                                    <input id="title" type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $document->title) }}" placeholder="@lang('admin_fields.document_title')" autocomplete="title">
                                    @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                            </div>
                            
                            <div class="col-md-6">
                                <label for="validity" class="form-label">@lang ( 'admin_fields.document_validity' )</label>
                                <select name="validity" id="validity" class="form-select @error( 'validity' ) is-invalid @enderror">
                                    <option value="1" {{ $document->validity == 1 ? 'selected' : '' }}>@lang ( 'admin_fields.yes' )</option>
                                    <option value="0" {{ $document->validity == 0 ? 'selected' : '' }}>@lang ( 'admin_fields.no' )</option>
                                </select>
                                @error('validity')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="mandatory" class="form-label">@lang ( 'admin_fields.document_mandatory' )</label>
                                <select name="mandatory" id="mandatory" class="form-select @error( 'mandatory' ) is-invalid @enderror">
                                    <option value="1" {{ $document->mandatory == 1 ? 'selected' : '' }}>@lang ( 'admin_fields.yes' )</option>
                                    <option value="0" {{ $document->mandatory == 0 ? 'selected' : '' }}>@lang ( 'admin_fields.no' )</option>
                                </select>
                                @error('mandatory')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="application" class="form-label">@lang ( 'admin_fields.document_for' )</label>
                                <select name="application" id="application" class="form-select @error( 'application' ) is-invalid @enderror">
                                    <option value="1" {{ $document->application == 1 ? 'selected' : '' }}>@lang ( 'admin_fields.client_app' )</option>
                                    <option value="2" {{ $document->application == 2 ? 'selected' : '' }}>@lang ( 'admin_fields.professional_app' )</option>
                                </select>
                                @error('application')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label for="status" class="form-label">@lang ( 'admin_fields.status' )</label>
                                <select name="status" id="status" class="form-select select2 @error( 'status' ) is-invalid @enderror">
                                    <option value="1" {{ $document->status == 1 ? 'selected' : '' }}>@lang ( 'admin_fields.active' )</option>
                                    <option value="0" {{ $document->status == 0 ? 'selected' : '' }}>@lang ( 'admin_fields.inactive' )</option>
                                </select>
                                @error( 'status' )
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            
                            <div class="text-center">
                                <button type="submit" class="btn btn-success btn-sm">@lang ( 'admin_fields.update' )</button>
                                <a href="{{ route ( 'documents.index' ) }}" type="button" class="btn btn-outline-secondary btn-sm">@lang ( 'admin_fields.back' )</a>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section( 'custom_js' )
@endsection
