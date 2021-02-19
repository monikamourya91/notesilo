

<?php $__env->startSection('content'); ?>
 <!-- Wrapper Start -->
    <div class="wrapper">
       <!-- Navbar Start -->
       <?php echo $__env->make('Admin::layouts.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <!-- Navbar End -->

         <!-- Sidebar sart -->
          <?php echo $__env->make('Admin::layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <!-- Sidebar End -->

    <!-- Main Container Start -->
        <main class="main--container">
            <!-- Main Content Start -->
            <section class="main--content">
                <div class="panel">
                    <div class="records--body">
                        <div class="title">
                            <h6 class="h6">User Detail</h6>
                        </div>

                        <?php if(session()->has('message')): ?>
                            <div class="alert alert-success">
                                <?php echo e(session()->get('message')); ?>

                            </div>
                        <?php endif; ?>
                        <!-- Tabs Nav Start -->
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                                <a href="#tab01" data-toggle="tab" class="nav-link active show">Profile</a>
                            </li>
                            
                          

                            <li class="nav-item">
                                <a href="#tab04" data-toggle="tab" class="nav-link">Subscription</a>
                            </li>
                            
                        </ul>
                        <!-- Tabs Nav End -->

                        <!-- Tab Content Start -->
                        <div class="tab-content">
                            <!-- Tab Pane Start -->
                            <div class="tab-pane fade active show" id="tab01">
                                <form action="<?php echo e(route('admin.updateSubscriber')); ?>" id="subscriber_form" method="POST" novalidate="novalidate">
                                    <?php echo csrf_field(); ?>

                                    <input type="hidden" id="userId" name="userId" value="<?php echo e($user->id); ?>">                                    
                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">Name: *</span>
                                        <div class="col-md-9">
                                            <input type="text" name="name" class="form-control valid" value="<?php echo e(old('name', $user->name)); ?>" required="" aria-invalid="false">
                                            <?php $__errorArgs = ['name'];
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
                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">Email: *</span>

                                        <div class="col-md-9">
                                            <input type="text" name="email" class="form-control valid" value="<?php echo e(old('email', $user->email)); ?>" required="" aria-invalid="false">
                                            <?php $__errorArgs = ['email'];
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
                                    
                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">License No:</span>

                                        <div class="col-md-9">
                                            <input type="text" readonly="" value="<?php echo e($user->license); ?>" class="form-control valid" aria-invalid="false">
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
                            
                            <!-- Tab Pane End -->

                            <!-- Tab Pane Start -->
                      
                            <!-- Tab Pane End -->

                             <!-- Tab Pane Start -->
                            <div class="tab-pane fade" id="tab04">
                                <div class="form-group row">
                                    <span class="label-text col-md-3 col-form-label">Plan Name:</span>

                                    <div class="col-md-9"><?php echo e(isset($planData[0]->plan_name)?$planData[0]->plan_name:''); ?></div>
                                </div>
                                <div class="form-group row">
                                    <span class="label-text col-md-3 col-form-label">Trial:</span>
                                    <div class="col-md-9">
                                        <?php if(isset($planData[0]->is_trial)): ?>
                                            <?php if($planData[0]->is_trial == '1'): ?>
                                                Yes
                                            <?php else: ?>
                                                No
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <span class="label-text col-md-3 col-form-label">Started On:</span>
                                    <div class="col-md-9"><?php echo e(isset($planData[0]->started_on)?$planData[0]->started_on:''); ?></div>
                                </div>
                                <div class="form-group row">
                                    <span class="label-text col-md-3 col-form-label">Expired On:</span>
                                    <div class="col-md-9"><?php echo e(isset($planData[0]->expired_on)?$planData[0]->expired_on:''); ?></div>
                                </div>
                            </div>
                            <!-- Tab Pane End -->
                        </div>
                        <!-- Tab Content End -->
                    </div>               
                </div>
                <!-- Modal -->


                <div id="autorespondersModal" class="modal fade" role="dialog">
                  <div class="modal-dialog modal-lg">
                    <!-- Modal content-->
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Active Autoresponder Credentials</h4>
                      </div>
                      <div class="modal-body" id="group_responsers">
                        
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                      </div>
                    </div>
                  </div>
                </div>

            </section>
            
            <!-- Main Content End -->
    <!-- footer -->
       <?php echo $__env->make('Admin::layouts.main_footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
     <!-- end footer -->
      <!-- Scripts -->
       <?php echo $__env->make('Admin::layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
     <!-- Scripts -->


                <script>
                    $(document).ready(function(){
                        $(".responders-group-wise").click(function(){
                            var group_id = $(this).attr('groupid');
                            $.ajax({
                                type:"POST",
                                data:{"group_id":group_id,"_token": "<?php echo e(csrf_token()); ?>"},
                                url:"<?php echo e(route('admin.groupResponders')); ?>",
                                success:function(response){
                                    $("#group_responsers").html(response);
                                }
                            });
                            $("#autorespondersModal").modal('show');
                        })
                    });
                </script>
<?php $__env->stopSection(); ?>
                
                
<?php echo $__env->make('Admin::layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\page2lead\app\Modules/Admin/resources/views/subscribers/view_subscriber.blade.php ENDPATH**/ ?>