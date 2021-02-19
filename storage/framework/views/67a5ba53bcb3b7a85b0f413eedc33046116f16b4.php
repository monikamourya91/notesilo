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
  <?php
      
      $adminId =    Auth::guard('admin')->user()->id;               // var_dump($name);
   ?>

   <main class="main--container">


            <!-- Main Content Start -->
            <section class="main--content">
                <div class="panel">

                    <!-- Edit Product Start -->
                    <div class="records--body">
                        <div class="title">
                            <h6 class="h6">Change Password</h6>                  
                        </div>
                     
                        <!-- Tab Content Start -->
                        <div class="tab-content">
                            <!-- Tab Pane Start -->
                            <div class="tab-pane fade show active" id="tab01">
                                <?php if($message = Session::get('success')): ?>
                                    <div class="alert alert-success">
                                        <p><?php echo e($message); ?></p>
                                    </div>
                                <?php endif; ?>
                                <form id="admin-change-password" method="POST" action="<?php echo e(route('admin.updatePassword')); ?>">
                                     <?php echo e(csrf_field()); ?>

                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">New Password: *</span>

                                        <div class="col-md-9">
                                            <input id="admin-password" type="password" name="password" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">Confirm Password: *</span>

                                        <div class="col-md-9">
                                            <input name="confirmpassword" type="password" class="form-control" required>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-9 offset-md-3">
                                            <input type="submit" value="Save" class="btn btn-rounded btn-success">
                                        </div>
                                        <input type="hidden" name="id" value="<?php echo e($adminId); ?>">
                                    </div>
                                </form>
                            </div>
                            <!-- Tab Pane End -->
                        </div>
                        <!-- Tab Content End -->
                    </div>
                    <!-- Edit Product End -->
                </div>
            </section>
            <!-- Main Content End -->

            
        <!-- Main Container End -->
         <?php echo $__env->make('Admin::layouts.main_footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
          <!-- Scripts -->
           <?php echo $__env->make('Admin::layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
         <!-- Scripts -->
<?php $__env->stopSection(); ?>


<?php echo $__env->make('Admin::layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\page2lead\app\Modules/Admin/resources/views/changepassword.blade.php ENDPATH**/ ?>