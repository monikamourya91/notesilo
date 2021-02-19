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
                    <div class="records--body">
                        <div class="title">
                            <h6 class="h6">Upgrade/Downgrade Subscription</h6>                  
                        </div>
                         <?php if(!empty($error_msg)): ?>
                            <p class="alert alert-danger"><?php echo e($error_msg); ?></p>
                        <?php else: ?>
                        <div class="tab-content">
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

                                <form id="admin_add_subscriber" action="<?php echo e(route('admin.updateSubscription')); ?>" method="POST">
                                    <?php echo e(csrf_field()); ?>

                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">Name:</span>
                                        <div class="col-md-9 col-form-label">
                                            <?php echo e($user->name); ?>

                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">Email:</span>
                                        <div class="col-md-9 col-form-label">
                                            <?php echo e($user->email); ?>

                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">Current Plan:</span>
                                        <div class="col-md-9 col-form-label">
                                            <?php echo e($user->plan_name); ?>

                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">Renew Date:</span>
                                        <div class="col-md-9 col-form-label">
                                            <?php echo e($user->expired_on); ?>

                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">Select Plan: *</span>
                                        <div class="col-md-9">
                                            <select name="plan" class="form-control <?php $__errorArgs = ['plan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> error <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required1>
                                                <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($plan->id); ?>" <?php if($user->plan_id == $plan->id): ?> selected <?php endif; ?>><?php echo e($plan->name); ?> ($<?php echo e($plan->price); ?>) <?php if($plan->trial) echo "- Trial"; ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                             <?php $__errorArgs = ['plan'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                                <p class="error"><?php echo e($message); ?></p>
                                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                        </div>
                                    </div>
                                    <div class="row mt-3">
                                        <div class="col-md-9 offset-md-3">
                                            <input type="hidden" value="<?php echo e($user->id); ?>" name="id">
                                            <input type="hidden" value="<?php echo e($user->plan_id); ?>" name="old_plan_id">
                                            <input type="hidden" value="<?php echo e($user->subscription_id); ?>" name="subscription_id">
                                            <input type="submit" value="Update" class="btn btn-rounded btn-success">
                                        </div>
                                    </div>
                                </form>
                                
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </section>
            <!-- Main Content End -->

            <?php echo $__env->make('Admin::layouts.main_footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <!-- Main Container End -->
          <!-- Scripts -->
           <?php echo $__env->make('Admin::layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
         <!-- Scripts -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Admin::layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\page2lead\app\Modules/Admin/resources/views/subscribers/subscriber_plan_change.blade.php ENDPATH**/ ?>