<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{URL::to('/dashboard')}}" class="brand-link">
        <img src="{{asset('back/dist/img/AdminLTELogo.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">{{ setting()->company_name ?? 'ShipBob' }}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar user panel (optional) -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
                <img src="{{asset('back/dist/img/user2-160x160.jpg')}}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="/" class="d-block">{{ Auth::user()->username ?? '' }}</a>
            </div>
        </div>

        <!-- SidebarSearch Form -->
        <div class="form-inline">
            <div class="input-group" data-widget="sidebar-search">
                <input class="form-control form-control-sidebar" type="search" placeholder="Search" aria-label="Search">
                <div class="input-group-append">
                    <button class="btn btn-sidebar">
                        <i class="fas fa-search fa-fw"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- Add icons to the links using the .nav-icon class
                     with font-awesome or any other icon font library -->
                <li class="nav-item {{ Request::is('dashboard') ? 'menu-open' : '' }}">
                    <a href="{{ URL::to('/dashboard') }}" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>
                            Dashboard

                        </p>
                    </a>

                </li>

                <li class="nav-item {{ Request::is('updateUser') ? 'menu-open' : '' }}">
                    <a href="{{ route('updateUser.index') }}" class="nav-link">
                        <i class="nav-icon far fa-image"></i>
                        <p>
                            Users
                        </p>
                    </a>
                </li>

                <li class="nav-item {{ Request::is('products*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Product
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('products.create') }}" class="nav-link {{ request()->routeIs('products.create') ? 'active_nav_menu' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Product</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('products.index') }}" class="nav-link {{ request()->routeIs('products.index') ? 'active_nav_menu' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>All Product</p>
                            </a>
                        </li>

                    </ul>
                </li>

                {{--
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Package
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('packages.create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Package</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('packages.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>All Package</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Assign Package
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('package-assign.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>All Assign Package</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('package-assign.create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Assign Package</p>
                            </a>
                        </li>

                    </ul>
                </li>
                --}}
                <li class="nav-item {{ Request::is('settings') ? 'menu-open' : '' }}">
                    <a href="{{ route('settings') }}" class="nav-link">
                        <i class="nav-icon far fa-image"></i>
                        <p>
                            Settings
                        </p>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('frozen-amounts*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Reserved Amount
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('frozen-amounts.create') }}" class="nav-link {{ request()->routeIs('frozen-amounts.create') ? 'active_nav_menu' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Reserved Amount</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('frozen-amounts.index') }}" class="nav-link  {{ request()->routeIs('frozen-amounts.index') ? 'active_nav_menu' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>All Reserved Amount</p>
                            </a>
                        </li>

                    </ul>
                </li>

                {{--
                <li class="nav-item">
                    <a href="{{ route('levels.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Level
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('levels.create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Level</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('levels.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>All Level</p>
                            </a>
                        </li>

                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Assign Level
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('level-assign.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>All Assign Level</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('level-assign.create') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Assign Level</p>
                            </a>
                        </li>

                    </ul>
                </li>
                --}}

                <li class="nav-item {{ (Request::is('cashin-lists*') || Request::is('cashout-lists*') || request()->routeIs('cashin-img-changes')) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-list"></i>
                        <p>
                            CashIn/CashOut
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ url('/cashin-lists') }}" class="nav-link {{ request()->routeIs('cashin-lists') ? 'active_nav_menu' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>CashIn Lists</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/cashout-lists') }}" class="nav-link {{ request()->routeIs('cashout-lists') ? 'active_nav_menu' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>CashOut Lists</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ url('/cashin-img-changes') }}" class="nav-link {{ request()->routeIs('cashin-img-changes') ? 'active_nav_menu' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>CashIn Img Change</p>
                            </a>
                        </li>

                    </ul>
                </li>

                <li class="nav-item {{ Request::is('tasks*') ? 'menu-open' : '' }}">
                    <a href="{{ route('tasks.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Task
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('tasks.create') }}" class="nav-link  {{ request()->routeIs('tasks.create') ? 'active_nav_menu' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Task</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('tasks.index') }}" class="nav-link {{ request()->routeIs('tasks.index') ? 'active_nav_menu' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>All Task</p>
                            </a>
                        </li>

                    </ul>
                </li>
                <li class="nav-item {{ Request::is('task-assign*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Assign Task
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('task-assign.index') }}" class="nav-link {{ request()->routeIs('task-assign.index') ? 'active_nav_menu' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>All Assign Task</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('task-assign.create') }}" class="nav-link {{ request()->routeIs('task-assign.create') ? 'active_nav_menu' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Assign Task</p>
                            </a>
                        </li>

                    </ul>
                </li>

                <li class="nav-item {{ Request::is('trial-task') ? 'menu-open' : '' }}">
                    <a href="{{ route('trial-task') }}" class="nav-link">
                        <i class="nav-icon far fa-image"></i>
                        <p>
                            Trial Task
                        </p>
                    </a>
                </li>

                <li class="nav-item {{ Request::is('trial-task-assign*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Assign Trial Task
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('trial-task-assign.index') }}" class="nav-link {{ request()->routeIs('trial-task-assign.index') ? 'active_nav_menu' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>All Assign Trial Task</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('trial-task-assign.create') }}" class="nav-link {{ request()->routeIs('trial-task-assign.create') ? 'active_nav_menu' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Assign Trial Task</p>
                            </a>
                        </li>

                    </ul>
                </li>

                <li class="nav-item {{ Request::is('invitation-codes*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Invitation Code
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('invitation-codes.create') }}" class="nav-link {{ request()->routeIs('invitation-codes.create') ? 'active_nav_menu' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Invitation Code</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('invitation-codes.index') }}" class="nav-link {{ request()->routeIs('invitation-codes.index') ? 'active_nav_menu' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>All Invitation Code</p>
                            </a>
                        </li>

                    </ul>
                </li>
                <li class="nav-item {{ Request::is('password-change') ? 'menu-open' : '' }}">
                    <a href="{{ route('password-change') }}" class="nav-link">
                        <i class="nav-icon fas fa-cog"></i>
                        <p>
                            Password Change
                        </p>
                    </a>
                </li>

                <li class="nav-item {{ Request::is('gifts*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Gift Box
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('gifts.create') }}" class="nav-link {{ request()->routeIs('gifts.create') ? 'active_nav_menu' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Gift Box</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('gifts.index') }}" class="nav-link {{ request()->routeIs('gifts.index') ? 'active_nav_menu' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>All Gift Box</p>
                            </a>
                        </li>

                    </ul>
                </li>

                <li class="nav-item {{ Request::is('bonus-reasons*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Bonus Reasons
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('bonus-reasons.create') }}" class="nav-link {{ request()->routeIs('bonus-reasons.create') ? 'active_nav_menu' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Bonus Reason</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('bonus-reasons.index') }}" class="nav-link {{ request()->routeIs('bonus-reasons.index') ? 'active_nav_menu' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>All Bonus Reason</p>
                            </a>
                        </li>

                    </ul>
                </li>

                <li class="nav-item {{ Request::is('bonus*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Bonus
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('bonus.create') }}" class="nav-link {{ request()->routeIs('bonus.create') ? 'active_nav_menu' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Bonus</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('bonus.index') }}" class="nav-link {{ request()->routeIs('bonus.index') ? 'active_nav_menu' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>All Bonus</p>
                            </a>
                        </li>

                    </ul>
                </li>
                <li class="nav-item {{ Request::is('credits*') || Request::is('credit-assign*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Credit
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('credit-assign.index') }}" class="nav-link {{ request()->routeIs('credit-assign.index') ? 'active_nav_menu' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Assign Credit</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('credits.index') }}" class="nav-link {{ request()->routeIs('credits.index') ? 'active_nav_menu' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>All Credit</p>
                            </a>
                        </li>

                    </ul>
                </li>

                <li class="nav-header">PAGE CONTENTS</li>
                <li class="nav-item {{ Request::is('home-page*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Home Page
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('hero-section-content') }}" class="nav-link {{ request()->routeIs('hero-section-content') ? 'active_nav_menu' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Hero Section</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('video-section-content') }}" class="nav-link {{ request()->routeIs('video-section-content') ? 'active_nav_menu' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Video Section</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('growth-section-content') }}" class="nav-link {{ request()->routeIs('growth-section-content') ? 'active_nav_menu' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Growth Section</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('shipping-section-content') }}" class="nav-link {{ request()->routeIs('shipping-section-content') ? 'active_nav_menu' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Shipping Section</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('global-section-content') }}" class="nav-link {{ request()->routeIs('global-section-content') ? 'active_nav_menu' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Global Section</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('courier-section-content') }}" class="nav-link {{ request()->routeIs('courier-section-content') ? 'active_nav_menu' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Courier Section</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('delivery-section-content') }}" class="nav-link {{ request()->routeIs('delivery-section-content') ? 'active_nav_menu' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Delivery Section</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('contact-section-content') }}" class="nav-link {{ request()->routeIs('contact-section-content') ? 'active_nav_menu' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Contact Section</p>
                            </a>
                        </li>

                    </ul>
                </li>
                <li class="nav-item {{ Request::is('helpCenter*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Help Center
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('helpCenter.create') }}" class="nav-link {{ request()->routeIs('helpCenter.create') ? 'active_nav_menu' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Help Center</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('helpCenter.index') }}" class="nav-link {{ request()->routeIs('helpCenter.index') ? 'active_nav_menu' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>All Help Center</p>
                            </a>
                        </li>

                    </ul>
                </li>
                <li class="nav-item {{ Request::is('events*') ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Event
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('events.create') }}" class="nav-link {{ request()->routeIs('events.create') ? 'active_nav_menu' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Add Event</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('events.index') }}" class="nav-link {{ request()->routeIs('events.index') ? 'active_nav_menu' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>All Event</p>
                            </a>
                        </li>

                    </ul>
                </li>
                <li class="nav-item {{ (Request::is('set-off*') || Request::is('sliders')) ? 'menu-open' : '' }}">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-th"></i>
                        <p>
                            Set Off
                            <i class="fas fa-angle-left right"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('set-off-video-section-content') }}" class="nav-link {{ request()->routeIs('set-off-video-section-content') ? 'active_nav_menu' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Set Off Video Section</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('sliders.index') }}" class="nav-link {{ request()->routeIs('sliders.index') ? 'active_nav_menu' : '' }}">
                                <i class="far fa-circle nav-icon"></i>
                                <p>Sliders</p>
                            </a>
                        </li>

                    </ul>
                </li>
                <li class="nav-item {{ Request::is('about-us') ? 'menu-open' : '' }}">
                    <a href="{{ route('about-us') }}" class="nav-link">
                        <i class="nav-icon far fa-image"></i>
                        <p>
                            About Us
                        </p>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('login-page-content') ? 'menu-open' : '' }}">
                    <a href="{{ route('login-page-content') }}" class="nav-link">
                        <i class="nav-icon far fa-image"></i>
                        <p>
                            Login page content
                        </p>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('rule-section-content') ? 'menu-open' : '' }}">
                    <a href="{{ route('rule-section-content') }}" class="nav-link">
                        <i class="nav-icon far fa-image"></i>
                        <p>
                            Credit Rules
                        </p>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('sign-up-content') ? 'menu-open' : '' }}">
                    <a href="{{ route('sign-up-content') }}" class="nav-link">
                        <i class="nav-icon far fa-image"></i>
                        <p>
                            Sign Up
                        </p>
                    </a>
                </li>
                <li class="nav-item {{ Request::is('gift-box-content') ? 'menu-open' : '' }}">
                    <a href="{{ route('gift-box-content') }}" class="nav-link">
                        <i class="nav-icon far fa-image"></i>
                        <p>
                            Gift Box Content
                        </p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
