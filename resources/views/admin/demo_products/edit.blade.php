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
                            <h2 class="content-header-title float-start mb-0">Demo Product</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ url('/')}}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="{{ route('demo_products.index') }}">Demo Products</a>
                                    </li>
                                    <li class="breadcrumb-item active">Edit
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
                                    <form class="form" action="{{ route('demo_products.update',$demoProduct->id) }}" method="POST" enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        @method('PATCH')

                                        <div class="row">
                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="first-name-column">Product Name</label>
                                                    <input type="text" id="product_name" name="name" class="form-control" placeholder="Title" value="{{ $demoProduct->name }}" />
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="first-name-column">Stock</label>
                                                    <input type="number" id="" name="stock" class="form-control"  placeholder="Stock" value="{{ $demoProduct->stock }}"  />
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="first-name-column">Price MRP*</label>
                                                    <input type="number" id="" name="mrp" class="form-control"  placeholder="MRP Price" value="{{ $demoProduct->mrp }}" required  />
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label"  for="last-name-column">Category</label>
                                                    <select class="form-select" name="category_id" id="basicSelect">
                                                        <option value="">(Select Category)</option>
                                                        @foreach ($categories as $item)
                                                            <option value="{{ $item->id }}" {{ (isset($demoProduct->category_id) && $demoProduct->category_id == $item->id) ? 'selected' : '' }}>{{ $item->title }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            

                                            

                                            

                                            <div class="col-md-3">
                                                <div class="mb-1">
                                                    <label class="form-label"  for="last-name-column">Image 1</label>
                                                    <div>
                                                        @isset($demoProduct->images[0])
                                                            <i data-feather="trash" class="me-50" onclick="delete_image({{ $demoProduct->id }},'{{ $demoProduct->image_1 }}')" style="color:red"></i>
                                                        @endisset
                                                    </div>
                                                    <div><img src="{{ asset('public/images/products/'.$demoProduct->image_1) }}" alt="" width="100" height="100" class="product_image"></div>
                                                    <input type="file" class="form-control" name="image_1">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="mb-1">
                                                    <label class="form-label"  for="last-name-column">Image 2</label>
                                                    <div>
                                                        @isset($demoProduct->images[1])
                                                            <i data-feather="trash" class="me-50" onclick="delete_image({{ $demoProduct->id }},'{{ $demoProduct->image_1 }}')" style="color:red"></i>
                                                        @endisset
                                                    </div>
                                                    <div><img src="{{ asset('public/images/products/'.$demoProduct->image_2) }}" alt="" width="100" height="100" class="product_image"></div>
                                                    <input type="file" class="form-control" name="image_2">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="mb-1">
                                                    <label class="form-label"  for="last-name-column">Image 3</label>
                                                    <div>
                                                        @isset($demoProduct->images[2])
                                                            <i data-feather="trash" class="me-50" onclick="delete_image({{ $demoProduct->id }},'{{ $demoProduct->image_1 }}')" style="color:red"></i>
                                                        @endisset
                                                    </div>
                                                    <div><img src="{{ asset('public/images/products/'.$demoProduct->image_3) }}" alt="" width="100" height="100" class="product_image"></div>
                                                    <input type="file" class="form-control" name="image_3">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="mb-1">
                                                    <label class="form-label"  for="last-name-column">Image 4</label>
                                                    <div>
                                                        @isset($demoProduct->images[3])
                                                            <i data-feather="trash" class="me-50" onclick="delete_image({{ $demoProduct->id }},'{{ $demoProduct->image_1 }}')" style="color:red"></i>
                                                        @endisset
                                                    </div>
                                                    <div><img src="{{ asset('public/images/products/'.$demoProduct->image_4) }}" alt="" width="100" height="100" class="product_image"></div>
                                                    <input type="file" class="form-control" name="image_4">
                                                </div>
                                            </div>


                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label"  for="last-name-column">Status</label>
                                                    <select class="form-select" name="status" id="basicSelect">
                                                        <option value="1"  {{ (isset($demoProduct->status) && $demoProduct->status == 1) ? 'selected' : '' }}>Active</option>
                                                        <option value="0" {{ (isset($demoProduct->status) && $demoProduct->status == 0) ? 'selected' : '' }}>Inactive</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="first-name-column">Selling Price</label>
                                                    <input type="number" id="" name="sp" class="form-control"  placeholder="SP Price" value="{{ $demoProduct->sp }}"  />
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="first-name-column">Order limit</label>
                                                    <input type="number" id="" name="order_limit" class="form-control"  placeholder="Order Limit" value="{{ $demoProduct->order_limit }}"  />
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="first-name-column">Quantity</label>
                                                    <input type="number" id="" name="quantity" class="form-control"  placeholder="Quantity" value="{{ $demoProduct->quantity }}"  />
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="first-name-column">Unit</label>
                                                    <input type="text" id="" name="unit" class="form-control"  placeholder="Unit" value="{{ $demoProduct->unit }}"  />
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="first-name-column">Box Quantity</label>
                                                    <input type="number" id="" name="packing_quantity" class="form-control"  placeholder="Packing Quantity" value="{{ $demoProduct->packing_quantity }}"  />
                                                </div>
                                            </div> 
                                            
                                            

                                            
                                            
                                            <div class="col-md-12 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="exampleFormControlTextarea1">Product Detail*</label>
                                                    <textarea class="form-control" name="detail" id="exampleFormControlTextarea1" rows="3" placeholder="Detail">{{ $demoProduct->detail }}</textarea>
                                                </div>
                                            </div>
                                            <input type="hidden" name="page_data" value="{{ $page_data }}">

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

    <script>
        function delete_image(id,name){
            $('#product_image').val(name);
            $('#danger_ke').modal('show');
        }
    </script>

    <div class="modal fade modal-danger text-start" id="danger_ke" tabindex="-1" aria-labelledby="myModalLabel120" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel120">Delete Product</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete !
                    </div>
                    <form action="{{ route('delete-product-image') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" id="" value="{{ $demoProduct->id }}">
                        <input type="hidden" name="image" id="product_image" value="">
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger" data-bs-dismiss="modal">Delete</button>
                        </div>
                    </form>
                </div>
        </div>
    </div>


    <script>
        window.onload = function() {
            // Get the "name" input field by its ID
            var nameInput = document.getElementById("product_name");

            if (nameInput) {
                // Set the cursor focus to the "name" input field
                nameInput.focus();
            }
        };
    </script>


@endsection