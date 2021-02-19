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
                    <!-- Records Header Start -->
                    <div class="records--header">
                        
                        <div class="title">
                             <a href="javascript:void(0);" class="page_title"> Resellers</a> 
                        </div>

                        <div class="actions">
                            <form action="<?php echo e(url('/user')); ?>" method="get" class="search">
                                <?php echo e(csrf_field()); ?>

                                <input id='search-email' type="text" class="form-control" name="email" placeholder="Email..." required>
                                <button type="submit" class="btn btn-rounded"><i class="fa fa-search"></i></button>
                            </form>
                        </div>
                    </div>
                    <!-- Records Header End -->
                </div>

                <div class="panel">

                    <?php if($message = Session::get('success')): ?>
                        <div class="alert alert-success">
                            <p><?php echo e($message); ?>

                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                            </p>
                            
                        </div>
                    <?php endif; ?>
                    <?php if($message = Session::get('error')): ?>
                        <div class="alert alert-danger">
                            <p><?php echo e($message); ?>

                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                            </p>
                            
                        </div>
                    <?php endif; ?>
                    <!-- Records List Start -->
                    <div class="records--list" data-title="Resellers Listing">
                        <table id="recordsListView">
                            <thead>
                                <tr>
                                    <th>S.NO</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Created On</th>
                                    <th>Status</th>
                                    <th class="not-sortable">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;  
                                ?>  
                              <?php $__currentLoopData = $resellers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reseller): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($i); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('admin.resellerView',['id'=>$reseller->id])); ?>" class="btn-link"><?php echo e($reseller->name); ?></a>
                                    </td>
                                    <td><a href="<?php echo e(route('admin.resellerView',['id'=>$reseller->id])); ?>" class="btn-link"><?php echo e($reseller->email); ?></a></td>
                                    <td><?php echo e($reseller->created_at); ?></td>
                                    <?php
                                    $status=array(
                                        '0'=>'Inactive',
                                        '1'=>'Active'
                                        );
                                    ?>
                                    <td>
                                        <?php $__currentLoopData = $status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $s => $s_value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                           <?php if($reseller->status==$s): ?>
                                                <a href="javascript:void(0)" class="btn-link">
                                                 <?php if($s==0): ?>
                                                        <span class="label label-danger"><?php echo e($s_value); ?></span></a>
                                                    <?php elseif($s==1): ?>
                                                        <span class="label label-success"><?php echo e($s_value); ?></span></a>
                                                    <?php endif; ?>
                                                    
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?> 
                                    </td>
                                    <!-- <td>                                        <a href="<?php echo e(url('/user')); ?>/<?php echo e($reseller->id); ?>" class="btn-link"><?php echo e($reseller->created_at); ?></a></td> -->
                                    
                                    <td>
                                        <div class="dropleft">
                                            <a href="#" class="btn-link" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></a>

                                            <div class="dropdown-menu">
                                                <a href="<?php echo e(route('admin.resellerView',['id'=>$reseller->id])); ?>" class="dropdown-item"><button class="btn btn-rounded btn-outline-info btn-st">View</button></a>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <?php $i++; ?>
                              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                
                            </tbody>
                        </table>
                    </div>
                    <!-- Records List End -->
                </div>
            </section>
            <!-- Main Content End -->
    <!-- footer -->
       <?php echo $__env->make('Admin::layouts.main_footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
     <!-- end footer -->
      <!-- Scripts -->
       <?php echo $__env->make('Admin::layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
     <!-- Scripts -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('Admin::layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\page2lead\app\Modules/Admin/resources/views/resellers/resellers.blade.php ENDPATH**/ ?>