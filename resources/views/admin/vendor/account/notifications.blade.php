


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
                                <li class="breadcrumb-item active"> Notifications
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
                            <h4 class="card-title">Notifications</h4>
                            <div class="form-check form-check-primary form-switch">
                                <input class="form-check-input checked_chackbox" id="systemNotification" type="checkbox" name="is_default" onclick="ChangeSlabStatus({{ $vendor->id }})" @if ($vendor->is_notify == '1')
                                    @checked(true) 
                                @endif   value="1" >
                            </div>
                        </div>
                        <div class="card-body my-25">
                            <div class="card-datatable">
                                <table class="datatables-ajax table table-responsive datatable_data">
                                    <thead>
                                        <tr>
                                            <th>Sr.no</th>
                                            <th>Image</th>
                                            <th>Title</th>
                                            <th>Body</th>
                                            <th>Created Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php  $i=1; @endphp
                                        @foreach($notifications as $key => $val)
                                        <tr>
                                            <th scope="row">{{ $i }}</th>
                                            <td><img src="{{ $val->image }}" id="account-upload-img" class="uploadedAvatar rounded me-50" alt="profile image" height="100" width="100" /><strong>{{ $val->product_name }}</strong></td>
                                            <td><strong>{{ $val->title }}</strong></td>
                                            <td>{{ $val->body }}</td>
                                            <td>{{ date('d-M-y H:i:s',strtotime($val->created_at)) }}</td>
                                            <td><a href="#"><Button class="btn btn-danger" onclick="deleteFunction({{ $val->id }})" >Delete</Button></a></td>
                                        </tr>
                                        @php $i++; @endphp
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <script>
                                function deleteFunction(id){
                                    var confirmed = confirm("Are you sure you want to delete notification?");
                                    if (confirmed) {
                                        var deleteUrl = "{{ route('delete_notifications', ':id') }}";
                                        deleteUrl = deleteUrl.replace(':id', id);
                                        window.location.href = deleteUrl;
                                    }
                                }
                            </script>
                           
                            
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
                url: "{{ route('changes_notification_status') }}",
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