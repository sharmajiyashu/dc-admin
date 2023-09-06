
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
                            <h2 class="content-header-title float-start mb-0">Category</h2>
                            <div class="breadcrumb-wrapper">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="{{url('/')}}">Home</a>
                                    </li>
                                    <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Categories</a>
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
                                    <a href="{{route('categories.create')}}" class=" btn btn-primary btn-gradient round  ">Add Category</a>
                                </div>
                                <div class="card-datatable">
                                    <table class="datatables-ajax table table-responsive ">
                                        <thead>
                                            <tr>
                                                <th>Sr.no</th>
                                                <th>icon</th>
                                                <th>title</th>
                                                <th>status</th>
                                                {{-- <th>packing quantity</th> --}}
                                                <th>Created Date</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php  $i=1; @endphp
                                            @foreach($categories as $key => $val)
                                            <tr>
                                                <th scope="row">{{ $i }}</th>
                                                <td><img src="{{ asset('public/images/categories/'.$val->image) }}" alt="" width="100"></td>
                                                <td><strong><a href="{{ route('categories.show',$val->id) }}">{{ $val->title }}</a></strong></td>
                                                <td>
                                                    <div class="form-check form-check-primary form-switch">
                                                        <input class="form-check-input checked_chackbox" id="systemNotification" type="checkbox" name="is_default" onclick="ChangeSlabStatus({{ $val->id }})" @if ($val->status == 'Active')
                                                            @checked(true) 
                                                        @endif   value="1" >
                                                    </div>
                                                </td>
                                                <td>{{ date('d-M-y H:i:s',strtotime($val->created_at)) }}</td>
                                                <td>
                                                    <a  href="{{route('categories.edit',$val->id)}}">
                                                        <button class="btn btn-info">Edit</button>
                                                    </a>

                                                    <a href="#" class="text-danger" data-bs-toggle="modal" data-bs-target="#danger_ke{{ $val->id }}"><button class="btn btn-danger">Delete</button></a>

                                                    <!-- Modal -->
                                                    <div class="modal fade modal-danger text-start" id="danger_ke{{ $val->id }}" tabindex="-1" aria-labelledby="myModalLabel120" aria-hidden="true">
                                                        <div class="modal-dialog modal-dialog-centered">
                                                            
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="myModalLabel120">Delete Category</h5>
                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        @if ($val->total_products < 1)
                                                                            Are you sure you want to delete !
                                                                        @else
                                                                            This Category still have some products. Please delete products first
                                                                        @endif
                                                                        
                                                                    </div>
                                                                    <form action="{{route('categories.destroy',$val->id)}}" method="POST">
                                                                        @csrf
                                                                        @method('delete')
                                                                        <div class="modal-footer">
                                                                            <button type="submit" class="btn btn-danger" @if ($val->total_products > 0) @disabled(true) @endif>Delete</button>
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
                                    
                                    <nav aria-label="Page navigation test-end">
                                        <ul class="pagination pagination-danger justify-content-end mt-2">
                                            
                                            @if ($categories->onFirstPage())
                                                <li class="page-item disabled"><span class="page-link" aria-hidden="true">&laquo;</span></li>
                                                <li class="page-item active disabled"><a class="page-link" href="{{ $categories->url(1) }}">1</a></li>
                                            @else
                                            <li class="page-item prev">
                                                <a class="page-link" href="{{ $categories->previousPageUrl() }}" aria-label="Previous"></a>
                                            </li>
                                                <li class="page-item"><a class="page-link" href="{{ $categories->url(1) }}">1</a></li>
                                            @endif
                                    
                                            @foreach ($categories->getUrlRange(2, $categories->lastPage() - 1) as $page => $url)
                                                <li class="page-item @if ($page == $categories->currentPage()) active disabled @endif">
                                                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                                </li>
                                            @endforeach
                                    
                                            @if ($categories->hasMorePages())
                                                <li class="page-item"><a class="page-link" href="{{ $categories->url($categories->lastPage()) }}">{{ $categories->lastPage() }}</a></li>
                                                <li class="page-item next">
                                                    <a class="page-link" href="{{ $categories->nextPageUrl() }}" aria-label="Next"></a>
                                                </li>
                                            @else
                                                <li class="page-item active disabled"><a class="page-link" href="{{ $categories->url($categories->lastPage()) }}">{{ $categories->lastPage() }}</a></li>
                                                <li class="page-item disabled"><span class="page-link" aria-hidden="true">&raquo;</span></li>
                                            @endif
                                        </ul>
                                    </nav>

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

    <script>
        function ChangeSlabStatus (id){
            $.ajax({
                url: "{{ route('changes_category_status') }}",
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