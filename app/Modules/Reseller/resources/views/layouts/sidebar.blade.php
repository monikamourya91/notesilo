<?php
    /*$adminData = Session::get('adminSessionData');
    $adminId = $adminData['id'];
    $adminName = $adminData['name'];
    $adminEmail = $adminData['email'];*/

    $adminId = Auth::guard('reseller')->user()->id;
    $adminName = Auth::guard('reseller')->user()->name;
    $adminEmail = Auth::guard('reseller')->user()->email;

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
                <li class="nav-item {{ (Route::is('reseller.profile') || Route::is('reseller.editProfile')) ? 'active' : '' }}">
                    <a href="{{ route('reseller.profile') }}" class="nav-link" title="User Profile">
                        <i class="fa fa-user"></i>
                    </a>
                </li>
                <li class="nav-item {{ Route::is('reseller.changePassword') ? 'active' : '' }}">
                    <a href="{{ route('reseller.changePassword') }}" class="nav-link" title="Password change">
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
                    <li class="{{ Route::is('reseller.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('reseller.dashboard') }}">
                            <i class="fa fa-home"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                     <li class="{{ (Route::is('reseller.subscribersList') || Route::is('reseller.subscriberAdd') || Route::is('reseller.subscriberView')) ? 'active' : '' }}">
                        <a href="{{ route('reseller.subscribersList') }}">
                            <i class="fa fa-users"></i>
                            <span>Subscribers</span> 
                        </a>
                    </li>
                    <li class="{{ (Route::is('reseller.paymentModesList') || Route::is('reseller.AddNewPaymetMode') || Route::is('reseller.EditPaymentMode')) ? 'active' : '' }}">
                        <a href="{{route('reseller.paymentModesList')}}">
                            <i class="fa fa-th"></i>
                            <span>Manage Payment Modes</span>
                        </a>
                    </li>
                    <li class="{{ (Route::is('reseller.plansList') || Route::is('reseller.AddNewPlan') || Route::is('reseller.EditPlan')) ? 'active' : '' }}">
                        <a href="{{route('reseller.plansList')}}">
                            <i class="fa fa-list"></i>
                            <span>Manage Plans</span>
                        </a>
                    </li>
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