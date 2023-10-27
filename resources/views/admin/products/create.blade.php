


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
                                    <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Products</a>
                                    </li>
                                    <li class="breadcrumb-item active">Add
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
                                    <h4 class="card-title">Create</h4>
                                </div>
                                <div class="card-body">
                                    <form class="form" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    
                                        <div class="row">

                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="first-name-column">Product Name</label>
                                                    <input type="text" id="product_name" name="name" class="form-control" required placeholder="Title" value="{{ old('name') }}" oninput="this.value = this.value.toUpperCase()" />
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label"  for="last-name-column">Category</label>
                                                    <select class="form-select" name="category_id" id="basicSelect" required> 
                                                        <option value="">(Select Category)</option>
                                                        @foreach ($categories as $item)
                                                            <option value="{{ $item->id }}" {{ (old("category_id") == $item->id ? "selected":"") }}  >{{ $item->title }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label"  for="last-name-column">Status</label>
                                                    <select class="form-select" name="status" id="basicSelect">
                                                        <option value="1" {{ (old("status") == '1' ? "selected":"") }}>Active</option>
                                                        <option value="0" {{ (old("status") == '0' ? "selected":"") }}>Inactive</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label"  for="last-name-column">Image</label>
                                                    <input type="file" name="image[]" class="form-control product_image" required>
                                                </div>
                                            </div>

                                            <div class="col-md-12" >
                                                <div class="row" id="appentd_image" >

                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-1">
                                                    <a href="#" class="btn-danger" id="ADDMOREIMEGBUTTION" style="padding: 4px;" onclick="append_image()">Add More Image</a>
                                                </div>
                                            </div>

                                            <script>
                                                function append_image(){

                                                    const elements = document.querySelectorAll('.product_image');
                                                    const count = elements.length;
                                                    
                                                    if(count > 3){
                                                        
                                                    }else{
                                                        $("#appentd_image").append(`<div class="col-md-6 col-12">
                                                            <div class="mb-1">
                                                                <label class="form-label"  for="last-name-column">Image</label>
                                                                <input type="file" name="image[]" class="form-control product_image">
                                                            </div>
                                                        </div>`);
                                                    }   

                                                    const elementss = document.querySelectorAll('.product_image');
                                                    const counts = elementss.length;

                                                    if(counts == 4){
                                                        document.getElementById("ADDMOREIMEGBUTTION").style.display = "none";
                                                    }
                                                    
                                                }
                                            </script>
                                            
                                            <div class="col-md-12 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="exampleFormControlTextarea1">Product Detail*</label>
                                                    <textarea class="form-control" name="detail" id="exampleFormControlTextarea1" rows="3" placeholder="Detail">{{ old('detail') }}</textarea>
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