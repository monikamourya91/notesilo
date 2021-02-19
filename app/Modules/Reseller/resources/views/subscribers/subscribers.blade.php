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
                             <a href="javascript:void(0);" class="page_title"> Subscribers</a> 
                             <a href="{{route('reseller.subscriberAdd')}}" class="btn btn-secondary cstm_add_btn"><i class="fa fa-plus-circle"></i> Add New</a>
                        </div>

                        <div class="actions">
                            <form action="{{route('reseller.subscribersList')}}" method="get" class="search">
                                <input id='search-email' type="text" class="form-control" name="email" placeholder="Email..." required value="@if(isset($_GET['email'])) {{$_GET['email']}} @endif" >
                                <button type="submit" class="btn btn-rounded"><i class="fa fa-search"></i></button>
                            </form>
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
                <div class="panel">
                    <!-- Records List Start -->
                    <div class="records--list" data-title="Subscribers Listing">
                        <table id="recordsListView">
                            <thead>
                                <tr>
                                    <th>S.NO</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>License No.</th>
                                    <th>Subscribed Plan</th>
                                    <th>Created On</th>
                                    <th>Status</th>
                                    <th class="not-sortable">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 1;  
                                ?>  
                              @foreach($users as $user)
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>
                                        <a href="{{route('reseller.subscriberView',['id'=>$user->id])}}" class="btn-link">{{$user->name}}</a>
                                    </td>
                                    <td><a href="{{route('reseller.subscriberView',['id'=>$user->id])}}" class="btn-link">{{$user->email}}</a></td>
                                    <td>{{$user->license}}</td>
                                    <td>{{$user->plan_name}}</td>
                                    <td>{{$user->created_at}}</td>
                                    @php
                                    $status=array(
                                        '0'=>'Inactive',
                                        '1'=>'Active'
                                        );
                                    @endphp
                                    <td>
                                        @foreach($status as $s => $s_value)
                                           @if($user->status==$s)
                                                <a href="javascript:void(0)" class="btn-link">
                                                 @if($s==0)
                                                        <span class="label label-danger">{{$s_value}}</span></a>
                                                    @elseif($s==1)
                                                        <span class="label label-success">{{$s_value}}</span></a>
                                                    @else
                                                        <span class="label label-warning">{{$s_value}}</span></a>
                                                    @endif
                                                    
                                            @endif
                                        @endforeach 
                                    </td>
                                    <!-- <td>                                        <a href="{{ url('/user') }}/{{ $user->id }}" class="btn-link">{{$user->created_at}}</a></td> -->
                                    
                                    <td>
                                        <div class="dropleft">
                                            <a href="#" class="btn-link" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></a>

                                            <div class="dropdown-menu">
                                                <a href="{{route('reseller.subscriberView',['id'=>$user->id])}}" class="dropdown-item"><button class="btn btn-rounded btn-outline-info btn-st">View</button></a>
                                                {{--<a href="{{url('/user/'.$user->id.'/edit')}}" class="dropdown-item"><button class="btn btn-rounded btn-outline-info btn-st">Upgrade/Downgrade</button></a>
                                                <a href="{{url('/user/'.$user->id.'/edit')}}" class="dropdown-item"><button class="btn btn-rounded btn-outline-info btn-st">Cancel Subscription</button></a>--}}
                                                <a href="javascript:void(0)" onclick="sendLicenseConfirmation('{{route('reseller.sendLicense',['id'=>$user->id])}}','{{$user->email}}')" class="dropdown-item"><button class="btn btn-rounded btn-outline-info btn-st">Send License</button></a>
                                                
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
            </section>
            <!-- Main Content End -->
    <!-- footer -->
       @include('Reseller::layouts.main_footer')
     <!-- end footer -->
      <!-- Scripts -->
       @include('Reseller::layouts.footer')
     <!-- Scripts -->
@endsection
