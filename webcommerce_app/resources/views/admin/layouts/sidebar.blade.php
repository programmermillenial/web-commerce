<aside class="sidebar sidebar-default sidebar-white sidebar-base navs-rounded-all ">
    <div class="sidebar-header d-flex align-items-center justify-content-start">
        <a href="{{ url('') }}" class="navbar-brand">

            <!--Logo start-->
            <div class="logo-main">
                <div class="logo-normal">
                    <svg class=" icon-30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="-0.757324" y="19.2427" width="28" height="4" rx="2"
                            transform="rotate(-45 -0.757324 19.2427)" fill="currentColor" />
                        <rect x="7.72803" y="27.728" width="28" height="4" rx="2"
                            transform="rotate(-45 7.72803 27.728)" fill="currentColor" />
                        <rect x="10.5366" y="16.3945" width="16" height="4" rx="2"
                            transform="rotate(45 10.5366 16.3945)" fill="currentColor" />
                        <rect x="10.5562" y="-0.556152" width="28" height="4" rx="2"
                            transform="rotate(45 10.5562 -0.556152)" fill="currentColor" />
                    </svg>
                </div>
                <div class="logo-mini">
                    <svg class=" icon-30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect x="-0.757324" y="19.2427" width="28" height="4" rx="2"
                            transform="rotate(-45 -0.757324 19.2427)" fill="currentColor" />
                        <rect x="7.72803" y="27.728" width="28" height="4" rx="2"
                            transform="rotate(-45 7.72803 27.728)" fill="currentColor" />
                        <rect x="10.5366" y="16.3945" width="16" height="4" rx="2"
                            transform="rotate(45 10.5366 16.3945)" fill="currentColor" />
                        <rect x="10.5562" y="-0.556152" width="28" height="4" rx="2"
                            transform="rotate(45 10.5562 -0.556152)" fill="currentColor" />
                    </svg>
                </div>
            </div>
            <!--logo End-->

            <h4 class="logo-title">{{ config('app.name') }}</h4>
        </a>
        <div class="sidebar-toggle" data-toggle="sidebar" data-active="true">
            <i class="icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M4.25 12.2744L19.25 12.2744" stroke="currentColor" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M10.2998 18.2988L4.2498 12.2748L10.2998 6.24976" stroke="currentColor" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </i>
        </div>
    </div>

    <div class="pt-0 sidebar-body data-scrollbar">
        <div class="sidebar-list">

            <ul class="navbar-nav iq-main-menu" id="sidebar-menu">

                <!-- DASHBOARD -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                        href="{{ route('dashboard') }}">
                        <i class="ri-dashboard-fill"></i>
                        <span class="item-name">Dashboard</span>
                    </a>
                </li>

                <!-- MASTER DATA -->
                <li>
                    <hr class="hr-horizontal">
                </li>

                <li class="nav-item static-item">
                    <a class="nav-link static-item disabled" href="#">
                        <span class="default-icon">MASTER DATA</span>
                    </a>
                </li>

                <!-- CATEGORY -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('menu-categories.*') ? 'active' : '' }}"
                        href="{{ route('menu-categories.index') }}">
                        <i class="ri-apps-2-fill"></i>
                        <span class="item-name">Menu Category</span>
                    </a>
                </li>

                <!-- MENUS -->
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('menu.*') ? 'active' : '' }}"
                        href="{{ route('menu.index') }}">
                        <i class="ri-restaurant-2-fill"></i>
                        <span class="item-name">Menu</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('customers.*') ? 'active' : '' }}"
                        href="{{ route('customers.index') }}">
                        <i class="ri-user-3-line"></i>
                        <span class="item-name">Customers</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->is('vouchers*') ? 'active' : '' }}"
                        href="{{ route('vouchers.index') }}">
                        <i class="ri-coupon-3-line"></i>
                        <span class="item-name">Voucher</span>
                    </a>
                </li>

                <!-- TRANSACTION -->
                <li>
                    <hr class="hr-horizontal">
                </li>

                <li class="nav-item static-item">
                    <a class="nav-link static-item disabled" href="#">
                        <span class="default-icon">TRANSACTION</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('orders.*') ? 'active' : '' }}"
                        href="{{ route('orders.index') }}">
                        <i class="ri-shopping-bag-3-fill"></i>
                        <span class="item-name">Orders</span>
                    </a>
                </li>

                <!-- REPORTS -->
                <li>
                    <hr class="hr-horizontal">
                </li>

                <li class="nav-item static-item">
                    <a class="nav-link static-item disabled" href="#">
                        <span class="default-icon">REPORT</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reports.sales*') ? 'active' : '' }}"
                        href="{{ route('reports.sales') }}">
                        <i class="ri-bar-chart-line"></i>
                        <span class="item-name">Sales Report</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('reports.customer.*') ? 'active' : '' }}"
                        href="{{ route('reports.customer') }}">
                        <i class="ri-file-chart-line"></i>
                        <span class="item-name">Customer Report</span>
                    </a>
                </li>

                <!-- SETTING -->
                <li>
                    <hr class="hr-horizontal">
                </li>

                <li class="nav-item static-item">
                    <a class="nav-link static-item disabled" href="#">
                        <span class="default-icon">SETTING</span>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('store-settings.*') ? 'active' : '' }}"
                        href="{{ route('store-settings.index') }}">
                        <i class="ri-equalizer-line"></i>
                        <span class="item-name">Store Setting</span>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="sidebar-footer"></div>
</aside>
