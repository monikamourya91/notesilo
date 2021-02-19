@extends('Reseller::layouts.app')

@section('content')
 <!-- Wrapper Start -->
    <div class="wrapper">
       <!-- Navbar Start -->
       @include('Reseller::layouts.navbar')
        <!-- Navbar End -->

         <!-- Sidebar sart -->
          @include('Reseller::layouts.sidebar')
        <!-- Sidebar End -->
    <!-- Main Container Start -->
        <main class="main--container">
            <!-- Main Content Start -->
            <section class="main--content">
                <div class="panel">
                    <!-- Records Header Start -->
                    <div class="records--header">
                        
                        <div class="title">
                             <a href="javascript:void(0);" class="page_title"> Reseller Plans</a>
                             <a href="{{route('reseller.AddNewPlan')}}" class="btn btn-secondary cstm_add_btn"><i class="fa fa-plus-circle"></i> Add New</a>  
                        </div>
                    </div>
                    <!-- Records Header End -->
                </div>

                @if ($message = Session::get('success'))
                    <div class="alert alert-success">
                        <p>{{ $message }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        </p>
                        
                    </div>
                @endif
                @if ($message = Session::get('error'))
                    <div class="alert alert-danger">
                        <p>{{ $message }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>
                        </p>
                        
                    </div>
                @endif

                @if($PaymentGateway->isEmpty())
                    <p class="alert alert-danger">You have not added any payment mode. <a href="{{route('reseller.AddNewPaymetMode')}}" class="clickherelink">Click here</a> to add your first payment mode.</p>
                @else
                    <div class="panel">
                        <div class="records--header" style="padding:20px;">
                            <div class="col-md-2">
                                <span class="label-text">Payment Mode: *</span>
                            </div>
                            <div class="col-md-10">
                                @php
                                $gateway_name = "";
                                @endphp
                                <select class="form-control" required onchange="showPlans(this.value)">
                                    <option value="">Select Payment Mode</option>
                                    @foreach($PaymentGateway as $mode)
                                        @php if($gateway_id == $mode->id){$gateway_name = $mode->payment_type;} @endphp
                                        <option value="{{$mode->id}}" @if($gateway_id == $mode->id) selected @endif>{{ucfirst($mode->payment_type)}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <!--if gateway is seleted or exist in db only then display thi table-->
                    @if($gateway_id != "") 
                    <div class="panel">
                        <div class="records--list" data-title="{{$gateway_name}} Plans Listing">
                            <table id="recordsListView">
                                <thead>
                                    <tr>
                                        <th>S.NO</th>
                                        <th>Payment Mode</th>
                                        <th>Type</th>
                                        <th>Name</th>
                                        <th>Price</th>
                                        <th>Gateway Plan ID</th>
                                        <th>Trial</th>
                                        <th>Status</th>
                                        <th class="not-sortable">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i=1; ?>
                                   @foreach($plans as $plan)
                                        <tr>
                                            <td>{{$i}}</td>
                                            <td>{{ucfirst($plan->payment_type)}}</td>
                                             <td>{{ucfirst($plan->type)}}</td>
                                            <td>{{$plan->name}}</td>
                                            <td>{{$plan->price}}</td>
                                            <td>{{$plan->live_plan_id}}</td>
                                            <td>{{($plan->trial == 1) ? 'Yes' : 'No'}}</td>
                                            <td>
                                            @php
                                                $status=array(
                                                    '0'=>'Inactive',
                                                    '1'=>'Active'
                                                );
                                            @endphp
                                            @foreach($status as $s => $s_value)
                                                @if($plan->status==$s)
                                                    <a href="{{route('reseller.EditPlan',['id'=>$plan->id])}}" class="btn-link">
                                                    @if($s==0)
                                                        <span class="label label-danger">{{$s_value}}</span></a>
                                                    @elseif($s==1)
                                                        <span class="label label-success">{{$s_value}}</span>
                                                    @endif
                                                    </a>
                                                 @endif
                                            @endforeach 
                                            </td>
                                            <td>
                                                <div class="dropleft">
                                                    <a href="#" class="btn-link" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></a>
                                                    <div class="dropdown-menu">
                                                        <a href="{{route('reseller.EditPlan',['id'=>$plan->id])}}" class="dropdown-item"><button class="btn btn-rounded btn-outline-info btn-st">Edit</button></a>
                                                        @php
                                                        if (!in_array($plan->id, $plansAlreadyInUse)){
                                                        @endphp
                                                        <a href="javascript:void(0)" onclick="deleteConfirmation('{{route('reseller.DeletePlan',['id'=>$plan->id])}}','payment_mode')"; class="dropdown-item"><button class="btn btn-rounded btn-outline-info btn-st">Delete</button></a>
                                                        @php
                                                        }
                                                        @endphp  
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                        <?php $i++; ?>
                                   @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- Records List End -->
                    </div>
                    @endif
                @endif   
                

            </section>
            <!-- Main Content End -->
    <!-- footer -->
       @include('Reseller::layouts.main_footer')
     <!-- end footer -->
      <!-- Scripts -->
       @include('Reseller::layouts.footer')
     <!-- Scripts -->
     <script type="text/javascript">
        function showPlans(gateway_id)
        {
            if(gateway_id == ""){
                return false;
            }
            window.location.href= "{{route('reseller.plansList')}}/"+gateway_id;
        }
     </script>
@endsection
