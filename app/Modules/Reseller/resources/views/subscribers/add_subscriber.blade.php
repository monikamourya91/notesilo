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
                    <div class="records--body">
                        <div class="title">
                            <h6 class="h6">New Subscriber</h6>                  
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

                                <form id="reseller_add_subscriber" action="{{ route('reseller.StoreNewSubscriber') }}" method="POST" novalidate="novalidate">
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
                                                    <option value="{{$plan->id}}" @if(old('plan') == $plan->id) selected @endif>{{$plan->name}}</option>
                                                @endforeach
                                            </select>
                                             @error('plan')
                                                <p class="error">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">Expire On: </span>
                                        <div class="col-md-9">
                                            <input type="text" value="{{ old('expired_on') }}" name="expired_on" class="form-control @error('expired_on') error @enderror" id="expire_on_datepicker" autocomplete="off" required1 placeholder="">
                                            <p style="font-size: 12px;">*If expiry date is not selected then subscriber will be active as far as the reseller account is active.</p>
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

            @include('Reseller::layouts.main_footer')
        <!-- Main Container End -->
          <!-- Scripts -->
           @include('Reseller::layouts.footer')
         <!-- Scripts -->
@endsection
