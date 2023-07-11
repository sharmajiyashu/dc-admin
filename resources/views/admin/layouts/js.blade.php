<!-- BEGIN: Vendor JS-->
<script src="{{ asset('public/admin/app-assets/vendors/js/vendors.min.js')}}"></script>
<!-- BEGIN Vendor JS-->

<!-- BEGIN: Page Vendor JS-->
<script src="{{ asset('public/admin/app-assets/vendors/js/charts/apexcharts.min.js')}}"></script>
<script src="{{ asset('public/admin/app-assets/vendors/js/extensions/toastr.min.js')}}"></script>
<!-- END: Page Vendor JS-->



<!-- BEGIN: Theme JS-->
<script src="{{ asset('public/admin/app-assets/js/core/app-menu.js')}}"></script>
<script src="{{ asset('public/admin/app-assets/js/core/app.js')}}"></script>
<!-- END: Theme JS-->

<!-- BEGIN: Page JS-->
<script src="{{ asset('public/admin/app-assets/js/scripts/pages/dashboard-ecommerce.js')}}"></script>
<!-- END: Page JS-->

<script>
    $(window).on('load', function() {
        if (feather) {
            feather.replace({
                width: 14,
                height: 14
            });
        }
    })
</script>


{{-- <script src="{{ asset('public/admin/asset/datatable/jquery.js') }}"></script> --}}
{{-- <script src="https://cdn.datatables.net/1.10.25/js/jquery.dataTables.min.js"></script> --}}

<script src="{{ asset('public/admin/assets/datatable/dataTables.min.js') }}"></script>
<script>
    $(document).ready(function() {
    $('.datatable_data').DataTable( {

    });
});
</script>

<script>
    $(document).ready(function() {
        
        toastr.options.timeOut = 10000;
        @if (Session::has('error'))
            toastr.error('{{ Session::get('error') }}');
            
        @elseif(Session::has('success'))
            toastr.success('{{ Session::get('success') }}');
        @endif

    });
</script>
