<ul class="nav nav-pills mb-2">
    <!-- account -->
    <li class="nav-item">
        <a class="nav-link {{ Request::routeIs('customers.show',$customer->id) ? 'active' : ''  }}" href="{{ route('customers.show',$customer->id) }}">
            <i data-feather="user" class="font-medium-3 me-50"></i>
            <span class="fw-bold">Account</span>
        </a>
    </li>
    <!-- security -->
    
    <!-- billing and plans -->
    <li class="nav-item">
        <a class="nav-link {{ Request::routeIs('customers.account.stores',$customer->id) ? 'active' : ''  }}" href="{{ route('customers.account.stores',$customer->id) }}">
            <i data-feather="user" class="font-medium-3 me-50"></i>
            <span class="fw-bold">Stores</span>
        </a>
    </li>
    <!-- notification -->
    <li class="nav-item">
        <a class="nav-link {{ Request::routeIs('customers.account.orders',$customer->id) ? 'active' : ''  }}" href="{{ route('customers.account.orders',$customer->id) }}">
            <i data-feather="shopping-cart" class="font-medium-3 me-50"></i>
            <span class="fw-bold">Orders</span>
        </a>
    </li>

    <li class="nav-item ">
        <a class="nav-link {{ Request::routeIs('customers.account.wishlist',$customer->id) ? 'active' : ''  }}" href="{{ route('customers.account.wishlist',$customer->id) }}">
            <i data-feather="shopping-cart" class="font-medium-3 me-50"></i>
            <span class="fw-bold">Wishlist</span>
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ Request::routeIs('customers.account.carts',$customer->id) ? 'active' : ''  }}" href="{{ route('customers.account.carts',$customer->id) }}">
            <i data-feather="shopping-cart" class="font-medium-3 me-50"></i>
            <span class="fw-bold">Carts</span>
        </a>
    </li>
    
    <li class="nav-item">
        <a class="nav-link {{ Request::routeIs('customers.account.notifications',$customer->id) ? 'active' : ''  }}" href="{{ route('customers.account.notifications',$customer->id) }}">
            <i data-feather="bell" class="font-medium-3 me-50"></i>
            <span class="fw-bold">Notifications</span>
        </a>
    </li>
</ul>