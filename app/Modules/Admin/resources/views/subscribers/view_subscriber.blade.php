@extends('Admin::layouts.app')

@section('content')
 <!-- Wrapper Start -->
    <div class="wrapper">
       <!-- Navbar Start -->
       @include('Admin::layouts.navbar')
        <!-- Navbar End -->

         <!-- Sidebar sart -->
          @include('Admin::layouts.sidebar')
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

                        @if(session()->has('message'))
                            <div class="alert alert-success">
                                {{session()->get('message')}}
                            </div>
                        @endif
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
                                <form action="{{route('admin.updateSubscriber')}}" id="subscriber_form" method="POST" novalidate="novalidate">
                                    @csrf

                                    <input type="hidden" id="userId" name="userId" value="{{$user->id}}">                                    
                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">Name: *</span>
                                        <div class="col-md-9">
                                            <input type="text" name="name" class="form-control valid" value="{{old('name', $user->name)}}" required="" aria-invalid="false">
                                            @error('name')
                                                <p class="error">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div> 
                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">Email: *</span>

                                        <div class="col-md-9">
                                            <input type="text" name="email" class="form-control valid" value="{{old('email', $user->email)}}" required="" aria-invalid="false">
                                            @error('email')
                                                <p class="error">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">License No:</span>

                                        <div class="col-md-9">
                                            <input type="text" readonly="" value="{{$user->license}}" class="form-control valid" aria-invalid="false">
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

                                    <div class="col-md-9">{{isset($planData[0]->plan_name)?$planData[0]->plan_name:''}}</div>
                                </div>
                                <div class="form-group row">
                                    <span class="label-text col-md-3 col-form-label">Trial:</span>
                                    <div class="col-md-9">
                                        @if(isset($planData[0]->is_trial))
                                            @if($planData[0]->is_trial == '1')
                                                Yes
                                            @else
                                                No
                                            @endif
                                        @endif
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <span class="label-text col-md-3 col-form-label">Started On:</span>
                                    <div class="col-md-9">{{isset($planData[0]->started_on)?$planData[0]->started_on:''}}</div>
                                </div>
                                <div class="form-group row">
                                    <span class="label-text col-md-3 col-form-label">Expired On:</span>
                                    <div class="col-md-9">{{isset($planData[0]->expired_on)?$planData[0]->expired_on:''}}</div>
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
       @include('Admin::layouts.main_footer')
     <!-- end footer -->
      <!-- Scripts -->
       @include('Admin::layouts.footer')
     <!-- Scripts -->


                <script>
                    $(document).ready(function(){
                        $(".responders-group-wise").click(function(){
                            var group_id = $(this).attr('groupid');
                            $.ajax({
                                type:"POST",
                                data:{"group_id":group_id,"_token": "{{ csrf_token() }}"},
                                url:"{{route('admin.groupResponders')}}",
                                success:function(response){
                                    $("#group_responsers").html(response);
                                }
                            });
                            $("#autorespondersModal").modal('show');
                        })
                    });
                </script>
@endsection
                
                