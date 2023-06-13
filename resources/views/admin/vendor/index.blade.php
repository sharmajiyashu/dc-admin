
@extends('admin.layouts.app')

@section('content')

<style>
    .Active{
        color: green;
        font-weight: 900;
    }
    .Inactive{
        color: red;
        font-weight: 900;
    }
</style>

 <!-- BEGIN: Content-->
<!-- BEGIN: Content-->
<div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
            <div class="content-header row">
                <div class="content-header-left col-md-9 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="col-12">
                            <h2 class="content-header-title float-start mb-0">Vendor</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="{{ route('vendors.index') }}">Vendors</a>
                                    </li>
                                    <li class="breadcrumb-item active">List
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Ajax Sourced Server-side -->
                <section id="ajax-datatable">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header border-bottom">
                                    <h4 class="card-title">List</h4>
                                    {{-- <a href="{{route('vendors.create')}}" class=" btn btn-info btn-gradient round  ">Add Category</a> --}}
                                </div>
                                <div class="card-datatable">
                                    <table class="datatables-ajax table table-responsive">
                                        <thead>
                                            <tr>
                                                <th>Sr.no</th>
                                                <th>Name</th>
                                                <th>Mobile</th>
                                                <th>Gender</th>
                                                <th>State</th>
                                                <th>City</th>
                                                <th>Created Date</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php  $i=1; @endphp
                                            @foreach($vendors as $key => $val)
                                            <tr>
                                                <th scope="row">{{ $i }}</th>
                                                <td>
                                                    {{-- <strong>{{ $val->name }}</strong> --}}
                                                    <a href="#" data-bs-toggle="modal"
                                                            data-bs-target="#order_detail_{{ $val->id }}"><strong>{{ $val->name }}</strong></a>

                                                    <div class="modal fade text-start"
                                                            id="order_detail_{{ $val->id }}" tabindex="-1"
                                                            aria-labelledby="myModalLabel17" aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h4 class="modal-title" id="myModalLabel17">
                                                                          Vendor Detail</h4>

                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body" style="    font-size: 14px;">
                                                                
                                                                        <div class="info-container">
                                                                            <ul class="list-unstyled">
                                                                                <li class="mb-75">
                                                                                    <span class="fw-bolder me-25">Name:</span>
                                                                                    <span>{{ $val->name }}</span>
                                                                                </li>
                                                                                <li class="mb-75">
                                                                                    <span class="fw-bolder me-25">Mobile:</span>
                                                                                    <span>{{ $val->mobile }}</span>
                                                                                </li>
                                                                                <li class="mb-75">
                                                                                    <span class="fw-bolder me-25">Gender:</span>
                                                                                    <span>{{ $val->gender }}</span>
                                                                                </li>
                                                                                <li class="mb-75">
                                                                                    <span class="fw-bolder me-25">DOB:</span>
                                                                                    <span>{{ $val->dob }}</span>
                                                                                </li>
                                                                                <li class="mb-75">
                                                                                    <span class="fw-bolder me-25">State:</span>
                                                                                    <span>{{ $val->state }}</span>
                                                                                </li>
                                                                                <li class="mb-75">
                                                                                    <span class="fw-bolder me-25">City:</span>
                                                                                    <span>{{ $val->city }}</span>
                                                                                </li>
                                                                                <li class="mb-75">
                                                                                    <span class="fw-bolder me-25">Address:</span>
                                                                                    <span>{{ $val->address }}</span>
                                                                                </li>
                                                                            </ul>
                                                                        </div>

                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                
                                                
                                                
                                                </td>
                                                <td>{{ $val->mobile }}</td>
                                                <td>{{ $val->gender }}</td>
                                                <td>{{ $val->state }}</td>
                                                <td>{{ $val->city }}</td>
                                                <td>{{ date('d-M-y H:i:s',strtotime($val->created_at)) }}</td>
                                            </tr>
                                            @php $i++; @endphp
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!--/ Ajax Sourced Server-side -->

                

            </div>
        </div>
    </div>
    <!-- END: Content-->
    <!-- END: Content-->

@endsection