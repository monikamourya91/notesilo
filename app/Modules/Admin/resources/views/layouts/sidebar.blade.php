<?php
    /*$adminData = Session::get('adminSessionData');
    $adminId = $adminData['id'];
    $adminName = $adminData['name'];
    $adminEmail = $adminData['email'];*/

    $adminId = Auth::guard('admin')->user()->id;
    $adminName = Auth::guard('admin')->user()->name;
    $adminEmail = Auth::guard('admin')->user()->email;

?>
<aside class="sidebar" data-trigger="scrollbar">
    <!-- Sidebar Profile Start -->
    <div class="sidebar--profile">
        <div class="profile--img">
            <a href="#">
                <img src="{{ asset('public/admin-assets/img/avatars/user.png')}}" alt="" class="rounded-circle">
            </a>
        </div>

        <div class="profile--name">
            <a href="#" class="btn-link">{{$adminName}}</a>
        </div>

        <div class="profile--nav">
            <ul class="nav">
                <li class="nav-item {{ (Route::is('admin.profile') || Route::is('admin.editProfile')) ? 'active' : '' }}">
                    <a href="{{ route('admin.profile') }}" class="nav-link" title="User Profile">
                        <i class="fa fa-user"></i>
                    </a>
                </li>
                <li class="nav-item {{ Route::is('admin.changePassword') ? 'active' : '' }}">
                    <a href="{{ route('admin.changePassword') }}" class="nav-link" title="Password change">
                        <i class="fa fa-lock"></i>
                    </a>
                </li>
                <!--<li class="nav-item">
                    <a href="mailbox_inbox.html" class="nav-link" title="Messages">
                        <i class="fa fa-envelope"></i>
                    </a>
                </li>-->
                <li class="nav-item">
                    <a href="{{ url('/logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();" class="nav-link" title="Logout">
                        <i class="fa fa-sign-out-alt"></i>
                    </a>
                </li>
            </ul>
        </div>
    </div>
    <!-- Sidebar Profile End -->

    <!-- Sidebar Navigation Start -->
    <div class="sidebar--nav">
        <ul>
            <li>
                <ul>
                    <li class="{{ Route::is('admin.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="fa fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="{{ Route::is('admin.subscribersList') || Route::is('admin.subscriberAdd') || Route::is('admin.subscriberView') ? 'active' : '' }}">
                        <a href="{{ route('admin.subscribersList') }}">
                            <i class="fa fa-users"></i>
                            <span>Subscribers</span>
                        </a>
                    </li>
                    <!-- <li class="{{ Route::is('admin.resellersList') || Route::is('admin.resellerView') ? 'active' : '' }}">
                        <a href="{{ route('admin.resellersList') }}">
                            <i class="fa fa-users"></i>
                            <span>Resellers</span>
                        </a>
                    </li> -->
                    <!--<li>
                        <a href="#">
                            <i class="fa fa-user-circle"></i>
                            <span>Manage User</span>
                        </a>

                        <ul>
                            <li><a href="{{ url('/user') }}">Users</a></li>
                            <li><a href="{{ url('/user/create') }}">Add User</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-th-list"></i>
                           <span>Manage Category</span>
                        </a>

                        <ul>
                            <li><a href="{{ url('/categories') }}">Categories</a></li>
                           <li><a href="{{ url('/categories/create') }}">Add Category</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">
                            <i class="fa fa-th-list"></i>
                           <span>Manage Ads</span>
                        </a>

                        <ul>
                            <li><a href="{{ url('/ads') }}">Ads</a></li>
                        </ul>
                    </li>-->
                    <!-- <li>
                        <a href="#">
                            <i class="fa fa-th"></i>
                            <span>Manage Extension</span>
                        </a>

                        <ul>
                            <li><a href="{{ url('/extension') }}">Listing</a></li>
                             <li><a href="{{ url('/categories/create') }}">Add Category</a></li> 
                        </ul>
                    </li> -->
                </ul>
            </li>

        </ul>
    </div>
    <!-- Sidebar Navigation End -->


</aside>
<!-- Sidebar End -->