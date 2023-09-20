
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
                                <div class="card-body border-bottom row">
                                    
                                    <div class="col-md-7">
                                        <h4 class="card-title">List</h4>
                                    </div>
                                    <div class="col-md-3" style="    text-align: end;">
                                        <a href="#" class=" btn btn-success btn-gradient round  "  data-bs-toggle="modal" data-bs-target="#add_bulk_product">Add Bulk Product</a>

                                        <div class="modal fade modal-success text-start" id="add_bulk_product" tabindex="-1" aria-labelledby="myModalLabel120" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="myModalLabel120">Add Bulk Product  </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <form action="{{ route('add_bulk_csv') }}" method="post" enctype="multipart/form-data">
                                                            @csrf
                                                            <div class="modal-body">
                                                                <div class="md-1">
                                                                    <label for="" class="form-label">Upload CSV</label>
                                                                    <input type="file" name="csv_file" class="form-control">
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <a href="{{ asset('public/uploads/template.csv') }}" class="btn btn-info">Download Template</a>
                                                                    <div class="md-1">
                                                                        <button type="submit" class="btn btn-success">Submit</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </form>
                                                    </div>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-2">
                                        <a href="{{route('products.create')}}" class=" btn btn-primary btn-gradient round  ">Add Product</a>
                                    </div>
                                    
                                    
                                </div>
                                <div class="card-datatable">
                                    <table class="datatables-ajax table table-responsive ">
                                        <thead>
                                            <tr>
                                                <th>Sr.no</th>
                                                <th>Image</th>
                                                <th>Name</th>
                                                <th>Category</th>
                                                <th>status</th>
                                                <th>Created Date</th>
                                                <th>Action</th>
                                            </tr>

                                        </thead>
                                        <tbody>

                                           
                        

                                            @php  $i=1; @endphp
                                            @foreach($products as $key => $val)
                                            <tr>

                                                <script>
                                                   checkedValues_all_type.push({{ $val->id }});
                                                </script>
                                                <th scope="row">{{ $i }} </th>
                                                <td><img src="{{ asset('public/images/products/'.$val->image) }}" alt="" width="100"></td>
                                                <td><input type="checkbox" onclick="checkAllProducts()" name="checked_products" value="{{ $val->id }}">

                                                    <a href="#" class="text-primary" data-bs-toggle="modal" data-bs-target="#danger_k{{ $val->id }}"><strong>{{ $val->name }}</strong></a>
                                                    <!-- Modal -->
                                                    <div class="modal fade modal-dark text-start" id="danger_k{{ $val->id }}" tabindex="-1" aria-labelledby="myModalLabel120" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                                            
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h3 class="modal-title" id="myModalLabel120">Product Detail</h3>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <ul class="list-unstyled">
                                                                            <li class="mb-75">
                                                                                <span class="fw-bolder me-25">Product Name :</span>
                                                                                <span class="text-bold">{{ $val->name }}</span>
                                                                            </li>
                                                                            <li class="mb-75">
                                                                                <span class="fw-bolder me-25">Category :</span>
                                                                                <span>{{ isset($val->category->title) ? $val->category->title :'' }}</span>
                                                                            </li>
                                                                            <li class="mb-75">
                                                                                <span class="fw-bolder me-25">Status :</span>
                                                                                <span class="{{$val->status}} text-bold">{{ $val->status }}</span>
                                                                            </li>
                                                                            <li class="mb-75">
                                                                                <span class="fw-bolder me-25">Created Date :</span>
                                                                                <span>{{ $val->created_at }}</span>
                                                                            </li>
                                                                            <li class="mb-75">
                                                                                <span class="fw-bolder me-25">Description :</span>
                                                                                <span>{{ $val->detail }}</span>
                                                                            </li>

                                                                            <li class="mb-75">
                                                                                <span class="fw-bolder me-25">Images :</span>
                                                                                <div class="row">
                                                                                    @if (!empty($val->images))
                                                                                        @php
                                                                                            $products = json_decode($val->images);
                                                                                        @endphp
                                                                                        @foreach ($products as $item)
                                                                                            <div class="col-md-2">
                                                                                                <img src="{{ asset('public/images/products/'.$item) }}" alt="" width="100">
                                                                                            </div>
                                                                                        @endforeach
                                                                                    @endif
                                                                                </div>
                                                                            </li>
                                                                          </ul>
                                                                    </div>
                                                                    
                                                                </div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>{{ isset($val->category_name)  ? $val->category_name :'' }}</td>
                                                <td ><div class="form-check form-check-primary form-switch">
                                                        <input class="form-check-input checked_chackbox" id="systemNotification" type="checkbox" name="is_default" onclick="ChangeSlabStatus({{ $val->id }})" @if ($val->status == 'Active')
                                                            @checked(true) 
                                                        @endif   value="1" >
                                                    </div>
                                                </td>
                                                <td>{{ date('d-M-y H:i:s',strtotime($val->created_at)) }}</td>
                                                <td>
                                                    <a  href="{{route('products.edit',$val->id)}}">
                                                        <button class="btn btn-info">Edit</button>
                                                    </a>

                                                    <a href="#" class="text-danger" data-bs-toggle="modal" data-bs-target="#danger_ke{{ $val->id }}"><button class="btn btn-danger">Delete</button></a>

                                                    <!-- Modal -->
                                                    <div class="modal fade modal-danger text-start" id="danger_ke{{ $val->id }}" tabindex="-1" aria-labelledby="myModalLabel120" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="myModalLabel120">Delete Product</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        Are you sure you want to delete !
                                                                    </div>
                                                                    <form action="{{route('products.destroy',$val->id)}}" method="POST">
                                                                        @csrf
                                                                        @method('delete')
                                                                        <div class="modal-footer">
                                                                            <button type="submit" class="btn btn-danger" data-bs-dismiss="modal">Delete</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>
                                            @php $i++; @endphp
                                            @endforeach
                                        </tbody>
                                    </table>

                                    @include('admin._pagination', ['data' => $products_2])

                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <script>
                    const checkedValues_all_type = [];
                </script>

                <!--/ Ajax Sourced Server-side -->

                <script>
                    function checkAllProducts(){
                        const checkboxes = document.querySelectorAll('input[type="checkbox"][name="checked_products"]');

                        // Create an empty array to store the values of checked checkboxes
                        const checkedValues = [];

                        // Loop through each checkbox and check if it is checked
                        checkboxes.forEach((checkbox) => {
                            if (checkbox.checked) {
                                checkedValues.push(checkbox.value);
                            }
                        });

                        // toastr.success(checkedValues.length+' Product Selected');
                        
                        console.log(checkedValues.length);

                        const delete_select_product = document.getElementById('delete_select_product');
                        const update_select_product = document.getElementById('update_select_product');

                        if (checkedValues.length > 0) {
                            delete_select_product.disabled = false;
                            update_select_product.disabled = false;
                        } else {
                            delete_select_product.disabled = true;
                            update_select_product.disabled = true;
                        }
                        
                        const spanElement = document.getElementById('selected_product_count');
                        spanElement.textContent = checkedValues.length;

                        const encodedArray = JSON.stringify(checkedValues);

                        const hiddenInput = document.getElementById('update_selected_images');
                                hiddenInput.value = encodedArray;

                        const hiddenInput_2 = document.getElementById('delete_selected_images');
                        hiddenInput_2.value = encodedArray;
                        
                    }


                </script>


                <div class="card">
                    <div class="card-header">
                        
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2">

                            </div>
                            <div class="col-md-3">
                                <h3 > <span class="badge rounded-pill badge-light-success"  id="selected_product_count">0</span> Product Selected</h3>
                            </div>

                            <script>
                                function CheckAll(){
                                    const checkboxes_12356 = document.querySelectorAll('input[type="checkbox"][name="checked_products"]');
                                    const checkAllCheckbox_2563 = document.getElementById('check-all');

                                    console.log(checkedValues_all_type);

                                    
                                    checkboxes_12356.forEach((checkbox) => {
                                            if (checkAllCheckbox_2563.checked){
                                                checkbox.checked = true;
                                            }else{
                                                checkbox.checked = false;
                                            }
                                    });
                                    checkAllProducts();
                                }
                                
                              </script>

                            <div class="col-md-2">
                                <label for="" style="font-size: 19px;">Check All</label>
                                <input type="checkbox" id="check-all" onclick="CheckAll()"   style="width: 18px;height: 18px;">
                            </div>

                            <div class="col-md-3">
                                <form action="{{ route('product.edit_multiple_product_image') }}" method="POST" >
                                    @csrf
                                    <input type="hidden" name="products" value="" id="update_selected_images">
                                    <button class="btn btn-success" disabled id="update_select_product">Update Image Selected product</button>
                                </form>
                            </div>

                            <div class="col-md-2">
                                <form action="{{ route('product.delete_multiple_images') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="products" value="" id="delete_selected_images">
                                    <button class="btn btn-danger" disabled id="delete_select_product"> Delete Selected Product</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                

            </div>
        </div>
    </div>
    <!-- END: Content-->
    <!-- END: Content-->

    <script>
        function ChangeSlabStatus (id){
            $.ajax({
                url: "{{ route('changes_product_status') }}",
                type: 'POST',
                dataType: 'json',
                data: {
                    _token: "{{ csrf_token() }}",id:id
                },
                success: function(response){
                    console.log(response[0]);
                    if(response[0] == 1){
                        toastr.success(response[1]);
                    }else{
                        toastr.error(response[1]);
                    }
                }
            });
        }
    </script>

@endsection