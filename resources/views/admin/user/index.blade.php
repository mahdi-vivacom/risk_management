@extends('Admin.layouts.app')
@section('title','User List')
@push('styles')
    <style>
        /*Page Styles*/
    </style>
@endpush
@section('page_pretitle', 'User List')
@section('page_title', 'Users')
@section('content')

    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Users</h3>
            </div>
            <div class="card-body border-bottom py-3">
                <div class="d-flex">
                    <div class="text-muted">
                        Show
                        <div class="mx-2 d-inline-block">
                            <input type="text" class="form-control form-control-sm" value="8" size="3" aria-label="Invoices count">
                        </div>
                        entries
                    </div>
                    <div class="ms-auto text-muted">
                        Search:
                        <div class="ms-2 d-inline-block">
                            <input type="text" class="form-control form-control-sm" aria-label="Search invoice">
                        </div>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table card-table table-vcenter text-nowrap datatable">
                    <thead>
                    <tr>
{{--                        <th class="w-1"><input class="form-check-input m-0 align-middle" type="checkbox" aria-label="Select all invoices"></th>--}}
                        <th class="w-1">No. <!-- Download SVG icon from http://tabler-icons.io/i/chevron-up -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-sm icon-thick" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M6 15l6 -6l6 6" /></svg>
                        </th>
                        <th>User Name</th>
                        <th>User Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th class="">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
{{--                        <td><input class="form-check-input m-0 align-middle" type="checkbox" aria-label="Select invoice"></td>--}}
                        <td><span class="text-muted">0001</span></td>
                        <td><a href="invoice.html" class="text-reset" tabindex="-1">Design Works</a></td>
                        <td>
                            test@gmail.com
                        </td>
                        <td>
                            Admin
                        </td>
                        <td>
                            <span class="badge bg-success me-1"></span> Active
                        </td>
                        <td class="">
                            <a href="#" type="button" class="btn btn-outline-primary btn-sm" onclick="">
                                <i class="ri-edit-box-line"></i>
                            </a>
                            <a href="#" type="button" class="btn btn-outline-success btn-sm" onclick="">
                                <i class="ri-shield-user-line"></i>
                            </a>
                            <a href="#" type="button" class="btn btn-outline-info btn-sm" onclick="">
                                <i class="ri-key-2-fill"></i>
                            </a>
                            <a href="#" type="button" class="btn btn-outline-danger btn-sm" onclick="">
                                <i class="ri-delete-bin-2-fill"></i>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        {{--                        <td><input class="form-check-input m-0 align-middle" type="checkbox" aria-label="Select invoice"></td>--}}
                        <td><span class="text-muted">0001</span></td>
                        <td><a href="invoice.html" class="text-reset" tabindex="-1">Design Works</a></td>
                        <td>
                            test@gmail.com
                        </td>
                        <td>
                            Admin
                        </td>
                        <td>
                            <span class="badge bg-success me-1"></span> Active
                        </td>
                        <td class="">
                            <a href="#" type="button" class="btn btn-outline-primary btn-sm" onclick="">
                                <i class="ri-edit-box-line"></i>
                            </a>
                            <a href="#" type="button" class="btn btn-outline-success btn-sm" onclick="">
                                <i class="ri-shield-user-line"></i>
                            </a>
                            <a href="#" type="button" class="btn btn-outline-info btn-sm" onclick="">
                                <i class="ri-key-2-fill"></i>
                            </a>
                            <a href="#" type="button" class="btn btn-outline-danger btn-sm" onclick="">
                                <i class="ri-delete-bin-2-fill"></i>
                            </a>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="card-footer d-flex align-items-center">
                <p class="m-0 text-muted">Showing <span>1</span> to <span>8</span> of <span>16</span> entries</p>
                <ul class="pagination m-0 ms-auto">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                            <!-- Download SVG icon from http://tabler-icons.io/i/chevron-left -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg>
                            prev
                        </a>
                    </li>
                    <li class="page-item"><a class="page-link" href="#">1</a></li>
                    <li class="page-item active"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">4</a></li>
                    <li class="page-item"><a class="page-link" href="#">5</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">
                            next <!-- Download SVG icon from http://tabler-icons.io/i/chevron-right -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="modal modal-blur fade" id="modal-report" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">New report</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Organization</label>
                                <select class="form-select">
                                    <option value="1" selected>Select Organization</option>
                                    <option value="2">Public</option>
                                    <option value="3">Hidden</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">User Role</label>
                                <select class="form-select">
                                    <option value="1" selected>Select Role</option>
                                    <option value="2">Public</option>
                                    <option value="3">Hidden</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">User name</label>
                                <input type="text" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">User Email</label>
                                <input type="email" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" class="form-control">
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="mb-3">
                                <label class="form-label">Confirm Password</label>
                                <input type="password" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-link link-secondary" data-bs-dismiss="modal">
                        Cancel
                    </a>
                    <a href="#" class="btn btn-primary ms-auto" data-bs-dismiss="modal">
                        <i class="ri-user-add-line"></i>
                        Create User
                    </a>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    @include('Admin.partials.dashboard_scripts')
    <script>
        // Page Scripts
    </script>
@endpush
