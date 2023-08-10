
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
                                <div class="card-header border-bottom">
                                    <h4 class="card-title">List</h4>
                                    <a href="{{route('products.create')}}" class=" btn btn-info btn-gradient round  ">Add Product</a>
                                </div>
                                <div class="card-datatable">
                                    <table class="datatables-ajax table table-responsive datatable_data">
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
                                                <th scope="row">{{ $i }}</th>
                                                <td><img src="{{ asset('public/images/products/'.$val->image) }}" alt="" width="100"></td>
                                                <td>
                                                    

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
                                                <td class="{{$val->status}} text-bold">{{ $val->status }}</td>
                                                <td>{{ date('d-M-y H:i:s',strtotime($val->created_at)) }}</td>
                                                <td>
                                                    <a  href="{{route('products.edit',$val->id)}}">
                                                        <button class="btn btn-primary">Edit</button>
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