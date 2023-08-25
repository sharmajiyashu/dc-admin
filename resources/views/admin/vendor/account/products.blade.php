


@extends('admin.layouts.app')

@section('content')
<style>
    .error{
        color:red;
    }
    
</style>
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
 <div class="app-content content ">
    <div class="content-overlay"></div>
    <div class="header-navbar-shadow"></div>
    <div class="content-wrapper container-xxl p-0">
        <div class="content-header row">
            <div class="content-header-left col-md-9 col-12 mb-2">
                <div class="row breadcrumbs-top">
                    <div class="col-12">
                        <h2 class="content-header-title float-start mb-0">Account</h2>
                        <div class="breadcrumb-wrapper">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('/') }}">Home</a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{ route('vendors.index') }}">Vendors </a>
                                </li>
                                <li class="breadcrumb-item"><a href="#">{{ $vendor->name }}</a>
                                </li>
                                <li class="breadcrumb-item active"> Products
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
        <div class="content-body">
            <div class="row">
                <div class="col-12">
                    @include('admin.vendor.account.include-nav')

                    <!-- profile -->
                    <div class="card">
                        <div class="card-header border-bottom">
                            <h4 class="card-title">Product Details</h4>
                        </div>
                        <div class="card-body my-25">
                            <div class="card-datatable">
                                <table class="datatables-ajax table table-responsive datatable_data">
                                    <thead>
                                        <tr>
                                            <th>Sr.no</th>
                                            <th>Image</th>
                                            <th> Name</th>
                                            <th>Category</th>
                                            <th>MRP</th>
                                            <th>Price</th>
                                            <th>stock</th>
                                            <th>packing quantity</th>
                                            <th>order limit</th>
                                            <th>unit</th>
                                            <th>status</th>
                                            <th>Created Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php  $i=1; @endphp
                                        @foreach($products as $key => $val)
                                        <tr>
                                            <th scope="row">{{ $i }}</th>
                                            <td><img src="{{ asset('public/images/products/'.$val->image) }}" alt="" width="100"></td>
                                            <td>
                                                <a href="#" class="text-primary" data-bs-toggle="modal" data-bs-target="#danger_k{{ $val->id }}"><strong>{{ $val->name }}</strong></a>
                                                <div class="modal fade modal-dark text-start" id="danger_k{{ $val->id }}" tabindex="-1" aria-labelledby="myModalLabel120" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered modal-lg">
                                                        
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h3 class="modal-title" id="myModalLabel120">Product Detail</h3>
                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                </div>
                                                                
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <div class="modal-body">
                                                                            <ul class="list-unstyled">
                                                                                <li class="mb-75">
                                                                                    <span class="fw-bolder me-25">Product Name :</span>
                                                                                    <span class="text-bold">{{ $val->name }}</span>
                                                                                </li>

                                                                                <li class="mb-75">
                                                                                    <span class="fw-bolder me-25">Status :</span>
                                                                                    <span class="text-bold">{{ $val->status }}</span>
                                                                                </li>

                                                                                <li class="mb-75">
                                                                                    <span class="fw-bolder me-25">MRP :</span>
                                                                                    <span class="text-bold">{{ $val->mrp }}</span>
                                                                                </li>

                                                                                <li class="mb-75">
                                                                                    <span class="fw-bolder me-25">Order Limit :</span>
                                                                                    <span class="text-bold">{{ $val->order_limit }}</span>
                                                                                </li>

                                                                                <li class="mb-75">
                                                                                    <span class="fw-bolder me-25">Packing Quantity :</span>
                                                                                    <span class="text-bold">{{ $val->packing_quantity }}</span>
                                                                                </li>
                                                                    
        
                                                                                
                                                                              </ul>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <div class="modal-body">
                                                                            <ul class="list-unstyled">
                                                                                <li class="mb-75">
                                                                                    <span class="fw-bolder me-25">Category Name :</span>
                                                                                    <span class="text-bold">{{ $val->category_name }}</span>
                                                                                </li>

                                                                                <li class="mb-75">
                                                                                    <span class="fw-bolder me-25">Stock :</span>
                                                                                    <span class="text-bold">{{ $val->stock }}</span>
                                                                                </li>

                                                                                <li class="mb-75">
                                                                                    <span class="fw-bolder me-25">SP :</span>
                                                                                    <span class="text-bold">{{ $val->sp }}</span>
                                                                                </li>
                                                                    
                                                                                <li class="mb-75">
                                                                                    <span class="fw-bolder me-25">Unit :</span>
                                                                                    <span class="text-bold">{{ $val->unit }}</span>
                                                                                </li>

                                                                                <li class="mb-75">
                                                                                    <span class="fw-bolder me-25">Quantity :</span>
                                                                                    <span class="text-bold">{{ $val->quantity }}</span>
                                                                                </li>
                                                                                
                                                                              </ul>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-md-12">
                                                                        <div class="modal-body">
                                                                        <ul class="list-unstyled">
                                                                            <li class="mb-75">
                                                                                <span class="fw-bolder me-25">Description :</span>
                                                                                <span>{{ $val->detail }}</span>
                                                                            </li>
                                                                            <li class="mb-75">
                                                                                <span class="fw-bolder me-25">Images :</span>
                                                                                <div class="row">
                                                                                    @if (!empty($val->images))
                                                                                        @php
                                                                                            $products = $val->images;
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
                                                    </div>
                                                </div>
                                            
                                            
                                            
                                            </td>
                                            <td>{{ isset($val->category_name)  ? $val->category_name :'' }}</td>

                                            <td>{{ $val->mrp }}</td>
                                            <td>{{ $val->sp }}</td>
                                            <td>{{ $val->stock }}</td>
                                            <td>{{ $val->packing_quantity }}</td>
                                            <td>{{ $val->order_limit }}</td>
                                            <td>{{ $val->unit }}</td>
                                            <td ><div class="form-check form-check-primary form-switch">
                                                <input class="form-check-input checked_chackbox" id="systemNotification" type="checkbox" name="is_default" onclick="ChangeSlabStatus({{ $val->id }})" @if ($val->status == 'Active')
                                                    @checked(true) 
                                                @endif   value="1" >
                                            </div></td>
                                            <td>{{ date('d-M-y H:i:s',strtotime($val->created_at)) }}</td>
                                            <td>
                                                <a  href="{{route('products.edit_2',$val->id)}}">
                                                    <button class="btn btn-info">Edit</button>
                                                </a>
                                            </td>
                                        </tr>
                                        @php $i++; @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
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