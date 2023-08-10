


@extends('admin.layouts.app')

@section('content')

 <!-- BEGIN: Content-->
 <div class="app-content content ">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper container-xxl p-0">
           
            <div class="content-body">

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

                <!-- Basic multiple Column Form section start -->
                <section id="multiple-column-form">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-body row">
                                    <div class="col-md-8"> 
                                        <h3 class="">Order #{{ $order->order_id }}</h3>
                                    </div>
                                    <div class="col-md-4" style="text-align: end;">
                                        @if ($order->status == 'pending')
                                            <a href="{{ route('change-order-status',['id' => $order->id, 'status' => 'accept']) }}"><button class="btn btn-success">Accept</button></a>
                                            <a href="{{ route('change-order-status',['id' => $order->id, 'status' => 'reject']) }}"><button class="btn btn-danger">Reject</button></a>
                                        @elseif ($order->status == 'accepted')
                                            <a href="{{ route('change-order-status',['id' => $order->id, 'status' => 'dispach']) }}"><button class="btn btn-success">Dispatch</button></a>

                                        @elseif ($order->status == 'dispatched')
                                            <a href="{{ route('change-order-status',['id' => $order->id, 'status' => 'deliver']) }}"><button class="btn btn-success">Deliver</button></a>
                                        @else
                                            
                                        @endif
                                        
                                    </div>
                                    
                                </div>
                                <div class="card-body">
                                    <h5 style="    color: #e16262;">ITEM(S): 2 | TOTAL: ₹ 540</h5>
                                    <div style="border-top: solid;padding-top: 1%;text-transform: uppercase;font-weight: 500;">
                                        <div style=" float:left">
                                            By : {{ $vendor->name }} ({{ $vendor->city }})
                                        </div>
                                        <div style="float:right">
                                            {{ date('Y-m-d h:i:s',strtotime($order->created_at)) }}
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-header">
                                    <h3 class="">Customers Details</h3>
                                    
                                </div>
                                <div class="card-body">
                                    <div class="row" style="font-weight: 500;">
                                        <div class="col-md-6">
                                            <div style="float:left">From</div>
                                            <div style="float:right;text-transform: uppercase;">{{ $customer->name }}</div>
                                        </div>
                                      
                                        <div class="col-md-6">
                                            <div style="float:left">Contact</div>
                                            <div style="float:right">{{ $customer->mobile }}</div>
                                        </div>

                                        <div class="col-md-6">
                                            <div style="float:left">Address</div>
                                            <div style="float:right">{{ $customer->address }}</div>
                                        </div>

                                        <div class="col-md-6">
                                            <div style="float:left">Order Status</div>
                                            <div style="float:right;text-transform: uppercase;">{{ $order->status }}</div>
                                        </div>

                                        <div class="col-md-6">
                                            <div style="float:left">Order Note</div>
                                            <div style="float:right">{{ $order->note }}</div>
                                        </div>

                                        <div class="col-md-6">
                                            <div style="float:left">Payment Status</div>
                                            <div style="float:right">Unpaid</div>
                                        </div>

                                        
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="">Customers Details</h3>
                                </div>
                                <div class="card-body" style="font-weight: 500;">
                                    <div class="card-datatable">
                                        <table class="datatables-ajax table table-responsive ">
                                            <thead>
                                                <tr>
                                                    <th>Sr.no</th>
                                                    <th>Item</th>
                                                    <th>MRP</th>
                                                    <th>S.P.</th>
                                                    <th>Quantity</th>
                                                    <th>Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php $i=1; ?>
                                               @foreach ($carts as $item)
                                                <tr>
                                                    <td>{{ $i }}</td>
                                                    <td>
                                                        <img src="{{ asset('public/images/products/'.$item->image) }}" alt="product Image" style=" width: 8%;">
                                                        {{ $item->product_name }}</td>
                                                    <td>{{ $item->p_mrp }}</td>
                                                    <td>{{ $item->p_price }}</td>
                                                    <td>{{ $item->quantity }}</td>
                                                    <td>{{ $item->total }}</td>
                                                </tr>
                                                <?php $i++; ?>
                                               @endforeach

                                               <tr style="background: #f3f2f7;">
                                                <td></td>
                                                <td style="text-align: center;"><strong>Total</strong></td>
                                                <td><strong>{{ $order->sum_mrp }}</strong></td>
                                                <td><strong>{{ $order->sum_price }}</strong></td>
                                                <td><strong>{{ $order->sum_quantity }}</strong></td>
                                                <td><strong>{{ $order->amount }}</strong></td>
                                               </tr>
                                            </tbody>
                                        </table>
                                        <br>
                                        <br>
                                        
                                        <br>
                                        <table class="datatables-ajax table table-responsive ">
                                            <thead>
                                                <tr>
                                                    <th>Total Of Amount</th>
                                                    <td><strong>{{ $order->amount }}/-</strong></td>
                                                </tr>
                                                <tr>
                                                    <th>In Words</th>
                                                    <td>{{ $order->in_word }}</td>
                                                </tr>
                                            </thead>
                                            
                                            
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- Basic Floating Label Form section end -->

            </div>
        </div>
    </div>
    <!-- END: Content-->
@endsection