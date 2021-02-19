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

      <h2>Admin profile data</h2>
    </div> 

    <main class="main--container">
            <section class="main--content">
                <a href="@if($backlink) {{$backlink}} @endif" style="margin-left:0px;margin-bottom:20px;" class="btn btn-secondary cstm_add_btn"><i class="fa fa-arrow-left" aria-hidden="true"></i> Back</a>
                <p class="alert alert-danger">
                    Unauthorized Access 
                </p>
            </section>
            <!-- Main Content End -->

            @include('Reseller::layouts.main_footer')
        <!-- Main Container End -->
          <!-- Scripts -->
           @include('Reseller::layouts.footer')
         <!-- Scripts -->
@endsection
