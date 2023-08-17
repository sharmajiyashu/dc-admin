


@extends('admin.layouts.app')

@section('content')
<style>
    .error{
        color:red;
    }
</style>
<style>
    .accept{
        color: green;
        font-weight: 900;
        text-transform: uppercase;
    }
    .accept{
        color: green;
        font-weight: 900;
        text-transform: uppercase;
    }
    .reject{
        color: red;
        font-weight: 900;
        text-transform: uppercase;
    }
    .pending{
        color: orange;
        font-weight: 900;
        text-transform: uppercase;
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
                                <li class="breadcrumb-item"><a href="{{ route('customers.index') }}">Customers </a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">{{ $customer->name }}</a>
                                </li>
                                <li class="breadcrumb-item active"> Cart
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
                    {{-- <ul class="nav nav-pills mb-2">
                        <!-- account -->
                        <li class="nav-item">
                            <a class="nav-link " href="{{ route('customers.show',$customer->id) }}">
                                <i data-feather="user" class="font-medium-3 me-50"></i>
                                <span class="fw-bold">Account</span>
                            </a>
                        </li>
                        <!-- security -->
                        
                        <!-- billing and plans -->
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('customers.account.stores',$customer->id) }}">
                                <i data-feather="user" class="font-medium-3 me-50"></i>
                                <span class="fw-bold">Stores</span>
                            </a>
                        </li>
                        <!-- notification -->
                        <li class="nav-item">
                            <a class="nav-link " href="{{ route('customers.account.orders',$customer->id) }}">
                                <i data-feather="shopping-cart" class="font-medium-3 me-50"></i>
                                <span class="fw-bold">Orders</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link " href="{{ route('customers.account.wishlist',$customer->id) }}">
                                <i data-feather="shopping-cart" class="font-medium-3 me-50"></i>
                                <span class="fw-bold">Wishlist</span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link active" href="{{ route('customers.account.carts',$customer->id) }}">
                                <i data-feather="shopping-cart" class="font-medium-3 me-50"></i>
                                <span class="fw-bold">Carts</span>
                            </a>
                        </li>
                        
                    </ul> --}}

                    @include('admin.customers.account.include-nav')

                    <!-- profile -->
                    <div class="card">
                        <div class="card-header border-bottom">
                            <h4 class="card-title">Cart Details</h4>
                        </div>
                        <div class="card-body my-25">
                            <div class="card-datatable">
                                <table class="datatables-ajax table table-responsive datatable_data">
                                    <thead>
                                        <tr>
                                            <th>Sr.no</th>
                                            <th>Product</th>
                                            <th>MRP</th>
                                            <th>S.P.</th>
                                            <th>Quantity</th>
                                            <th>Amount</th>
                                            <th>Created Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php  $i=1; @endphp
                                        @foreach($cart_items as $key => $val)
                                        <tr>
                                            <th scope="row">{{ $i }}</th>
                                            <td><img src="{{ asset('public/images/products/'.$val->product_image) }}" id="account-upload-img" class="uploadedAvatar rounded me-50" alt="profile image" height="100" width="100" /><strong>{{ $val->product_name }}</strong></td>
                                            <td>{{ $val->p_mrp }}</td>
                                            <td>{{ $val->p_price }}</td>
                                            <td>{{ $val->quantity }}</td>
                                            <td><strong>{{ $val->total }}</strong></td>
                                            <td>{{ date('d-M-y H:i:s',strtotime($val->created_at)) }}</td>
                                            {{-- <td><a href="{{ route('orders.invoice',$val->order_id) }}"><i data-feather="eye" class="me-50"></i></a></td> --}}
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