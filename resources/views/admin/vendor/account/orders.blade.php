


@extends('admin.layouts.app')

@section('content')
<style>
    .error{
        color:red;
    }
</style>
<style>
    .rejected{
        color: #f21e20;
        font-weight: 900;
        text-transform: uppercase;
    }
    .delivered{
        color: #8f968f;
        font-weight: 900;
        text-transform: uppercase;
    }
    .accepted{
        color:#6daf6d;
        font-weight: 900;
        text-transform: uppercase;
    }
    .dispatched{
        color: #b0c348;
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
                                <li class="breadcrumb-item"><a href="{{ route('vendors.index') }}">Vendors </a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">Account Settings </a>
                                </li>
                                <li class="breadcrumb-item active"> Orders
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

                    <!-- profile -->
                    <div class="card">
                        <div class="card-header border-bottom">
                            <h4 class="card-title">Order Details</h4>
                        </div>
                        <div class="card-body my-25">
                            <div class="card-datatable">
                                <table class="datatables-ajax table table-responsive datatable_data">
                                    <thead>
                                        <tr>
                                            <th>Sr.no</th>
                                            <th>Order ID</th>
                                            <th>Buyer</th>
                                            <th>Seller</th>
                                            <th>Item</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Created Date</th>
                                            <th>Invoice</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php  $i=1; @endphp
                                        @foreach($orders as $key => $val)
                                        <tr>
                                            <th scope="row">{{ $i }}</th>
                                            <td><a href="{{ route('orders.show',$val->id) }}"><strong>#{{ $val->order_id }}</strong></a></td>
                                            <td>{{ $val->user_name }}</td>
                                            <td>{{ $val->vendor_name }}</td>
                                            <td>{{ $val->total_item }}</td>
                                            <td>{{ $val->amount }}</td>
                                            <td class="{{$val->status}} text-bold">{{ $val->status }}</td>
                                            <td>{{ date('d-M-y H:i:s',strtotime($val->created_at)) }}</td>
                                            <td><a href="{{ route('orders.invoice',$val->order_id) }}"><button class="btn btn-dark">Invoice</button></a></td>
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