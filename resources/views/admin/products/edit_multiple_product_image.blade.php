
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
                            <h2 class="content-header-title float-start mb-0">Product</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a>
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
                                
                                <form action="{{ route('update_multiple_products_image') }}" method="POST" enctype="multipart/form-data" >
                                    @csrf

                                    <input type="hidden" value="{{ $product_2 }}" name="products">

                                    <div class="card-datatable">
                                        <table class="datatables-ajax table table-responsive ">
                                            <thead>
                                                <tr>
                                                    <th>Sr.no</th>
                                                    <th>Product Name</th>
                                                    <th>Category Name</th>
                                                    <th>Image 1</th>
                                                    <th>Image 2</th>
                                                    <th>Image 3</th>
                                                    <th>Image 4</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php  $i=1; @endphp
                                                @foreach($products as $key => $val)
                                                    <tr>
                                                        <td>{{ $i }}</td>
                                                        <td><strong>{{ $val->name }}</strong></td>
                                                        <td>{{ $val->category_name }}</td>
                                                        <td><img src="{{ asset('public/images/products/'.$val->image_1) }}" alt="" width="100"><br>
                                                            <input type="file" class="form-control" name="image_1_{{ $val->id }}">
                                                        </td>
                                                        <td><img src="{{ asset('public/images/products/'.$val->image_2) }}" alt="" width="100"><br>
                                                            <input type="file" class="form-control" name="image_2_{{ $val->id }}">
                                                        </td>
                                                        <td><img src="{{ asset('public/images/products/'.$val->image_3) }}" alt="" width="100"><br>
                                                            <input type="file" class="form-control" name="image_3_{{ $val->id }}">
                                                        </td>
                                                        <td><img src="{{ asset('public/images/products/'.$val->image_4) }}" alt="" width="100"><br>
                                                            <input type="file" class="form-control" name="image_4_{{ $val->id }}">
                                                        </td>
                                                    </tr>
                                                @php $i++; @endphp
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <input type="hidden" name="get_data" value="{{ $page_data }}">
                                    <button>Submit</button>
                                </form>


                            </div>
                        </div>
                    </div>
                </section>

            </div>
        </div>
    </div>
    <!-- END: Content-->
    <!-- END: Content-->

@endsection