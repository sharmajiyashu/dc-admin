


@extends('admin.layouts.app')

@section('content')
<style>
    .error{
        color:red;
    }
</style>
<style>
    .accept{
        color: green;
        font-weight: 900;
        text-transform: uppercase;
    }
    .accept{
        color: green;
        font-weight: 900;
        text-transform: uppercase;
    }
    .reject{
        color: red;
        font-weight: 900;
        text-transform: uppercase;
    }
    .pending{
        color: orange;
        font-weight: 900;
        text-transform: uppercase;
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
                                <li class="breadcrumb-item active"> Wishlist
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
                            <h4 class="card-title">Wishlist Details</h4>
                        </div>
                        <div class="card-body my-25">
                            <div class="card-datatable">
                                <table class="datatables-ajax table table-responsive datatable_data">
                                    <thead>
                                        <tr>
                                            <th>Sr.no</th>
                                            <th>Name</th>
                                            <th>Days</th>
                                            <th>Total Customers</th>
                                            <th>Total Products</th>
                                            <th>Status</th>
                                            <th>Created Date</th>
                                        </tr>
                                    </thead>

                                    <script>
                                        function ChangeSlabStatus (id){
                                            $.ajax({
                                                url: "{{ route('changes_slab_status') }}",
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

                                    <tbody>
                                        @php  $i=1; @endphp
                                        @foreach($slabs as $key => $val)
                                        <tr>
                                            <th scope="row">{{ $i }}</th>
                                            <td><strong>{{ $val->name }}</strong></td>
                                            <td>{{ $val->days }}</td>
                                            <td>{{ $val->total_customers }}</td>
                                            <td>{{ $val->total_products }}</td>
                                            <td>
                                                <div class="form-check form-check-primary form-switch">
                                                    <input class="form-check-input checked_chackbox" id="systemNotification" type="checkbox" name="is_default" onclick="ChangeSlabStatus({{ $val->id }})" @if ($val->status == 1)
                                                        @checked(true) 
                                                    @endif   value="1" >
                                                </div>
                                            </td>
                                            <td>{{ date('d-M-y H:i:s',strtotime($val->created_at)) }}</td>
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
@endsection