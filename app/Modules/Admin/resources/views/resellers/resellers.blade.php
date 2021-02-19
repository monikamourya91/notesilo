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
                    <!-- Records Header Start -->
                    <div class="records--header">
                        
                        <div class="title">
                             <a href="javascript:void(0);" class="page_title"> Resellers</a> 
                        </div>

                        <div class="actions">
                            <form action="{{url('/user')}}" method="get" class="search">
                                {{ csrf_field() }}
                                <input id='search-email' type="text" class="form-control" name="email" placeholder="Email..." required>
                                <button type="submit" class="btn btn-rounded"><i class="fa fa-search"></i></button>
                            </form>
                        </div>
                    </div>
                    <!-- Records Header End -->
                </div>

                <div class="panel">

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
                              @foreach($resellers as $reseller)
                                <tr>
                                    <td>{{$i}}</td>
                                    <td>
                                        <a href="{{route('admin.resellerView',['id'=>$reseller->id])}}" class="btn-link">{{$reseller->name}}</a>
                                    </td>
                                    <td><a href="{{route('admin.resellerView',['id'=>$reseller->id])}}" class="btn-link">{{$reseller->email}}</a></td>
                                    <td>{{$reseller->created_at}}</td>
                                    @php
                                    $status=array(
                                        '0'=>'Inactive',
                                        '1'=>'Active'
                                        );
                                    @endphp
                                    <td>
                                        @foreach($status as $s => $s_value)
                                           @if($reseller->status==$s)
                                                <a href="javascript:void(0)" class="btn-link">
                                                 @if($s==0)
                                                        <span class="label label-danger">{{$s_value}}</span></a>
                                                    @elseif($s==1)
                                                        <span class="label label-success">{{$s_value}}</span></a>
                                                    @endif
                                                    
                                            @endif
                                        @endforeach 
                                    </td>
                                    <!-- <td>                                        <a href="{{ url('/user') }}/{{ $reseller->id }}" class="btn-link">{{$reseller->created_at}}</a></td> -->
                                    
                                    <td>
                                        <div class="dropleft">
                                            <a href="#" class="btn-link" data-toggle="dropdown"><i class="fa fa-ellipsis-v"></i></a>

                                            <div class="dropdown-menu">
                                                <a href="{{route('admin.resellerView',['id'=>$reseller->id])}}" class="dropdown-item"><button class="btn btn-rounded btn-outline-info btn-st">View</button></a>
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
       @include('Admin::layouts.main_footer')
     <!-- end footer -->
      <!-- Scripts -->
       @include('Admin::layouts.footer')
     <!-- Scripts -->
@endsection
