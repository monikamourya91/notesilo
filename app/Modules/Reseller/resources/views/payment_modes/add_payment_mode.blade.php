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
                            <h6 class="h6">New Payment Mode</h6>                  
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
                                <form id="reseller_add_payment_mode" action="{{ route('reseller.StoreNewPaymentMode') }}" method="POST">
                                    {{ csrf_field() }}
                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">Payment Mode: *</span>
                                        <div class="col-md-9">
                                            <select name="payment_mode" class="form-control @error('payment_mode') error @enderror">
                                                <option value="">Select Payment Mode</option>
                                                @foreach(config('constant.payment_types') as $payment_type)
                                                    <option value="{{strtolower($payment_type)}}" @if(old('payment_mode') == $payment_type) selected @endif>{{ucfirst($payment_type)}}</option>
                                                @endforeach
                                            </select>
                                             @error('payment_mode')
                                                <p class="error">{{$message}}</p>
                                            @enderror
                                            <!--<input type="text" value="{{ old('payment_mode') }}" name="payment_mode" class="form-control @error('payment_mode') error @enderror" required1>-->
                                           
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">Vendor ID: *</span>
                                        <div class="col-md-9">
                                            <input type="text" value="{{ old('vendor_id') }}" name="vendor_id" class="form-control @error('vendor_id') error @enderror" required1>
                                             @error('vendor_id')
                                                <p class="error">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">API Key: *</span>
                                        <div class="col-md-9">
                                            <input type="text" value="{{ old('api_key') }}" name="api_key" class="form-control @error('api_key') error @enderror" required1>
                                             @error('api_key')
                                                <p class="error">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="row mt-3">
                                        <div class="col-md-9 offset-md-3">
                                            <input type="submit" value="Verify and Save" class="btn btn-rounded btn-success">
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
