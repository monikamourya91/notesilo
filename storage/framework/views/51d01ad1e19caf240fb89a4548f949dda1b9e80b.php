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

    <main class="main--container">
            <section class="main--content">
                <div class="panel">

                    <!-- Edit Product Start -->
                    <div class="records--body">
                        <div class="title">
                            <h6 class="h6">Edit Profile</h6>                  
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
                                <?php if($message = Session::get('error')): ?>
                                    <div class="alert alert-danger">
                                        <p><?php echo e($message); ?></p>
                                    </div>
                                <?php endif; ?>
                                <form id="profile-admin-edit" action="<?php echo e(route('admin.updateProfile',['id' => $admin->id])); ?>" method="post">
                                    <?php echo e(csrf_field()); ?>

                                    
                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">Name: *</span>
                                        <div class="col-md-9">
                                            <input type="text" value="<?php echo e($admin->name); ?>" name="name" class="form-control" required>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">Email: *</span>

                                        <div class="col-md-9">
                                            <input type="email" value="<?php echo e($admin->email); ?>" name="email" class="form-control" required>
                                        </div>
                                    </div>
                                 
                                    <div class="row mt-3">
                                        <div class="col-md-9 offset-md-3">
                                            <input type="submit" value="Update" class="btn btn-rounded btn-success">
                                        </div>
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

            <?php echo $__env->make('Admin::layouts.main_footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <!-- Main Container End -->
          <!-- Scripts -->
           <?php echo $__env->make('Admin::layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
         <!-- Scripts -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Admin::layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\page2lead\app\Modules/Admin/resources/views/editprofile.blade.php ENDPATH**/ ?>