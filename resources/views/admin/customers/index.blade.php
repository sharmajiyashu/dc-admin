
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
                            <h2 class="content-header-title float-start mb-0">Customer</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">Customers</a>
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
                                    {{-- <a href="{{route('customers.create')}}" class=" btn btn-info btn-gradient round  ">Add Category</a> --}}
                                </div>
                                <div class="card-datatable">
                                    <table class="datatables-ajax table table-responsive datatable_data">
                                        <thead>
                                            <tr>
                                                <th>Sr.no</th>
                                                <th>Image</th>
                                                <th>Name</th>
                                                <th>Store Name</th>
                                                <th>Mobile</th>
                                                <th>Gender</th>
                                                <th>State</th>
                                                <th>City</th>
                                                <th>Created Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php  $i=1; @endphp
                                            @foreach($customers as $key => $val)
                                            <tr>
                                                <th scope="row">{{ $i }}</th>
                                                <td><img src="{{ asset('public/images/users/'.$val->image) }}" id="account-upload-img" class="uploadedAvatar rounded me-50" alt="profile image" height="100" width="100" /></td>
                                                <td>
                                                    
                                                    <a href="{{ route('customers.show',$val->id) }}"><strong>{{ $val->name }}</strong></a>
                                                </td>
                                                <td>{{ $val->store_name }}</td>
                                                <td>{{ $val->mobile }}</td>
                                                <td>{{ $val->gender }}</td>
                                                <td>{{ $val->state }}</td>
                                                <td>{{ $val->city }}</td>
                                                <td>{{ date('d-M-y H:i:s',strtotime($val->created_at)) }}</td>
                                                <td><a href="{{ route('customers.show',$val->id) }}"><button class="btn btn-dark">View</button></a></td>
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