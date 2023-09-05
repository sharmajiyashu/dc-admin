


@extends('admin.layouts.app')

@section('content')

<style>
    .error{
        color:red;
    }
    /* input {
        text-transform: uppercase;
    } */
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
                            <h2 class="content-header-title float-start mb-0">Category</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{ url('admin')}}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categories</a>
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
                                    <form class="form" action="{{ route('categories.store') }}" method="POST" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    
                                        <div class="row">
                                            <div class="col-md-12 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="first-name-column">Title <span class="error">*</span></label>
                                                    <input type="text" id="first-name-column" name="title" class="form-control" placeholder="Title" oninput="this.value = this.value.toUpperCase()"  value="{{ old('title') }}" />
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label"  for="last-name-column">Icon size(30/30) <span class="error">*</span></label>
                                                    <input type="file" name="image" class="form-control">
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label"  for="last-name-column">Status</label>
                                                    <select class="form-select" name="status" id="basicSelect">
                                                        <option value="Active" {{ (old("status") == 'Active' ? "selected":"") }}>Active</option>
                                                        <option value="Inactive" {{ (old("status") == 'Inactive' ? "selected":"") }}>Inactive</option>
                                                    </select>
                                                </div>
                                            </div>
                                            
                                            {{-- <div class="col-md-6 col-12">
                                                <div class="mb-1">
                                                    <label class="form-label" for="first-name-column">Packing Quantity</label>
                                                    <input type="text" id="first-name-column" name="packing_quantity" class="form-control" placeholder="Packing Quantity" value="{{ old('packing_quantity') }}" />
                                                </div>
                                            </div> --}}
                                            
                                            
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