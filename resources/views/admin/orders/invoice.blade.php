<!DOCTYPE html>
<html class="loading semi-dark-layout" lang="en" data-layout="semi-dark-layout" data-textdirection="ltr">
<!-- BEGIN: Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0,minimal-ui">
    <meta name="description" content="Vuexy admin is super flexible, powerful, clean &amp; modern responsive bootstrap 4 admin template with unlimited possibilities.">
    <meta name="keywords" content="admin template, Vuexy admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="PIXINVENT">
    <title>Jewellery Dukan - Invoice</title>
    <link rel="apple-touch-icon" href="{{ asset('public/admin/app-assets/images/ico/apple-icon-120.png')}}">
    <link rel="shortcut icon" type="image/x-icon" href="{{ asset('public/favicon.ico')}}">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,300;0,400;0,500;0,600;1,400;1,500;1,600" rel="stylesheet">

    <!-- BEGIN: Vendor CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/admin/app-assets/vendors/css/vendors.min.css')}}">
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
    <link rel="stylesheet" type="text/css" href="{{ asset('public/admin/app-assets/css/pages/app-invoice-print.css')}}">
    <!-- END: Page CSS-->

    <!-- BEGIN: Custom CSS-->
    <link rel="stylesheet" type="text/css" href="{{ asset('public/admin/assets/css/style.css')}}">
    <!-- END: Custom CSS-->

</head>
<!-- END: Head-->

<!-- BEGIN: Body-->

<body class="vertical-layout vertical-menu-modern blank-page navbar-floating footer-static  " data-open="click" data-menu="vertical-menu-modern" data-col="blank-page">
    <!-- BEGIN: Content-->
    <div class="app-content content  " id="overall_PDF">
        <div class="content-overlay"></div>
        <div class="header-navbar-shadow"></div>
        <div class="content-wrapper">
            <div class="content-header row">
            </div>

            <div class="content-body">
                <div class="invoice-print p-3">
                    
                    <div class="invoice-header d-flex justify-content-between flex-md-row flex-column pb-2">
                        <div class="d-flex justify-content-between " style="width: 95%">
                            <div>
                                <div class="d-flex mb-1">
                                    <img src="https://dcjewelry.in/admin/assets/uploads/logo_20230819122652_64e067b490ef8.jpg" alt="" style="height: 50px;width:100%">
                                    {{-- <h3 class="text-primary fw-bold ms-1">INVOICE</h3>  --}}
                                </div>
                                <p class="mb-25" style="font-size: 11px;">Date  : {{ date('d-M-y H:i:s') }}</p>
                            </div>
                            <div class="mt-md-0 mt-2">
                                <h3>Invoice : #{{ $order->order_id }}</h3>
                                <h6 class="">Invoice To  : {{ $customer->store_name }} </h6>
                                <h6 class="">Invoice From  : {{ $vendor->store_name }} </h6>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive mt-1">
                        <table class="table m-0" style="max-width: 100%;">
                            <thead>
                                <tr>
                                    <th class="py-1" style="width: 1%;">Sr.no</th>
                                    <th class="py-1">Image</th>
                                    <th class="py-1">Product</th>
                                    <th class="py-1">MRP</th>
                                    <th class="py-1">S.P.</th>
                                    <th class="py-1">Quantity</th>
                                    <th class="py-1">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i=1; ?>

                            {{-- @for ($i = 0; $i < 300; $i++) --}}

                                @foreach ($carts as $key => $item)
                                    <tr class="border-bottom">
                                        <td class="py-1">
                                            {{ $i }}
                                        </td>
                                        <td class="py-1">
                                            <img src="{{ asset('public/images/products/thumb1/'.$item->image) }}" alt="product Image" width="40" >
                                            
                                        </td>
                                        <td class="py-1">
                                            {{ $item->product_name }}
                                        </td>
                                        <td class="py-1">
                                            {{ $item->p_mrp }}
                                        </td>
                                        <td class="py-1">
                                            {{ $item->p_price }}
                                        </td>
                                        <td class="py-1">
                                            {{ $item->quantity }} 
                                            @if ($item->out_of_stock > 0)
                                                <br><span style="color: red !important;">( -{{$item->out_of_stock}} out of stock)</span>
                                            @endif
                                        </td>
                                        <td class="py-1">
                                            {{ $item->total }}
                                        </td>
                                        
                                    </tr>
                                    <?php $i++; ?>
                                @endforeach
                                
                            {{-- @endfor --}}

                                <tr style="background: #f3f2f7;">
                                    <td class="py-1"></td>
                                    <td class="py-1"></td>
                                    <td class="py-1" style="text-align: center;"><strong>Total</strong></td>
                                    <td class="py-1"> <strong>{{ $order->sum_mrp }}</strong></td>
                                    <td class="py-1"><strong>{{ $order->sum_price }}</strong></td>
                                    <td class="py-1"><strong>{{ $order->sum_quantity }}</strong></td>
                                    <td class="py-1"><strong>{{ $order->amount }}</strong></td>
                                    <td style="    background-color: white;"></td>
                                </tr>

                            </tbody>
                        </table>


                        <br>
                        <br>
                        <table class="datatables-ajax table table-responsive ">
                            <thead>
                                <tr>
                                    <th class="py-1">Total Of Amount</th>
                                    <td class="py-1"><strong>{{ $order->amount }}/-</strong></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <th class="py-1">In Words</th>
                                    <td class="py-1">{{ $order->in_word }}</td>
                                    <td></td>
                                </tr>
                                
                            </thead>
                            
                        </table>
                    </div>

                    {{-- <hr class="my-2" />

                    <a href="#" id="pdf_download" >Download</a> --}}

                    
                </div>


                

            </div>

            
        </div>
    </div>

                        
    <!-- END: Content-->


    <!-- BEGIN: Vendor JS-->
    <script src="{{ asset('public/admin/app-assets/vendors/js/vendors.min.js')}}"></script>
    <!-- BEGIN Vendor JS-->

    <!-- BEGIN: Page Vendor JS-->
    <!-- END: Page Vendor JS-->

    <!-- BEGIN: Theme JS-->
    <script src="{{ asset('public/admin/app-assets/js/core/app-menu.js')}}"></script>
    <script src="{{ asset('public/admin/app-assets/js/core/app.js')}}"></script>
    <!-- END: Theme JS-->

    <!-- BEGIN: Page JS-->
    <script src="{{ asset('public/admin/app-assets/js/scripts/pages/app-invoice-print.js')}}"></script>
    <!-- END: Page JS-->

    <script>
        $(window).on('load', function() {
            if (feather) {
                feather.replace({
                    width: 14,
                    height: 14
                });
            }
        })
    </script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js" integrity="sha512-GsLlZN/3F2ErC5ifS5QtgpiJtWd43JWSuIgh7mbzZ8zBps+dvLusV+eNQATqgA/HdeKFVgA5v3S/cIrLF7QnIg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    
    <script>
         //Download as PDF
    window.onload = function () {

    document.getElementById("pdf_download")
        .addEventListener("click", () => {
            const overall_PDF = this.document.getElementById("overall_PDF");
         const pdf = "Kapil.pdf";
            console.log(overall_PDF);
            console.log(window*2);
            
            var opt = {

                margin: 0.2,
                filename: pdf,
                image: { type: 'png', quality: 0.98 },
                // html2canvas: {scrollX: 0,  scrollY: 0, dpi: 600, letterRendering: true, },
                jsPDF: { unit: 'in', format: 'A4', orientation: 'p' }
            };
            html2pdf().from(overall_PDF).set(opt).save();
        })
} 

 
    </script>


</body>
<!-- END: Body-->


</html>