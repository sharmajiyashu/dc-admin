
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
                                <form>
                                <div class="card-body border-bottom row">

                                    
                                    <div class="col-md-2">
                                        <label for="">Search By OrderId</label>
                                        <input type="text" class="form-control" name="order_id" value="@isset($get['order_id']){{ $get['order_id'] }}@endisset" placeholder="Order ID ...">
                                    </div>

                                    <div class="col-md-2">
                                        <label for="">Select Buyer</label>
                                        <select name="customer_id" id="department" class="select2 form-select">
                                            <option value="">All</option>
                                            @foreach ($buyer as $item)
                                                <option value="{{ $item->id }}" @if (isset($get['customer_id']))
                                                    @if ($get['customer_id'] == $item->id)
                                                        selected    
                                                    @endif
                                                    
                                                @endif>{{ $item->store_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <label for="">Select Seller</label>
                                        <select name="vendor_id" id="department_vendor" class="select2 form-select">
                                            <option value="">All</option>
                                            @foreach ($seller as $item)
                                                <option value="{{ $item->id }}" @if (isset($get['vendor_id']))
                                                    @if ($get['vendor_id'] == $item->id)
                                                        selected    
                                                    @endif
                                                    
                                                @endif>{{ $item->store_name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2" style="    text-align: ;align-self: end;">
                                        <button class="btn btn-info" >Search</button>
                                    </form>

                                    </div>
                                    
                                </div>

                                <div class="card-datatable">
                                    <table class="datatables-ajax table table-responsive ">
                                        <thead>
                                            <tr>
                                                <th>Sr.no</th>
                                                <th>Order ID</th>
                                                <th>Buyer</th>
                                                <th>Seller</th>
                                                <th>Item</th>
                                                <th>Amount</th>
                                                <th>status</th>
                                                <th>Created Date</th>
                                                <th>Invoice</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php  $i = ($orders->currentPage() - 1) * $orders->perPage() + 1; @endphp
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
                                    @include('admin._pagination_filter', ['data' => $orders,'keyword' => $get])
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