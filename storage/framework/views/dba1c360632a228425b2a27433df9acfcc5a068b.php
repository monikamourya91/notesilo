<?php $__env->startSection('content'); ?>
 <!-- Wrapper Start -->
    <div class="wrapper">
       <!-- Navbar Start -->
        <?php echo $__env->make('Admin::layouts.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <!-- Navbar End -->

         <!-- Sidebar sart -->
          <?php echo $__env->make('Admin::layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <!-- Sidebar End -->
    </div>
    <!-- Wrapper End -->

        <!-- Main Container Start -->
        <main class="main--container">
     

            <!-- Main Content Start -->

            <section class="main--content">
                <div class="row gutter-20">
                    <div class="col-lg-8">
                        <!-- Panel Start -->
                        <div class="panel profile-cover">
                            <div class="profile-cover__img">
                                <img src="<?php echo e(asset('public/admin-assets/img/avatars/01_80x80.png')); ?>" alt="">
                            </div>

                            <div class="profile-cover__action" data-bg-img="<?php echo e(asset('public/admin-assets/img/covers/01_800x150.jpg')); ?>" data-overlay="0.3">
                               
                            </div>

                            <div class="profile-cover__info">
                                <ul class="nav">
                                    <li><strong></strong></li>
                                </ul>
                            </div>
                        </div>
                        <!-- Panel End -->

                        
                    </div>

                    <div class="col-lg-4">
                        <!-- Panel Start -->
                        <div class="panel">
                            <div class="panel-heading">
                                <h3 class="panel-title">About Me</h3>
                            </div>

                            <div class="panel-content panel-about">
                                <table>
                                    <tr>
                                        <th><i class="fa fa-user"></i>Name</th>
                                        <td><?php echo e($adminData['name']); ?></td>
                                    </tr>
                                    <tr>
                                        <th><i class="fa fa-envelope"></i>Email</th>
                                        <td><?php echo e($adminData['email']); ?></td>
                                    </tr>                                
                                </table>
                            </div>

                            <!-- <div class="panel-social">
                                <ul class="nav">
                                    <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                    <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                    <li><a href="#"><i class="fab fa-google-plus-g"></i></a></li>
                                    <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                    <li><a href="#"><i class="fab fa-behance"></i></a></li>
                                    <li><a href="#"><i class="fab fa-dribbble"></i></a></li>
                                </ul>
                            </div> -->
                        </div>
                        <!-- Panel End -->

                        

       
                    </div>
                </div>
            </section>
           
        <?php echo $__env->make('Admin::layouts.main_footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <!-- Main Container End -->
          <!-- Scripts -->
           <?php echo $__env->make('Admin::layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
         <!-- Scripts -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Admin::layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\page2lead\app\Modules/Admin/resources/views/profile.blade.php ENDPATH**/ ?>