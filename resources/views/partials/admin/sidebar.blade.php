        <nav class="sidebar sidebar-offcanvas" id="sidebar">
            <ul class="nav">
              <li class="nav-item">
                <div class="d-flex sidebar-profile">
                  <div class="sidebar-profile-image">
                    <img src=" {{ $loggedInUser && $loggedInUser->profile_picture ? asset('storage/profile-picture/' . $loggedInUser->profile_picture) : asset('assets/images/user-icon.png') }}" alt="image">
                    <span class="sidebar-status-indicator"></span>
                  </div>
                  <div class="sidebar-profile-name">
                    <p class="sidebar-name">
                      {{ $loggedInUser->first_name }}
                    </p>
                    <p class="sidebar-designation">
                      Admin
                    </p>
                  </div>
                </div>
              </li>


              @if($loggedInUser->role !== 'sales')
              <li class="nav-item {{ request()->route()->named('admin.dashboard') ? 'active-nav' : '' }} ">
                <a class="nav-link" href="{{ route('admin.dashboard') }}">
                    <i class="fa fa-desktop menu-icon"></i>
                    <span class="menu-title">Dashboard</span>
                </a>
              </li>
              @endif
            
 
 
            <li class="nav-item {{ request()->route()->named('admin.pos.index') ? 'active-nav' : '' }}">
              <a class="nav-link" href="{{ route('admin.pos.index') }}">
                <i class="fa fa-shopping-cart menu-icon" ></i>
                  <span class="menu-title">Point of Sale</span>
              </a>
          </li>
          
          
      
          <li class="nav-item {{ Request::is('admin/order*') ? 'active-nav' : '' }}">
            <a class="nav-link {{ Request::is('admin/order*') ? '' : 'collapsed' }}" data-toggle="collapse" href="#orders-menu" aria-expanded="{{ Request::is('admin/order*') ? 'true' : 'false' }}" aria-controls="orders-menu">
                <i class="fa fa-file menu-icon"></i>
                <span class="menu-title">Manage Orders</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse {{ Request::is('admin/order*') ? 'show' : '' }}" id="orders-menu">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.orders.index') }}">All Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.orders.index', ['filter' => 'delivery']) }}">Delivery Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.orders.index', ['filter' => 'instore']) }}">Instore Orders</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.orders.index', ['filter' => 'pending']) }}">Pending Orders</a>
                    </li>
                </ul>
            </div>
          </li>
          @if($loggedInUser->role !== 'sales')
          <li class="nav-item {{ request()->route()->named('admin.table-bookings') ? 'active-nav' : '' }}">
            <a class="nav-link" href="{{ route('admin.table-bookings') }}">
                <i class="fa fa-folder-open menu-icon"></i>
                <span class="menu-title">Manage Bookings</span>
            </a>
          </li>        
          @endif



        @if ($loggedInUser->role == "global_admin")

        <li class="nav-item {{ request()->route()->named('admin.users.index') ? 'active-nav' : '' }}">
          <a class="nav-link" href="{{ route('admin.users.index') }}">
              <i class="fa fa-users menu-icon"></i>
              <span class="menu-title">Manage Admins</span>
          </a>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" data-toggle="collapse" href="#venue-settings" aria-expanded="false" aria-controls="venue-settings">
                <i class="fa fa-campground menu-icon"></i>
                <span class="menu-title">Venue Management</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="venue-settings">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.venues.index') }}">Venues</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.venue-packages.index') }}">Packages</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.venue-bookings.index') }}">Bookings</a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link collapsed" data-toggle="collapse" href="#room-settings" aria-expanded="false" aria-controls="room-settings">
                <i class="fa fa-bed menu-icon"></i>
                <span class="menu-title">Room Management</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="room-settings">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.rooms.index') }}">Rooms</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.room-bookings.index') }}">Bookings</a>
                    </li>
                </ul>
            </div>
        </li>
              
        <li class="nav-item">
            <a class="nav-link collapsed" data-toggle="collapse" href="#stock-settings" aria-expanded="false" aria-controls="stock-settings">
                <i class="fa fa-boxes menu-icon"></i>
                <span class="menu-title">Stock Management</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="stock-settings">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.suppliers.index') }}">Suppliers</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.stock-categories.index') }}">Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.stock-items.index') }}">Stock Items</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.stock-purchases.index') }}">Purchases (In)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.stock-issues.index') }}">Issues (Out)</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.stock-history.index') }}">History</a>
                    </li>
                </ul>
            </div>
        </li>
              
        <li class="nav-item">
            <a class="nav-link collapsed" data-toggle="collapse" href="#site-settings" aria-expanded="false" aria-controls="site-settings">
                <i class="fa fa-cog menu-icon"></i>
                <span class="menu-title">Site Settings</span>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="site-settings" style="">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.menus.index') }}">Menu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.categories.index') }}">Category</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.waiters.index') }}">Waiters</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.restaurant-tables.index') }}">Restaurant Tables</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.testimonies.index') }}">Testimony</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.terms.edit') }}">Terms & Condition</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.privacy-policy.edit') }}">Privacy Policy</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.general-settings') }}">General Settings</a>
                    </li>
                </ul>
            </div>
        </li>
        @endif
    


              <li class="nav-item {{ request()->route()->named('admin.view.myprofile') ? 'active-nav' : '' }}">
                <a class="nav-link" href="{{ route('admin.view.myprofile') }}">
                  <i class="fa fa-user menu-icon"></i>
                  <span class="menu-title">My Profile</span>
                </a>
              </li>

              <li class="nav-item {{ request()->route()->named('change.password.form') ? 'active-nav' : '' }}">
                <a class="nav-link" href="{{ route('change.password.form') }}">
                  <i class="fa fa-lock menu-icon"></i>
                  <span class="menu-title">Change Password</span>
                </a>
              </li>     


              <li class="nav-item">
                <a target="_blank" class="nav-link" href="{{ route('home') }}">
                  <i class="fa fa-globe menu-icon"></i>
                  <span class="menu-title">Main Website</span>
                </a>
              </li>

              <li class="nav-item">
                <a class="nav-link" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                    <i class="fa fa-power-off menu-icon"></i>
                    <span class="menu-title">Logout</span>
                </a>
            </li>
              
            </ul>
  
          </nav>
