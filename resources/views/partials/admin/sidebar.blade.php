@php
    $loggedInUser = $loggedInUser ?? auth()->user();
@endphp

<style>
    /* =============================================
       Admin Sidebar — All The Season Garden
    ============================================= */

    .sidebar-offcanvas {
        width: 248px;
        min-width: 248px;
        background: #0f1117;
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        position: sticky;
        top: 0;
        height: 100vh;
        overflow-y: auto;
        overflow-x: hidden;
        border-right: 1px solid rgba(255,255,255,0.06);
        z-index: 1040;
        scrollbar-width: thin;
        scrollbar-color: #1e2330 transparent;
    }

    .sidebar-offcanvas::-webkit-scrollbar {
        width: 3px;
    }
    .sidebar-offcanvas::-webkit-scrollbar-thumb {
        background: #1e2330;
        border-radius: 3px;
    }

    /* ── Brand ── */
    .sb-brand {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 16px 18px;
        border-bottom: 1px solid rgba(255,255,255,0.06);
    }
    .sb-brand-inner {
        display: flex;
        align-items: center;
        gap: 9px;
        text-decoration: none;
    }
    .sb-brand-icon {
        width: 30px;
        height: 30px;
        border-radius: 7px;
        background: #dc2626;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .sb-brand-icon i {
        color: #fff;
        font-size: 13px;
    }
    .sb-brand-text {
        line-height: 1.25;
    }
    .sb-brand-name {
        font-size: 13px;
        font-weight: 700;
        color: #f9fafb;
        margin: 0;
        white-space: nowrap;
    }
    .sb-brand-sub {
        font-size: 10.5px;
        color: #4b5563;
        margin: 0;
    }
    .sb-close {
        background: transparent;
        border: none;
        color: #6b7280;
        font-size: 16px;
        cursor: pointer;
        padding: 2px 4px;
        line-height: 1;
        transition: color 0.15s;
    }
    .sb-close:hover {
        color: #d1d5db;
    }

    /* ── Nav Sections ── */
    .sb-nav {
        flex: 1;
        padding: 8px 0 12px;
        list-style: none;
        margin: 0;
    }
    .sb-section-label {
        font-size: 10px;
        font-weight: 600;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #374151;
        padding: 16px 18px 4px;
        user-select: none;
    }
    .sb-section-label:first-child {
        padding-top: 8px;
    }

    /* ── Nav Items ── */
    .sb-item {
        padding: 1px 8px;
    }
    .sb-link {
        display: flex;
        align-items: center;
        gap: 9px;
        padding: 7px 10px;
        border-radius: 7px;
        text-decoration: none !important;
        color: #9ca3af !important;
        font-size: 13px;
        font-weight: 500;
        transition: background 0.12s, color 0.12s;
        position: relative;
    }
    .sb-link .sb-icon {
        width: 16px;
        text-align: center;
        font-size: 13px;
        color: #4b5563;
        flex-shrink: 0;
        transition: color 0.12s;
    }
    .sb-link .sb-arrow {
        margin-left: auto;
        font-size: 9px;
        color: #374151;
        transition: transform 0.18s ease, color 0.12s;
    }
    .sb-link:hover {
        background: rgba(255,255,255,0.05);
        color: #e5e7eb !important;
    }
    .sb-link:hover .sb-icon {
        color: #9ca3af;
    }
    .sb-item.is-active > .sb-link {
        background: rgba(220, 38, 38, 0.1);
        color: #fff !important;
        font-weight: 600;
    }
    .sb-item.is-active > .sb-link .sb-icon {
        color: #ef4444;
    }
    .sb-link[aria-expanded="true"] .sb-arrow {
        transform: rotate(90deg);
        color: #9ca3af;
    }

    /* ── Submenu ── */
    .sb-submenu {
        list-style: none;
        margin: 2px 0 4px 0;
        padding: 0 0 0 34px;
    }
    .sb-submenu .sb-sub-item {
        padding: 1px 0;
    }
    .sb-submenu .sb-sub-link {
        display: block;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 12.5px;
        color: #6b7280;
        text-decoration: none;
        transition: background 0.12s, color 0.12s;
    }
    .sb-submenu .sb-sub-link:hover {
        background: rgba(255,255,255,0.04);
        color: #d1d5db;
    }
    .sb-submenu .sb-sub-link.is-active-sub {
        color: #ef4444;
        font-weight: 600;
    }

    /* ── User Footer ── */
    .sb-footer {
        border-top: 1px solid rgba(255,255,255,0.06);
        padding: 12px;
        background: #0f1117;
    }
    .sb-user {
        display: flex;
        align-items: center;
        gap: 9px;
        padding: 8px 6px;
        border-radius: 8px;
        margin-bottom: 8px;
        transition: background 0.12s;
    }
    .sb-user:hover {
        background: rgba(255,255,255,0.04);
    }
    .sb-avatar {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        object-fit: cover;
        border: 1.5px solid #1f2937;
        flex-shrink: 0;
    }
    .sb-user-info {
        flex: 1;
        min-width: 0;
    }
    .sb-user-name {
        font-size: 13px;
        font-weight: 600;
        color: #f3f4f6;
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .sb-user-role {
        font-size: 11px;
        color: #6b7280;
        margin: 0;
        white-space: nowrap;
    }
    .sb-footer-actions {
        display: flex;
        gap: 6px;
    }
    .sb-action-btn {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        padding: 6px 10px;
        border-radius: 6px;
        font-size: 11.5px;
        font-weight: 500;
        text-decoration: none !important;
        transition: background 0.12s, color 0.12s;
        cursor: pointer;
        border: none;
    }
    .sb-btn-site {
        background: #1a1f2e;
        color: #9ca3af !important;
        border: 1px solid #1f2937;
    }
    .sb-btn-site:hover {
        background: #1f2937;
        color: #e5e7eb !important;
    }
    .sb-btn-logout {
        background: rgba(220, 38, 38, 0.08);
        color: #f87171 !important;
        border: 1px solid rgba(220, 38, 38, 0.15);
    }
    .sb-btn-logout:hover {
        background: #dc2626;
        color: #fff !important;
        border-color: #dc2626;
    }

    /* ── Mobile ── */
    @media (max-width: 991.98px) {
        .sidebar-offcanvas {
            position: fixed;
            left: -260px;
            top: 0;
            bottom: 0;
            transition: left 0.24s ease;
            box-shadow: none;
        }
        .sidebar-offcanvas.mobile-open {
            left: 0;
            box-shadow: 6px 0 30px rgba(0,0,0,0.5);
        }
    }
</style>

<nav class="sidebar sidebar-offcanvas" id="sidebar">

    {{-- Brand --}}
    <div class="sb-brand">
        <a href="{{ route('admin.dashboard') }}" class="sb-brand-inner">
            <img src="/favicon_io/android-chrome-192x192.png" alt="All Season Garden" style="width: 32px; height: 32px; border-radius: 8px; object-fit: cover; flex-shrink: 0;">
            <div class="sb-brand-text">
                <p class="sb-brand-name">All Season Garden</p>
                <p class="sb-brand-sub">Restaurant Admin</p>
            </div>
        </a>
        <button class="sb-close d-lg-none" id="closeSidebarBtn">
            <i class="fas fa-times"></i>
        </button>
    </div>

    {{-- Navigation --}}
    <ul class="sb-nav">

        {{-- MAIN --}}
        <li class="sb-section-label">Main</li>

        @if($loggedInUser->role !== 'sales')
        <li class="sb-item {{ request()->route()->named('admin.dashboard') ? 'is-active' : '' }}">
            <a class="sb-link" href="{{ route('admin.dashboard') }}">
                <i class="fas fa-th-large sb-icon"></i>
                <span>Dashboard</span>
            </a>
        </li>
        @endif

        <li class="sb-item {{ request()->route()->named('admin.pos.index') ? 'is-active' : '' }}">
            <a class="sb-link" href="{{ route('admin.pos.index') }}">
                <i class="fas fa-cash-register sb-icon"></i>
                <span>Point of Sale</span>
            </a>
        </li>

        {{-- OPERATIONS --}}
        <li class="sb-section-label">Operations</li>

        <li class="sb-item {{ Request::is('admin/order*') ? 'is-active' : '' }}">
            <a class="sb-link {{ Request::is('admin/order*') ? '' : 'collapsed' }}"
               data-bs-toggle="collapse" href="#m-orders"
               aria-expanded="{{ Request::is('admin/order*') ? 'true' : 'false' }}">
                <i class="fas fa-receipt sb-icon"></i>
                <span>Orders</span>
                <i class="fas fa-chevron-right sb-arrow"></i>
            </a>
            <div class="collapse {{ Request::is('admin/order*') ? 'show' : '' }}" id="m-orders">
                <ul class="sb-submenu">
                    <li class="sb-sub-item">
                        <a class="sb-sub-link {{ request()->fullUrl() == route('admin.orders.index') ? 'is-active-sub' : '' }}"
                           href="{{ route('admin.orders.index') }}">All Orders</a>
                    </li>
                    <li class="sb-sub-item">
                        <a class="sb-sub-link {{ request('filter') == 'delivery' ? 'is-active-sub' : '' }}"
                           href="{{ route('admin.orders.index', ['filter' => 'delivery']) }}">Delivery</a>
                    </li>
                    <li class="sb-sub-item">
                        <a class="sb-sub-link {{ request('filter') == 'instore' ? 'is-active-sub' : '' }}"
                           href="{{ route('admin.orders.index', ['filter' => 'instore']) }}">Dine-in</a>
                    </li>
                    <li class="sb-sub-item">
                        <a class="sb-sub-link {{ request('filter') == 'pending' ? 'is-active-sub' : '' }}"
                           href="{{ route('admin.orders.index', ['filter' => 'pending']) }}">Pending</a>
                    </li>
                </ul>
            </div>
        </li>

        @if($loggedInUser->role !== 'sales')
        <li class="sb-item {{ request()->route()->named('admin.table-bookings') ? 'is-active' : '' }}">
            <a class="sb-link" href="{{ route('admin.table-bookings') }}">
                <i class="fas fa-calendar-check sb-icon"></i>
                <span>Table Bookings</span>
            </a>
        </li>
        @endif

        {{-- KITCHEN --}}
        <li class="sb-section-label">Kitchen</li>

        <li class="sb-item {{ Request::is('admin/kitchen*') ? 'is-active' : '' }}">
            <a class="sb-link {{ Request::is('admin/kitchen*') ? '' : 'collapsed' }}"
               data-bs-toggle="collapse" href="#m-kitchen"
               aria-expanded="{{ Request::is('admin/kitchen*') ? 'true' : 'false' }}">
                <i class="fas fa-utensils sb-icon"></i>
                <span>Kitchen</span>
                <i class="fas fa-chevron-right sb-arrow"></i>
            </a>
            <div class="collapse {{ Request::is('admin/kitchen*') ? 'show' : '' }}" id="m-kitchen">
                <ul class="sb-submenu">
                    <li class="sb-sub-item">
                        <a class="sb-sub-link {{ request()->route()->named('admin.kitchen.kot') ? 'is-active-sub' : '' }}"
                           href="{{ route('admin.kitchen.kot') }}">Live KOT</a>
                    </li>
                    <li class="sb-sub-item">
                        <a class="sb-sub-link {{ request()->route()->named('admin.kitchen.ingredients') ? 'is-active-sub' : '' }}"
                           href="{{ route('admin.kitchen.ingredients') }}">Raw Materials</a>
                    </li>
                    <li class="sb-sub-item">
                        <a class="sb-sub-link {{ request()->route()->named('admin.kitchen.recipes') ? 'is-active-sub' : '' }}"
                           href="{{ route('admin.kitchen.recipes') }}">Recipes</a>
                    </li>
                    <li class="sb-sub-item">
                        <a class="sb-sub-link {{ request()->route()->named('admin.kitchen.production') ? 'is-active-sub' : '' }}"
                           href="{{ route('admin.kitchen.production') }}">Batch Log</a>
                    </li>
                    <li class="sb-sub-item">
                        <a class="sb-sub-link {{ request()->route()->named('admin.kitchen.reports') ? 'is-active-sub' : '' }}"
                           href="{{ route('admin.kitchen.reports') }}">Reports</a>
                    </li>
                </ul>
            </div>
        </li>

        {{-- BAR --}}
        <li class="sb-section-label">Bar</li>

        <li class="sb-item {{ Request::is('admin/bar*') ? 'is-active' : '' }}">
            <a class="sb-link {{ Request::is('admin/bar*') ? '' : 'collapsed' }}"
               data-bs-toggle="collapse" href="#m-bar"
               aria-expanded="{{ Request::is('admin/bar*') ? 'true' : 'false' }}">
                <i class="fas fa-cocktail sb-icon"></i>
                <span>Bar & Beverage</span>
                <i class="fas fa-chevron-right sb-arrow"></i>
            </a>
            <div class="collapse {{ Request::is('admin/bar*') ? 'show' : '' }}" id="m-bar">
                <ul class="sb-submenu">
                    <li class="sb-sub-item">
                        <a class="sb-sub-link {{ request()->route()->named('admin.bar.inventory') ? 'is-active-sub' : '' }}"
                           href="{{ route('admin.bar.inventory') }}">Drink Stock</a>
                    </li>
                    <li class="sb-sub-item">
                        <a class="sb-sub-link {{ request()->route()->named('admin.bar.tickets') ? 'is-active-sub' : '' }}"
                           href="{{ route('admin.bar.tickets') }}">Dispense Tickets</a>
                    </li>
                    <li class="sb-sub-item">
                        <a class="sb-sub-link {{ request()->route()->named('admin.bar.recipes') ? 'is-active-sub' : '' }}"
                           href="{{ route('admin.bar.recipes') }}">Cocktail Recipes</a>
                    </li>
                    <li class="sb-sub-item">
                        <a class="sb-sub-link {{ request()->route()->named('admin.bar.reports') ? 'is-active-sub' : '' }}"
                           href="{{ route('admin.bar.reports') }}">Reports</a>
                    </li>
                </ul>
            </div>
        </li>

        @if ($loggedInUser->role == "global_admin")

        {{-- MANAGEMENT --}}
        <li class="sb-section-label">Management</li>

        <li class="sb-item {{ request()->route()->named('admin.payroll.index') ? 'is-active' : '' }}">
            <a class="sb-link" href="{{ route('admin.payroll.index') }}">
                <i class="fas fa-file-invoice-dollar sb-icon"></i>
                <span>Payroll</span>
            </a>
        </li>

        <li class="sb-item {{ request()->route()->named('admin.users.index') ? 'is-active' : '' }}">
            <a class="sb-link" href="{{ route('admin.users.index') }}">
                <i class="fas fa-users-cog sb-icon"></i>
                <span>Admins</span>
            </a>
        </li>

        <li class="sb-item {{ request()->route()->named('admin.waiters.index') ? 'is-active' : '' }}">
            <a class="sb-link" href="{{ route('admin.waiters.index') }}">
                <i class="fas fa-id-badge sb-icon"></i>
                <span>Staff & Waiters</span>
            </a>
        </li>

        <li class="sb-item {{ Request::is('admin/venue*') ? 'is-active' : '' }}">
            <a class="sb-link {{ Request::is('admin/venue*') ? '' : 'collapsed' }}"
               data-bs-toggle="collapse" href="#m-venues"
               aria-expanded="{{ Request::is('admin/venue*') ? 'true' : 'false' }}">
                <i class="fas fa-glass-cheers sb-icon"></i>
                <span>Venues</span>
                <i class="fas fa-chevron-right sb-arrow"></i>
            </a>
            <div class="collapse {{ Request::is('admin/venue*') ? 'show' : '' }}" id="m-venues">
                <ul class="sb-submenu">
                    <li class="sb-sub-item"><a class="sb-sub-link" href="{{ route('admin.venues.index') }}">All Venues</a></li>
                    <li class="sb-sub-item"><a class="sb-sub-link" href="{{ route('admin.venue-packages.index') }}">Packages</a></li>
                    <li class="sb-sub-item"><a class="sb-sub-link" href="{{ route('admin.venue-bookings.index') }}">Bookings</a></li>
                </ul>
            </div>
        </li>

        <li class="sb-item {{ Request::is('admin/room*') ? 'is-active' : '' }}">
            <a class="sb-link {{ Request::is('admin/room*') ? '' : 'collapsed' }}"
               data-bs-toggle="collapse" href="#m-rooms"
               aria-expanded="{{ Request::is('admin/room*') ? 'true' : 'false' }}">
                <i class="fas fa-hotel sb-icon"></i>
                <span>Rooms</span>
                <i class="fas fa-chevron-right sb-arrow"></i>
            </a>
            <div class="collapse {{ Request::is('admin/room*') ? 'show' : '' }}" id="m-rooms">
                <ul class="sb-submenu">
                    <li class="sb-sub-item"><a class="sb-sub-link" href="{{ route('admin.rooms.index') }}">All Rooms</a></li>
                    <li class="sb-sub-item"><a class="sb-sub-link" href="{{ route('admin.room-bookings.index') }}">Bookings</a></li>
                </ul>
            </div>
        </li>

        {{-- INVENTORY --}}
        <li class="sb-section-label">Inventory</li>

        <li class="sb-item {{ Request::is('admin/stock*') || Request::is('admin/suppliers*') ? 'is-active' : '' }}">
            <a class="sb-link {{ Request::is('admin/stock*') || Request::is('admin/suppliers*') ? '' : 'collapsed' }}"
               data-bs-toggle="collapse" href="#m-stock"
               aria-expanded="{{ Request::is('admin/stock*') || Request::is('admin/suppliers*') ? 'true' : 'false' }}">
                <i class="fas fa-boxes sb-icon"></i>
                <span>Stock & Inventory</span>
                <i class="fas fa-chevron-right sb-arrow"></i>
            </a>
            <div class="collapse {{ Request::is('admin/stock*') || Request::is('admin/suppliers*') ? 'show' : '' }}" id="m-stock">
                <ul class="sb-submenu">
                    <li class="sb-sub-item"><a class="sb-sub-link" href="{{ route('admin.suppliers.index') }}">Suppliers</a></li>
                    <li class="sb-sub-item"><a class="sb-sub-link" href="{{ route('admin.stock-categories.index') }}">Categories</a></li>
                    <li class="sb-sub-item"><a class="sb-sub-link" href="{{ route('admin.stock-items.index') }}">Items</a></li>
                    <li class="sb-sub-item"><a class="sb-sub-link" href="{{ route('admin.stock-purchases.index') }}">Purchases (In)</a></li>
                    <li class="sb-sub-item"><a class="sb-sub-link" href="{{ route('admin.stock-issues.index') }}">Issues (Out)</a></li>
                    <li class="sb-sub-item"><a class="sb-sub-link" href="{{ route('admin.stock-history.index') }}">History</a></li>
                </ul>
            </div>
        </li>

        {{-- SETTINGS --}}
        @php
            $inSettings = Request::is('admin/menu*') || Request::is('admin/category*') || Request::is('admin/banner*') ||
                          Request::is('admin/waiter*') || Request::is('admin/restaurant-table*') ||
                          Request::is('admin/testimony*') || Request::is('admin/general-settings*');
        @endphp
        <li class="sb-section-label">Settings</li>

        <li class="sb-item {{ $inSettings ? 'is-active' : '' }}">
            <a class="sb-link {{ $inSettings ? '' : 'collapsed' }}"
               data-bs-toggle="collapse" href="#m-settings"
               aria-expanded="{{ $inSettings ? 'true' : 'false' }}">
                <i class="fas fa-sliders-h sb-icon"></i>
                <span>Site & Restaurant</span>
                <i class="fas fa-chevron-right sb-arrow"></i>
            </a>
            <div class="collapse {{ $inSettings ? 'show' : '' }}" id="m-settings">
                <ul class="sb-submenu">
                    <li class="sb-sub-item"><a class="sb-sub-link" href="{{ route('admin.menus.index') }}">Menus</a></li>
                    <li class="sb-sub-item"><a class="sb-sub-link" href="{{ route('admin.categories.index') }}">Categories</a></li>
                    <li class="sb-sub-item"><a class="sb-sub-link" href="{{ route('admin.banners.index') }}">Banners</a></li>
                    <li class="sb-sub-item"><a class="sb-sub-link" href="{{ route('admin.restaurant-tables.index') }}">Tables</a></li>
                    <li class="sb-sub-item"><a class="sb-sub-link" href="{{ route('admin.testimonies.index') }}">Testimonials</a></li>
                    <li class="sb-sub-item"><a class="sb-sub-link" href="{{ route('admin.terms.edit') }}">Terms & Conditions</a></li>
                    <li class="sb-sub-item"><a class="sb-sub-link" href="{{ route('admin.privacy-policy.edit') }}">Privacy Policy</a></li>
                    <li class="sb-sub-item"><a class="sb-sub-link" href="{{ route('admin.general-settings') }}">General Settings</a></li>
                </ul>
            </div>
        </li>

        @endif

        {{-- ACCOUNT --}}
        <li class="sb-section-label">Account</li>

        <li class="sb-item {{ request()->route()->named('admin.view.myprofile') ? 'is-active' : '' }}">
            <a class="sb-link" href="{{ route('admin.view.myprofile') }}">
                <i class="fas fa-user sb-icon"></i>
                <span>My Profile</span>
            </a>
        </li>

        <li class="sb-item {{ request()->route()->named('change.password.form') ? 'is-active' : '' }}">
            <a class="sb-link" href="{{ route('change.password.form') }}">
                <i class="fas fa-lock sb-icon"></i>
                <span>Change Password</span>
            </a>
        </li>

    </ul>

    {{-- User Footer --}}
    <div class="sb-footer">
        <div class="sb-user">
            <img class="sb-avatar"
                 src="{{ $loggedInUser && $loggedInUser->profile_picture ? asset('storage/profile-picture/' . $loggedInUser->profile_picture) : asset('assets/images/user-icon.png') }}"
                 alt="{{ $loggedInUser->first_name }}">
            <div class="sb-user-info">
                <p class="sb-user-name">{{ $loggedInUser->first_name }} {{ $loggedInUser->last_name }}</p>
                <p class="sb-user-role">
                    @if($loggedInUser->role == 'global_admin') Global Admin
                    @elseif($loggedInUser->role == 'sales') Sales Manager
                    @else {{ ucfirst($loggedInUser->role) }}
                    @endif
                </p>
            </div>
        </div>
        <div class="sb-footer-actions">
            <a target="_blank" class="sb-action-btn sb-btn-site" href="{{ route('home') }}">
                <i class="fas fa-globe"></i> Website
            </a>
            <a class="sb-action-btn sb-btn-logout" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>
    </div>

</nav>
