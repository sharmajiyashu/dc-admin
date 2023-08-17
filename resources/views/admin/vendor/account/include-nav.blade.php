<ul class="nav nav-pills mb-2">
    <!-- account -->
    <li class="nav-item">
        <a class="nav-link {{ Request::routeIs('vendors.show',$vendor->id) ? 'active' : ''  }}" href="{{ route('vendors.show',$vendor->id) }}">
            <i data-feather="user" class="font-medium-3 me-50"></i>
            <span class="fw-bold">Account</span>
        </a>
    </li>
    <!-- security -->
    <li class="nav-item">
        <a class="nav-link {{ Request::routeIs('vendors.account.products',$vendor->id) ? 'active' : ''  }}" href="{{ route('vendors.account.products',$vendor->id) }}">
            <i data-feather="lock" class="font-medium-3 me-50"></i>
            <span class="fw-bold">Products</span>
        </a>
    </li>
    <!-- billing and plans -->
    <li class="nav-item">
        <a class="nav-link {{ Request::routeIs('vendors.account.customers',$vendor->id) ? 'active' : ''  }} " href="{{ route('vendors.account.customers',$vendor->id) }}">
            <i data-feather="user" class="font-medium-3 me-50"></i>
            <span class="fw-bold">Customers</span>
        </a>
    </li>
    <!-- notification -->
    <li class="nav-item">
        <a class="nav-link {{ Request::routeIs('vendors.account.orders',$vendor->id) ? 'active' : ''  }}" href="{{ route('vendors.account.orders',$vendor->id) }}">
            <i data-feather="shopping-cart" class="font-medium-3 me-50"></i>
            <span class="fw-bold">Orders</span>
        </a>
    </li>
    
    <li class="nav-item">
        <a class="nav-link {{ Request::routeIs('vendors.account.wishlist',$vendor->id) ? 'active' : ''  }}" href="{{ route('vendors.account.wishlist',$vendor->id) }}">
            <i data-feather="shopping-cart" class="font-medium-3 me-50"></i>
            <span class="fw-bold">Wishlist</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ Request::routeIs('vendors.account.notifications',$vendor->id) ? 'active' : ''  }}" href="{{ route('vendors.account.notifications',$vendor->id) }}">
            <i data-feather="bell" class="font-medium-3 me-50"></i>
            <span class="fw-bold">Notifications</span>
        </a>
    </li>

</ul>