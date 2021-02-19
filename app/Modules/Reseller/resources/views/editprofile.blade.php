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

    <main class="main--container">
            <section class="main--content">
                <div class="panel">

                    <!-- Edit Product Start -->
                    <div class="records--body">
                        <div class="title">
                            <h6 class="h6">Edit Profile</h6>                  
                        </div>
                     
                        <!-- Tab Content Start -->
                        <div class="tab-content">
                            <!-- Tab Pane Start -->
                            <div class="tab-pane fade show active" id="tab01">
                                @if ($message = Session::get('success'))
                                    <div class="alert alert-success">
                                        <p>{{ $message }}</p>
                                    </div>
                                @endif
                                @if ($message = Session::get('error'))
                                    <div class="alert alert-danger">
                                        <p>{{ $message }}</p>
                                    </div>
                                @endif
                                <form id="profile-reseller-edit" action="{{ route('reseller.updateProfile',['id' => $admin->id]) }}" method="post">
                                    {{ csrf_field() }}
                                    
                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">Name: *</span>
                                        <div class="col-md-9">
                                            <input type="text" value="{{ $admin->name }}" name="name" class="form-control" required>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">Email: *</span>

                                        <div class="col-md-9">
                                            <input type="email" value="{{ $admin->email }}" name="email" class="form-control" required readonly>
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

                            

                           
                        </div>
                        <!-- Tab Content End -->
                    </div>
                    <!-- Edit Product End -->
                </div>
            </section>
            <!-- Main Content End -->

            @include('Reseller::layouts.main_footer')
        <!-- Main Container End -->
          <!-- Scripts -->
           @include('Reseller::layouts.footer')
         <!-- Scripts -->
@endsection
