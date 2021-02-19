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
            <!-- Page Header Start -->
            <section class="page--header">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-6">
                            <!-- Page Title Start -->
                            <h2 class="page--title h5">Ads</h2>
                            <!-- Page Title End -->

                            <ul class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/ads') }}">Ad</a></li>
                                <li class="breadcrumb-item active"><span>View Ads</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Page Header End -->

            <<!-- Main Content Start -->
            <section class="main--content">
                <div class="panel">
                    <!-- Records Header Start -->
                    <div class="records--header">
                        <div class="title fa-shopping-bag">
                            <h3 class="h3">Ad - <a href="#" class="btn btn-sm btn-outline-info">{{$ads['title']}}</a></h3>
                        </div>
                    </div>
                    <!-- Records Header End -->
                </div>
                
                <div class="panel">

                    <!-- View Order Start -->
                    <div class="records--body">
                        <!-- <div class="title">
                            <h6 class="h6">Order #052656225<span class="text-lightergray"> - June 15, 2017 02:30</span></h6>
                        </div> -->

                        <!-- Tabs Nav Start -->
                        <ul class="nav nav-tabs">
                            <li class="nav-item">
                               <!--  <a href="#tab01" data-toggle="tab" class="nav-link active">Overview</a> -->
                                <a href="#tab01" data-toggle="tab" class="nav-link" id="overview">Overview</a>
                            </li>
                            <li class="nav-item">
                                <a href="#tab02" data-toggle="tab" class="nav-link" id="seller">Seller Details</a>
                            </li>
                        </ul>
                        <!-- Tabs Nav End -->

                        <!-- Tab Content Start -->
                        <div class="tab-content" >
                            <!-- Tab Pane Start -->
                            <div class="tab-pane fade show active" >
                             <div id="tabOverview">
                                 <h4 class="subtitle">Quick Stats</h4>
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-simple">
                                            <tbody>
                                                <tr>
                                                    <td>Categories:</td>
                                                    <th>{{$ads['category_name']}}</th>
                                                </tr>

                                            @foreach($ads['customfield'] as $label)
                                                

                                                <tr>
                                                    <td> {{$label['validations']['label']}}:</td>
                                                    <th>{{$label['value']}}</th>
                                                </tr>
                                            @endforeach 
                                            <tr>
                                                    <td>Status:</td>
                                                    @if($ads['status']==0)
                                                        <th>Pending</th>
                                                    @elseif($ads['status']==1) 
                                                        <th>Approved</th>
                                                    @else  
                                                        <th>Rejected</th> 
                                                    @endif       
                                                </tr>   
                                                
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="col-md-6">
                                        <table class="table table-simple">
                                            <tbody>
                                                
                                                <tr>
                                                    <td> Price:</td>
                                                    <th>{{$ads['price']}} {{$ads['currency']}}</th>
                                                </tr>
                                                
                                                <tr>
                                                    <td>Is Negotiate:</td>
                                                    @if($ads['is_negotiate']==0)
                                                        <th>No</th>
                                                    @elseif($ads['is_negotiate']==1) 
                                                        <th>Yes</th>
                                                    @endif       
                                                </tr>
                                                <tr>
                                                    <td>Ad Posted on:</td>
                                                    <th>{{date('d M,Y ',strtotime($ads['created_at']))}}</th>

                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <br>

                                <h4 class="subtitle">Description</h4>
                                <div class="row" >
                                    <div style="margin-left: 39px;">{{$ads['description']}}</div>
                                </div>


                                <h4 class="subtitle">Images</h4>
                                <div class="row">
                                    @if(count($ads['images'])>0)
                                        @foreach($ads['images'] as $key => $img)
                                    <div class="col-lg-3 col-md-4 col-xs-6 thumb">
                                        <a href="{{$img->image_path}}?auto=compress&cs=tinysrgb&h=650&w=940" class="fancybox" rel="ligthbox">
                                            <img  src="{{$img->image_path}}?auto=compress&cs=tinysrgb&h=650&w=940" class="zoom img-fluid "  alt="adImage">
                                        </a>
                                    </div>
                                        @endforeach
                                    @endif
                                </div> 
                            </div>
                            
                            <!-- Tab Pane End -->

                            <!-- Tab Pane Start -->
                            <div id="tabSeller">
                                <div class="tab-pane fade" id="tab02"  >
                                            <h4 class="subtitle">Seller Information</h4>
                                    <div class="row">
                                        <div class="col-md-6">

                                            <table class="table table-simple">
                                                <tbody>
                                                    <tr>
                                                    @if(!empty($ads['seller_details']))
                                                        <td>Image:</td>
                                                        
                                                        <th>
                                                            
                                                          <img src="{{$ads['seller_details']['image_path']}}" alt="image"  height="100" width="100">              
                                                        </th>
                                                    </tr>
                                                   
                                                    <tr>
                                                        <td>Primary Name:</td>
                                                        <th><a href="{{url('/user/'.$ads['seller_id'])}}" class="btn-link">{{$ads['seller_details']['name']}} {{$ads['seller_details']['last_name']}}</a></th>
                                                    </tr>
                                                    <tr>
                                                        <td>Secondary Name:</td>
                                                        <th>{{$ads['name']}}</th>
                                                    </tr>
                                                    <tr>
                                                        <td>Email:</td>
                                                        <th>{{$ads['seller_details']['email']}}</a></th>
                                                    </tr>
                                                    <tr>
                                                        <td>Username:</td>
                                                        <th>{{$ads['seller_details']['username']}}</a></th>
                                                    </tr>
                                                    <tr>
                                                        <td>Website:</td>
                                                        <th>{{$ads['seller_details']['website']}}</a></th>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <div class="col-md-6">

                                            <table class="table table-simple">
                                                <tbody>
                                                    <tr>
                                                       <td>State:</td>
                                                        <th>{{$ads['state_name']}}</th>
                                                    </tr> 
                                                    
                                                    <tr>
                                                       <td>City:</td>
                                                        <th>{{$ads['city_name']}}</th>
                                                    </tr> 
                                                    <tr>
                                                        <td>Address:</td>
                                                        <th>{{$ads['seller_details']['address_line']}}</a></th>
                                                    </tr>
                                                    <tr>
                                                        <td>Zip Code:</td>
                                                        <th>{{$ads['seller_details']['zip_code']}}</a></th>
                                                    </tr>
                                                    <tr>
                                                        <td>Primary Phone Number:</td>
                                                        <th>{{$ads['seller_details']['phone_no']}}</a></th>
                                                    </tr>
                                          @endif                
                                                    <tr>
                                                        <td>Secondary Phone Number:</td>
                                                        <th>{{$ads['phone_no']}}</a></th>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- Tab Pane End -->
                            </div>
                        </div>
                        <!-- Tab Content End -->
                    </div>
                    <!-- View Order End -->
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
