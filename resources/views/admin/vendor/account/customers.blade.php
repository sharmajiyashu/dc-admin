


@extends('admin.layouts.app')

@section('content')
<style>
    .error{
        color:red;
    }
    
</style>
<style>
    .Active{
        color: green;
        font-weight: 900;
    }
    .Inactive{
        color: red;
        font-weight: 900;
    }
    .Pending{
        color: orange;
        font-weight: 900;
    }
</style>

 <!-- BEGIN: Content-->
 <div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Account</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('/') }}">Home</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('vendors.index') }}">Vendors </a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">Account Settings </a>
                                </li>
                                <li class="breadcrumb-item active"> Customers
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="content-body">
            <div class="row">
                <div class="col-12">
                    @include('admin.vendor.account.include-nav')

                    @if ($errors->any())
                        @foreach ($errors->all() as $error)
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <div class="alert-body">
                                                    {{$error}}
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        @endforeach
                    @endif

                    <!-- profile -->
                    <div class="card">
                        <div class="card-header border-bottom">
                            <h4 class="card-title">Customer Details</h4>

                            <a href="#" class="text-danger" data-bs-toggle="modal" data-bs-target="#AddStporeCode"><button class="btn btn-primary me-1 waves-effect waves-float waves-light">Add Customer</button></a>


                            <!-- Modal -->
                            <div class="modal fade modal-primary text-start" id="AddStporeCode" tabindex="-1" aria-labelledby="myModalLabel120" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="myModalLabel120">Add Customer</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form action="{{route('vendors.account.customers.add_customers')}}" method="POST">
                                                    @csrf
                                                <div class="col-md-12 col-12">
                                                    <div class="mb-1">
                                                        <label class="form-label" for="first-name-column">Customer Mobile <span class="error">*</span></label>
                                                        <input type="number" id="first-name-column" name="mobile" class="form-control" placeholder="Customer Mobile" value="{{ old('mobile') }}" />
                                                        <input type="hidden" name="vendor_id" id="" value="{{ $vendor->id }}">
                                                    </div>
                                                </div>
                                            </div>
                                            
                                                <div class="modal-footer">
                                                    {{-- <input type="hidden" name="id" value="{{ $val->id }}"> --}}
                                                    <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Submit</button>
                                                </div>
                                            </form>
                                        </div>
                                </div>
                            </div>


                        </div>
                        <div class="card-body my-25">
                            <div class="card-datatable">
                                <table class="datatables-ajax table table-responsive datatable_data">
                                    <thead>
                                        <tr>
                                            <th>Sr.no</th>
                                            <th>Customer Name</th>
                                            <th>Mobile</th>
                                            <th>State</th>
                                            <th>City</th>
                                            <th>Status</th>
                                            <th>Created Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php  $i=1; @endphp
                                        @foreach($customers as $key => $val)
                                        <tr>
                                            <th scope="row">{{ $i }}</th>
                                            <td>{{ $val->customer_name }}</td>
                                            <td>{{ $val->customer_mobile }}</td>
                                            <td>{{ $val->customer_state }}</td>
                                            <td>{{ $val->customer_city }}</td>
                                            <td class="{{$val->status}} text-bold">{{ $val->status }}</td>
                                            <td>{{ date('d-M-y H:i:s',strtotime($val->created_at)) }}</td>
                                            <td>
                                                <a href="#" class="text-primary" data-bs-toggle="modal" data-bs-target="#edit_ke{{ $val->id }}"><button class="btn btn-primary">Edit</button></a>

                                                    <!-- Modal -->
                                                <div class="modal fade modal-primary text-start" id="edit_ke{{ $val->id }}" tabindex="-1" aria-labelledby="myModalLabel120" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="myModalLabel120">Edit Status</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <form action="{{route('vendors.account.customers.change_status')}}" method="POST">
                                                                        @csrf

                                                                        Are you sure you want to delete

                                                                    <div class="col-md-12 col-12">
                                                                        <div class="mb-1">
                                                                            <label class="form-label"  for="last-name-column"></label>
                                                                            <select class="form-select" name="status" id="basicSelect">
                                                                                <option value="1" {{ (isset($val->status) && $val->status == 'Active') ? 'selected' : '' }}>Active</option>
                                                                                <option value="2" {{ (isset($val->status) && $val->status == 'Inactive') ? 'selected' : '' }}>Inactive</option>
                                                                                <option value="0" {{ (isset($val->status) && $val->status == 'Pending') ? 'selected' : '' }}>Pending</option>
                                                                            </select>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                                
                                                                    <div class="modal-footer">
                                                                        <input type="hidden" name="id" value="{{ $val->id }}">
                                                                        <button type="submit" class="btn btn-primary" data-bs-dismiss="modal">Change</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                    </div>
                                                </div>

                                                <a href="#" class="text-danger" data-bs-toggle="modal" data-bs-target="#danger_ke{{ $val->id }}"><button class="btn btn-danger">Delete</button></a>

                                                    <!-- Modal -->
                                                <div class="modal fade modal-danger text-start" id="danger_ke{{ $val->id }}" tabindex="-1" aria-labelledby="myModalLabel120" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered">
                                                        
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="myModalLabel120">Delete Product</h5>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    Are you Shure you want to delete !
                                                                </div>
                                                                <form action="{{route('vendors.account.customers.delete')}}" method="POST">
                                                                    @csrf
                                                                    <div class="modal-footer">
                                                                        <input type="hidden" name="id" value="{{ $val->id }}">
                                                                        <button type="submit" class="btn btn-danger" data-bs-dismiss="modal">Delete</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                    </div>
                                                </div>


                                            </td>
                                        </tr>
                                        @php $i++; @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                           
                            
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
    <!-- END: Content-->
@endsection