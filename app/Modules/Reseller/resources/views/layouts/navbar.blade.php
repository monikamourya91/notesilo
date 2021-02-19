<?php
    /*$adminData = Session::get('adminSessionData');
    $adminId = $adminData['id'];
    $adminName = $adminData['name'];
    $adminEmail = $adminData['email'];*/

    $adminId = Auth::guard('reseller')->user()->id;
    $adminName = Auth::guard('reseller')->user()->name;
    $adminEmail = Auth::guard('reseller')->user()->email;

    $reseller_hash = Auth::guard('reseller')->user()->reseller_hash

?>

<!-- Navbar Start -->
        <header class="navbar navbar-fixed">
            <!-- Navbar Header Start -->
            <div class="navbar--header">
                <!-- Logo Start -->
                <a href="{{route('reseller.dashboard')}}" class="logo">
                   <center><img src="{{ asset('public/admin-assets/img/logo/group_leads_logo.png')}}" alt=""></center>
                </a>
                <!-- Logo End -->

                <!-- Sidebar Toggle Button Start -->
                <a href="#" class="navbar--btn" data-toggle="sidebar" title="Toggle Sidebar">
                    <i class="fa fa-bars"></i>
                </a>
                <!-- Sidebar Toggle Button End -->
            </div>
            <!-- Navbar Header End -->

            <!-- Sidebar Toggle Button Start -->
            <a href="#" class="navbar--btn" data-toggle="sidebar" title="Toggle Sidebar">
                <i class="fa fa-bars"></i>
            </a>
            <!-- Sidebar Toggle Button End -->

            <!-- Navbar Search Start -->
           <!-- <div class="navbar--search">
                <form action="search-results.html">
                    <input type="search" name="search" class="form-control" placeholder="Search Something..." required>
                    <button class="btn-link"><i class="fa fa-search"></i></button>
                </form>
            </div>-->
            <!-- Navbar Search End -->

            <div class="navbar--nav ml-auto">
                <ul class="nav">
                    @php
                        $plans_list = DB::table('resellers as t1')
                        ->join('payment_gateway_settings as t2','t1.id','=','t2.reseller_id')
                        ->join('plans as t3','t2.id','=','t3.payment_gateway_id')
                        ->where('t1.status',1)
                        ->where('t2.status',1)
                        ->where('t3.status',1)
                        ->where('t2.reseller_id',$adminId)
                        ->count();
                    @endphp

                    @if($plans_list > 0)
                    <li style="padding-top: 30px;color:#333;">
                        Plans Page: <a href='{{url("/plans/$reseller_hash")}}' target="_blank" style="color: #3c61ad;">
                            {{url("/plans/$reseller_hash")}}
                        </a>
                    </li>
                    @endif
                    {{-- <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fa fa-bell"></i>
                            <span class="badge text-white bg-blue">7</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="fa fa-envelope"></i>
                            <span class="badge text-white bg-blue">4</span>
                        </a>
                    </li> --}}

                    <!-- Nav Language Start -->
                    <!--<li class="nav-item dropdown nav-language">
                        <a href="#" class="nav-link" data-toggle="dropdown">
                            <img src="{{ asset('admin/img/flags/us.png')}}" alt="">
                            <span>English</span>
                            <i class="fa fa-angle-down"></i>
                        </a>

                        <ul class="dropdown-menu">
                            <li>
                                <a href="">
                                    <img src="{{ asset('admin/img/flags/de.png')}}" alt="">
                                    <span>German</span>
                                </a>
                            </li>
                            <li>
                                <a href="">
                                    <img src="{{ asset('admin/img/flags/fr.png')}}" alt="">
                                    <span>French</span>
                                </a>
                            </li>
                            <li>
                                <a href="">
                                    <img src="{{ asset('admin/img/flags/us.png')}}" alt="">
                                    <span>English</span>
                                </a>
                            </li>
                        </ul>
                    </li>-->
                    <!-- Nav Language End -->

                    <!-- Nav User Start -->
                    <li class="nav-item dropdown nav--user online">
                        <a href="#" class="nav-link" data-toggle="dropdown">
                            <img src="{{ asset('public/admin-assets/img/avatars/user.png')}}" alt="" class="rounded-circle">
                            <span>{{$adminName}}</span>
                            <i class="fa fa-angle-down"></i>
                        </a>

                        <ul class="dropdown-menu">
                            <li><a href="{{ route('reseller.profile') }}"><i class="far fa-user"></i>Profile</a></li>
                            <li><a href="{{ route('reseller.editProfile',['id' => $adminId]) }}"><i class="fa fa-edit"></i>Edit Profile</a></li>
                            <li><a href="{{ url('/logout') }}"onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><i class="fa fa-power-off"></i>Logout</a></li>


                            <form id="logout-form" action="{{ route('reseller.logout') }}" method="POST" style="display: none;">
                                        @csrf
                             </form>
                        </ul>
                    </li>
                    <!-- Nav User End -->
                </ul>
            </div>
        </header>
        <!-- Navbar End -->