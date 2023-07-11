
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
                            <h2 class="content-header-title float-start mb-0">Order</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">orders</a>
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
                                                <td>{{ date('d-M-y H:i:s',strtotime($val->created_at)) }}</td>
                                                <td><a href="{{ route('orders.invoice',$val->order_id) }}"><i data-feather="eye" class="me-50"></i></a></td>
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