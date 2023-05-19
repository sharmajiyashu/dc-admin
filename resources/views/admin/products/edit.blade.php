


@extends('admin.layouts.app')

@section('content')

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
                                    <li class="breadcrumb-item"><a href="{{ url('/')}}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">products</a>
                                    </li>
                                    <li class="breadcrumb-item active"><a href="#">Edit</a>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
                                <div class="card-header">
                                    <h4 class="card-title">Update</h4>
                                </div>
                                <div class="card-body">
                                    <form class="form" action="{{ route('products.update',$product->id) }}" method="POST">
                                        {{ csrf_field() }}
                                        @method('PATCH')

                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="first-name-column">Product Name</label>
                                                    <input type="text" id="first-name-column" name="name" class="form-control" placeholder="Title" value="{{ $product->name }}" />
                                                </div>
                                            </div>
                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label"  for="last-name-column">Status</label>
                                                    <select class="form-select" name="status" id="basicSelect">
                                                        <option value="Active"  {{ (isset($product->status) && $product->status == 'Active') ? 'selected' : '' }}>Active</option>
                                                        <option value="Inactive" {{ (isset($product->status) && $product->status == 'Inactive') ? 'selected' : '' }}>Inactive</option>
                                                    </select>
                                                </div>
                                            </div><div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label"  for="last-name-column">Category</label>
                                                    <select class="form-select" name="category_id" id="basicSelect">
                                                        <option value="Active">(Select Category)</option>
                                                        @foreach ($categories as $item)
                                                            <option value="{{ $item->id }}" {{ (isset($product->category_id) && $product->category_id == $item->id) ? 'selected' : '' }}>{{ $item->title }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="first-name-column">Stock</label>
                                                    <input type="number" id="first-name-column" name="stock" class="form-control" placeholder="Stock" value="{{ $product->stock }}" />
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="first-name-column">Price MRP*</label>
                                                    <input type="text" id="first-name-column" name="mrp" class="form-control" placeholder="M.R.P" value="{{ $product->mrp }}" />
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="first-name-column">Selling Price*</label>
                                                    <input type="text" id="first-name-column" name="sp" class="form-control" placeholder="S.P." value="{{ $product->sp }}" />
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="first-name-column">Order Limit*</label>
                                                    <input type="text" id="first-name-column" name="order_limit" class="form-control" placeholder="Order Limit" value="{{ $product->order_limit }}" />
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="first-name-column">Quantity*</label>
                                                    <input type="text" id="first-name-column" name="quantity" class="form-control" placeholder="Quantity" value="{{ $product->quantity }}" />
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="first-name-column">Unit*</label>
                                                    <input type="text" id="first-name-column" name="unit" class="form-control" placeholder="Unit" value="{{ $product->unit }}" />
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="first-name-column">Packing Quantity*</label>
                                                    <input type="text" id="first-name-column" name="packing_quantity" class="form-control" placeholder="Packing Quantity" value="{{ $product->packing_quantity }}" />
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-12 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="exampleFormControlTextarea1">Product Detail*</label>
                                                    <textarea class="form-control" name="detail" id="exampleFormControlTextarea1" rows="3" placeholder="Detail">{{ $product->detail }}</textarea>
                                                </div>
                                            </div>


                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary me-1">Submit</button>
                                                <button type="reset" class="btn btn-outline-secondary">Reset</button>
                                            </div>
                                        </div>
                                    </form>
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