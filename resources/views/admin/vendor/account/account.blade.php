


@extends('admin.layouts.app')

@section('content')
<style>
    .error{
        color:red;
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
                                <li class="breadcrumb-item"><a href="#">Account Settings </a>
                                </li>
                                <li class="breadcrumb-item active"> Account
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
                            <h4 class="card-title">Vendor Details</h4>
                        </div>
                        <div class="card-body py-2 my-25">
                            <!-- header section -->
                            <div class="d-flex">
                                <a href="#" class="me-25">
                                    <img src="{{ asset('public/images/users/'.$vendor->image) }}" id="account-upload-img" class="uploadedAvatar rounded me-50" alt="profile image" height="100" width="100" />
                                </a>
                                <!-- upload and reset button -->
                                {{-- <div class="d-flex align-items-end mt-75 ms-1">
                                    <div>
                                        <label for="account-upload" class="btn btn-sm btn-primary mb-75 me-75">Upload</label>
                                        <input type="file" id="account-upload" hidden accept="image/*" />
                                        <button type="button" id="account-reset" class="btn btn-sm btn-outline-secondary mb-75">Reset</button>
                                        <p class="mb-0">Allowed file types: png, jpg, jpeg.</p>
                                    </div>
                                </div> --}}
                                <!--/ upload and reset button -->
                            </div>
                            <!--/ header section -->

                            <!-- form -->
                            <form class="validate-form mt-2 pt-50" action="{{ route('customers.update',$vendor->id) }}" method="POST"  enctype="multipart/form-data">
                                {{ csrf_field() }}
                                @method('PATCH')
                                <div class="row">

                                    <div class="col-md-6 col-12">
                                        <div class="mb-1">
                                            <label class="form-label"  for="last-name-column">Update Profile<span class="error">*</span></label>
                                            <input type="file" name="image" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-6 mb-1">
                                        <label class="form-label" for="accountFirstName">Name</label>
                                        <input type="text" class="form-control" id="accountFirstName" name="name" placeholder="Name" value="{{ $vendor->name }}" data-msg="Please enter name" />
                                    </div>
                                    <div class="col-12 col-sm-6 mb-1">
                                        <label class="form-label" for="accountLastName">Store Code</label>
                                        <input type="text" readonly class="form-control" id="accountLastName" name="" placeholder="Store Code" value="{{ $vendor->store_code }}" data-msg="Please enter last name" />
                                    </div>
                                    <div class="col-12 col-sm-6 mb-1">
                                        <label for="currency" class="form-label">Gender</label>
                                        <select id="currency" name="gender" class="select2 form-select">
                                            <option value="">Select Gender</option>
                                            <option value="Male" {{ (isset($vendor) && $vendor->gender == 'Male') ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ (isset($vendor) && $vendor->gender == 'Female') ? 'selected' : '' }} >Female</option>
                                            <option value="Other" {{ (isset($vendor) && $vendor->gender == 'Other') ? 'selected' : '' }} >Other</option>
                                        </select>
                                    </div>

                                    <div class="col-12 col-sm-6 mb-1">
                                        <label class="form-label" for="accountOrganization">DOB</label>
                                        <input type="date" class="form-control" id="accountOrganization" name="dob" placeholder="dob" value="{{ $vendor->dob }}" />
                                    </div>
                                    <div class="col-12 col-sm-6 mb-1">
                                        <label class="form-label" for="accountPhoneNumber">Mobile</label>
                                        <input type="number" readonly class="form-control account-number-mask" id="accountPhoneNumber" name="mobile" placeholder="Mobile" value="{{ $vendor->mobile }}" />
                                    </div>
                                    <div class="col-12 col-sm-6 mb-1">
                                        <label class="form-label" for="accountAddress">State</label>
                                        <input type="text" class="form-control" id="accountAddress" name="state" placeholder="State" value="{{ $vendor->state }}" />
                                    </div>
                                    <div class="col-12 col-sm-6 mb-1">
                                        <label class="form-label" for="accountState">City</label>
                                        <input type="text" class="form-control" id="accountState" name="city" placeholder="City" value="{{ $vendor->city }}" />
                                    </div>
                                    <div class="col-12 col-sm-6 mb-1">
                                        <label class="form-label" for="accountState">Pin</label>
                                        <input type="text" class="form-control" id="accountState" name="pin" placeholder="Pin" value="{{ $vendor->pin }}" />
                                    </div>
                                    <div class="col-12 col-sm-12 mb-1">
                                        <label class="form-label" for="accountZipCode">Address</label>
                                        <textarea name="address" class="form-control" id="" cols="30" rows="3">{{ $vendor->address }}</textarea>
                                    </div>
                                    
                                    
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary mt-1 me-1">Save changes</button>
                                        <button type="reset" class="btn btn-outline-secondary mt-1">Discard</button>
                                    </div>
                                </div>
                            </form>
                            <!--/ form -->
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</div>
    <!-- END: Content-->
@endsection