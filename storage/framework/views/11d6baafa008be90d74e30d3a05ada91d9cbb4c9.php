<?php
    /*$adminData = Session::get('adminSessionData');
    $adminId = $adminData['id'];
    $adminName = $adminData['name'];
    $adminEmail = $adminData['email'];*/

    $adminId = Auth::guard('admin')->user()->id;
    $adminName = Auth::guard('admin')->user()->name;
    $adminEmail = Auth::guard('admin')->user()->email;

?>

<!-- Navbar Start -->
        <header class="navbar navbar-fixed">
            <!-- Navbar Header Start -->
            <div class="navbar--header">
                <!-- Logo Start -->
                <a href="<?php echo e(route('admin.dashboard')); ?>" class="logo">
                   <center>
                    <!-- <img src="<?php echo e(asset('public/admin-assets/img/logo/group_leads_logo.png')); ?>" alt=""> -->
                        <img src="http://trigvent.com/wp-content/uploads/2020/04/trigvent-logo-1-150x150.png" alt="" style="height: 100px;margin-top: -25px;">
                    </center>
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
                    

                    <!-- Nav Language Start -->
                    <!--<li class="nav-item dropdown nav-language">
                        <a href="#" class="nav-link" data-toggle="dropdown">
                            <img src="<?php echo e(asset('admin/img/flags/us.png')); ?>" alt="">
                            <span>English</span>
                            <i class="fa fa-angle-down"></i>
                        </a>

                        <ul class="dropdown-menu">
                            <li>
                                <a href="">
                                    <img src="<?php echo e(asset('admin/img/flags/de.png')); ?>" alt="">
                                    <span>German</span>
                                </a>
                            </li>
                            <li>
                                <a href="">
                                    <img src="<?php echo e(asset('admin/img/flags/fr.png')); ?>" alt="">
                                    <span>French</span>
                                </a>
                            </li>
                            <li>
                                <a href="">
                                    <img src="<?php echo e(asset('admin/img/flags/us.png')); ?>" alt="">
                                    <span>English</span>
                                </a>
                            </li>
                        </ul>
                    </li>-->
                    <!-- Nav Language End -->

                    <!-- Nav User Start -->
                    <li class="nav-item dropdown nav--user online">
                        <a href="#" class="nav-link" data-toggle="dropdown">
                            <img src="<?php echo e(asset('public/admin-assets/img/avatars/user.png')); ?>" alt="" class="rounded-circle">
                            <span><?php echo e($adminName); ?></span>
                            <i class="fa fa-angle-down"></i>
                        </a>

                        <ul class="dropdown-menu">
                            <li><a href="<?php echo e(route('admin.profile')); ?>"><i class="far fa-user"></i>Profile</a></li>
                            <li><a href="<?php echo e(route('admin.editProfile',['id' => $adminId])); ?>"><i class="fa fa-edit"></i>Edit Profile</a></li>
                            <li><a href="<?php echo e(url('/logout')); ?>"onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();"><i class="fa fa-power-off"></i>Logout</a></li>


                            <form id="logout-form" action="<?php echo e(route('admin.logout')); ?>" method="POST" style="display: none;">
                                        <?php echo csrf_field(); ?>
                             </form>
                        </ul>
                    </li>
                    <!-- Nav User End -->
                </ul>
            </div>
        </header>
        <!-- Navbar End --><?php /**PATH /home/pagearvu/api.page2leads.net/app/Modules/Admin/resources/views/layouts/navbar.blade.php ENDPATH**/ ?>