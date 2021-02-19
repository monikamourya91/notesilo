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
    </div>
    <!-- Wrapper End -->

        <!-- Main Container Start -->
        <main class="main--container">
     

            <!-- Main Content Start -->

            <section class="main--content">
                <div class="row gutter-20">
                    <div class="col-lg-8">
                        <div class="panel">
                            <div class="panel-heading">
                                <h3 class="panel-title">Subscription</h3>
                            </div>
                            <div class="panel-content panel-about">
                                <div class="row">
                                    <div class="col-md-3 label_color">Package Name: </div>
                                    <div class="col-md-6 info_color">{{$subscription_info->name}}</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 label_color">Subscription ID: </div>
                                    <div class="col-md-6 info_color">{{$subscription_info->payment_subscription_id}}</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 label_color">Started On: </div>
                                    <div class="col-md-6 info_color">{{$subscription_info->started_on}}</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 label_color">Expired On: </div>
                                    <div class="col-md-6 info_color">{{$subscription_info->expired_on}}</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 label_color">License Limit: </div>
                                    <div class="col-md-6 info_color">
                                        @if($subscription_info->package_limit == 50000) 
                                            Unlimited 
                                        @else
                                            {{$subscription_info->package_limit}}
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>    

                        <div class="panel">
                            <div class="panel-heading">
                                <h3 class="panel-title">Usage</h3>
                            </div>
                            <div class="panel-content panel-about">
                                <div class="row">
                                    <div class="col-md-3 label_color">Used Licenses: </div>
                                    <div class="col-md-6 info_color">{{$used_licenses}}</div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 label_color">Paid Subscribers: </div>
                                    <div class="col-md-6 info_color">{{$paid_subscribers}}</div>
                                </div>
                            </div>
                        </div>                    
                    </div>

                    <div class="col-lg-4">
                        <!-- Panel Start -->
                        <div class="panel">
                            <div class="panel-heading">
                                <h3 class="panel-title">About Me</h3>
                            </div>

                            <div class="panel-content panel-about">
                                <table>
                                    <tr>
                                        <th><i class="fa fa-user"></i>Name</th>
                                        <td>{{ $adminData['name'] }}</td>
                                    </tr>
                                    <tr>
                                        <th><i class="fa fa-envelope"></i>Email</th>
                                        <td>{{ $adminData['email'] }}</td>
                                    </tr>                                
                                </table>
                            </div>

                            <!-- <div class="panel-social">
                                <ul class="nav">
                                    <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                    <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                    <li><a href="#"><i class="fab fa-google-plus-g"></i></a></li>
                                    <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                    <li><a href="#"><i class="fab fa-behance"></i></a></li>
                                    <li><a href="#"><i class="fab fa-dribbble"></i></a></li>
                                </ul>
                            </div> -->
                        </div>
                        <!-- Panel End -->

                        

       
                    </div>
                </div>
                <style>
                    .label_color{color:#2bb3c0;font-weight:bold;}
                    .info_color{color:#333;font-weight: 600;}
                    .panel-about .row{margin-bottom: 15px;}
                </style>
            </section>
           
        @include('Reseller::layouts.main_footer')

        <!-- Main Container End -->
          <!-- Scripts -->
           @include('Reseller::layouts.footer')
         <!-- Scripts -->
@endsection
