


<!-- BEGIN: Main Menu-->
<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="navbar-header">
        <ul class="nav navbar-nav flex-row">
            <li class="nav-item me-auto"><a class="navbar-brand" href="{{ route('/') }}">
                    <h2 class="brand-text">Jewellery Dukan</h2>
                </a></li>
            <li class="nav-item nav-toggle"><a class="nav-link modern-nav-toggle pe-0" data-bs-toggle="collapse"><i class="d-block d-xl-none text-primary toggle-icon font-medium-4" data-feather="x"></i><i class="d-none d-xl-block collapse-toggle-icon font-medium-4  text-primary" data-feather="disc" data-ticon="disc"></i></a></li>
        </ul>
    </div>
    <div class="shadow-bottom"></div>
    <div class="main-menu-content">
        <ul class="navigation navigation-main" id="main-menu-navigation" data-menu="menu-navigation">
            <li class=" nav-item {{ \Request::is('/') ? 'active' : ''  }}"><a class="d-flex align-items-center" href="{{ url('/') }}"><i data-feather="home"></i><span class="menu-title text-truncate" data-i18n="Dashboards">Dashboards</span><span class="badge badge-light-warning rounded-pill ms-auto me-1"></span></a>
                
            </li>
            
            
            <li class="nav-item {{ Request::routeIs('categories.index','categories.create','categories.edit','categories.show') ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('categories.index') }}"><i data-feather="file-text"></i><span class="menu-title text-truncate" data-i18n="Invoice">Category</span></a>
                
            </li>

            <li class="nav-item {{ Request::routeIs('products.index','products.create','products.edit','products.show') ? 'active' : '' }} "><a class="d-flex align-items-center" href="{{ route('products.index') }}"><i data-feather="file-text"></i><span class="menu-title text-truncate" data-i18n="Invoice">Product</span></a>
                
            </li>

            <li class="nav-item {{ Request::routeIs('vendors.index','vendors.create','vendors.edit','vendors.show') ? 'active' : '' }}"><a class="d-flex align-items-center  " href="{{ route('vendors.index') }}"><i data-feather="user"></i><span class="menu-title text-truncate" data-i18n="File Manager">Vendor</span></a>
            </li>

            <li class="nav-item  {{ Request::routeIs('customers.index','customers.create','customers.edit','customers.show') ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('customers.index') }}"><i data-feather="user"></i><span class="menu-title text-truncate" data-i18n="File Manager">Customer</span></a>
            </li>

            <li class="nav-item {{ Request::routeIs('orders.index','orders.create','orders.edit','orders.show') ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('orders.index') }}"><i data-feather="shopping-cart"></i><span class="menu-title text-truncate" data-i18n="File Manager">Orders</span></a>
            </li>
            
            <li class="nav-item {{ Request::routeIs('notifications.index','notifications.create','notifications.edit','notifications.show') ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('notifications.index') }}"><i data-feather="shopping-cart"></i><span class="menu-title text-truncate" data-i18n="File Manager">Notification</span></a>
            </li>

            <li class="nav-item {{ Request::routeIs('demo_products.index','demo_products.create','demo_products.edit','demo_products.show') ? 'active' : '' }}"><a class="d-flex align-items-center" href="{{ route('demo_products.index') }}"><i data-feather="shopping-cart"></i><span class="menu-title text-truncate" data-i18n="File Manager">Demo Products</span></a>
            </li>
            
            
            
            
        </ul>
    </div>
</div>
<!-- END: Main Menu-->