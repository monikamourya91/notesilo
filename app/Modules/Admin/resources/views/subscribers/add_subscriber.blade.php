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
    </div> 

    <main class="main--container">
            <section class="main--content">
                <div class="panel">
                    <div class="records--body">
                        <div class="title">
                            <h6 class="h6">New Subscriber.</h6>                  
                        </div>
                         @if(!empty($error_msg))
                            <p class="alert alert-danger">{{$error_msg}}</p>
                        @else
                        <div class="tab-content">
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

                                <form id="admin_add_subscriber" action="{{ route('admin.StoreNewSubscriber') }}" method="POST">
                                    {{ csrf_field() }}
                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">Email: *</span>
                                        <div class="col-md-9">
                                            <input type="text" value="{{ old('email') }}" name="email" class="form-control @error('email') error @enderror" required1>
                                            @error('email')
                                                <p class="error">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">Name: *</span>
                                        <div class="col-md-9">
                                            <input type="text" value="{{ old('name') }}" name="name" class="form-control @error('name') error @enderror" required1>
                                            @error('name')
                                                <p class="error">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">Select Plan: *</span>
                                        <div class="col-md-9">
                                            <select name="plan" class="form-control @error('plan') error @enderror" required1>
                                                <option value="">Select Plan</option>
                                                @foreach($plans as $plan)
                                                    <option value="{{$plan->id}}" 
													
													@if(old('plan') == $plan->id) selected @endif>{{$plan->name}} (${{$plan->price}})
													<?php if($plan->trial) echo "- Trial"; ?>
												
													
													</option>
                                                @endforeach
                                            </select>
                                             @error('plan')
                                                <p class="error">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">Trial: *</span>
                                        <div class="col-md-9">
                                            <input type="checkbox" value="1" name="is_trial" class="form-control cstm_checkbox" required1 @if(old('is_trial') == '1') checked @endif>
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-3">
                                        <div class="col-md-9 offset-md-3">
                                            <input type="submit" value="Add" class="btn btn-rounded btn-success">
                                        </div>
                                    </div>
                                </form>
                                
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </section>
            <!-- Main Content End -->

            @include('Admin::layouts.main_footer')
        <!-- Main Container End -->
          <!-- Scripts -->
           @include('Admin::layouts.footer')
         <!-- Scripts -->
@endsection
