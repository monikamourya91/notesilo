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

                @if($PaymentGateway->isEmpty())
                    <p class="alert alert-danger">You don't have any active payment mode. <a href="{{route('reseller.AddNewPaymetMode')}}" class="clickherelink">Click here</a> to add your payment mode.</p>
                @else

                <div class="panel">
                    <div class="records--body">
                        <div class="title">
                            <h6 class="h6">New Plan</h6>                  
                        </div>
                     
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="tab01">
                                <div class="live_plan_response"></div>
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
                                <form
                                 id="reseller_add_plan" action="{{ route('reseller.StoreNewPlan') }}" method="POST">
                                    {{ csrf_field() }}
                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">Payment Mode: *</span>
                                        <div class="col-md-9">
                                            <select class="form-control @error('payment_mode') error @enderror" name="payment_mode" onchange="changePaymentMode()" id="PlanPaymentMode">
                                                <option value="">Select Payment Mode</option>
                                                @foreach($PaymentGateway as $mode)
                                                    <option value="{{$mode->id}}" @if(old('payment_mode') == $mode->id) selected @endif> {{ucfirst($mode->payment_type)}}</option>
                                                @endforeach
                                            </select>
                                             @error('payment_mode')
                                                <p class="error">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label"><span id="payment_mode_label"></span> Plan ID: *</span>
                                        <div class="col-md-9">
                                            <input type="text" value="{{ old('plan_id') }}" name="plan_id" class="form-control @error('plan_id') error @enderror" required1>
                                             @error('plan_id')
                                                <p class="error">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row" style="display:none">
                                        <span class="label-text col-md-3 col-form-label">Plan Name: *</span>
                                        <div class="col-md-9">
                                            <input type="text" value="{{ old('name') }}" name="name" class="form-control @error('name') error @enderror" required1 readonly>
                                            @error('name')
                                                <p class="error">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row" style="display:none">
                                        <span class="label-text col-md-3 col-form-label">Type: *</span>
                                        <div class="col-md-9">
                                            <input type="text" value="{{ old('type') }}" name="type" class="form-control @error('type') error @enderror" required1 readonly>
                                            <!--<select class="form-control @error('type') error @enderror" name="type">
                                                <option value="">Select Type</option>
                                                <option value="monthly" @if(old('type') == 'monthly') selected @endif>Monthly</option>
                                                <option value="yearly" @if(old('type') == 'yearly') selected @endif>Yearly</option>
                                            </select>-->
                                             @error('type')
                                                <p class="error">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row" style="display:none">
                                        <span class="label-text col-md-3 col-form-label">Price: *</span>
                                        <div class="col-md-9">
                                            <input type="text" value="{{ old('price') }}" name="price" class="form-control @error('price') error @enderror" required1 readonly>
                                             @error('price')
                                                <p class="error">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row" style="display:none">
                                        <span class="label-text col-md-3 col-form-label">Trial:</span>
                                        <div class="col-md-9" id="trial_txt">
                                            
                                        </div>
                                    </div>
                                    <div class="form-group row mt-3">
                                        <div class="col-md-9 offset-md-3">
                                            <input type="submit" value="Verify and Save" class="btn btn-rounded btn-success">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </section>
            <!-- Main Content End -->

            @include('Reseller::layouts.main_footer')
        <!-- Main Container End -->
          <!-- Scripts -->
           @include('Reseller::layouts.footer')
         <!-- Scripts -->
@endsection
