


<!DOCTYPE html>
<html class="loading" lang="en" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="description" content="Vuexy admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, Vuexy admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <title>Order History - Jewellery Dukan </title>
    <link rel="apple-touch-icon" href="{{ asset('public/admin/app-assets/images/ico/apple-icon-120.png')}}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('public/admin/app-assets/images/ico/favicon.ico')}}">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/admin/app-assets/vendors/css/vendors.min.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/admin/app-assets/vendors/css/charts/apexcharts.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/admin/app-assets/vendors/css/extensions/toastr.min.css')}}">
    <!-- END: Vendor CSS-->

    <!-- BEGIN: Theme CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/admin/app-assets/css/bootstrap.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/admin/app-assets/css/bootstrap-extended.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/admin/app-assets/css/colors.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/admin/app-assets/css/components.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/admin/app-assets/css/themes/dark-layout.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/admin/app-assets/css/themes/bordered-layout.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/admin/app-assets/css/themes/semi-dark-layout.css')}}">

    <!-- BEGIN: Page CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/admin/app-assets/css/core/menu/menu-types/vertical-menu.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/admin/app-assets/css/pages/dashboard-ecommerce.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/admin/app-assets/css/plugins/charts/chart-apex.css')}}">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/admin/app-assets/css/plugins/extensions/ext-component-toastr.css')}}">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/admin/assets/css/style.css')}}">
    <!-- END: Custom CSS-->
    <link rel="stylesheet" href="{{ asset('public/admin/assets/datatable/datatable.css') }}">
    <style>
        .card-datatable{
            padding: 9px;
        }
        tr{
            border-bottom: 1px solid rgb(105 94 94 / 20%) !important;
        }
        table.dataTable tbody tr.even {
            background-color: #f3f2f7;
        }
    </style>
</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern  navbar-floating footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="">

        <div class="header-navbar"></div>
        <div class="content-wrapper container-xxl p-0">
           
            <div class="content-body">

           

                <!-- Basic multiple Column Form section start -->
                <section id="multiple-column-form">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="">Order #{{ $order->order_id }}</h3>
                                    
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
                                                    <td>{{ $item->quantity }}
                                                        @if ($item->out_of_stock > 0)
                                                        <br><span style="color: red !important;">( -{{$item->out_of_stock}} out of stock)</span>
                                                    @endif</td>
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

    @include('admin.layouts.js')

</body>
<!-- END: Body-->

</html>