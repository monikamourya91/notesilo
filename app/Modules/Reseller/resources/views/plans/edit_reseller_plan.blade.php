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
                            <h6 class="h6">Edit Plan</h6>                  
                        </div>
                     
                        <div class="tab-content">
                            <div class="tab-pane fade show active" id="tab01">
                                @if(!$editable)
                                    <div class="alert alert-danger">
                                        <p>Subscribers are using this plan, you can't edit this plan.</p>
                                    </div>
                                @endif
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
                                <form id="reseller_edit_plan" action="{{ route('reseller.UpdatePlan') }}" method="POST">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="id" value="{{$plan->id}}">
                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">Payment Mode: *</span>
                                        <div class="col-md-9">
                                            <select class="form-control @error('payment_mode') error @enderror" name="payment_mode" onchange="changePaymentMode()" id="PlanPaymentMode" @if(!$editable) disabled @endif>
                                                <option value="">Select Payment Mode</option>
                                                @foreach($PaymentGateway as $mode)
                                                    <option value="{{$mode->id}}" @if(old('payment_mode',$plan->payment_gateway_id) == $mode->id) selected @endif> {{ucfirst($mode->payment_type)}}</option>
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
                                            <input type="text" value="{{ old('plan_id',$plan->live_plan_id) }}" name="plan_id" class="form-control @error('plan_id') error @enderror" required1  @if(!$editable) readonly @endif>
                                             @error('plan_id')
                                                <p class="error">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">Plan Name: *</span>
                                        <div class="col-md-9">
                                            <input type="text" value="{{ old('name',$plan->name) }}" name="name" class="form-control @error('name') error @enderror" required1 readonly>
                                            @error('name')
                                                <p class="error">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">Type: *</span>
                                        <div class="col-md-9">
                                             <input type="text" value="{{ old('type',$plan->type) }}" name="type" class="form-control @error('name') error @enderror" required1 readonly>
                                            @error('type')
                                                <p class="error">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <span class="label-text col-md-3 col-form-label">Price: *</span>
                                        <div class="col-md-9">
                                            <input type="text" value="{{ old('price',$plan->price) }}" name="price" class="form-control @error('price') error @enderror" required1 readonly>
                                             @error('price')
                                                <p class="error">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        @php
                                            $status = old('status',$plan->status);
                                        @endphp
                                        <span class="label-text col-md-3 col-form-label">Status: *</span>
                                        <div class="col-md-9">
                                        <label style="display:inline-block"><input type="radio" id="" name="status" value="1" {{($status == "1") ? 'checked':'' }}> Active</label> &nbsp;
                                        <label style="display:inline-block"><input type="radio" id="" name="status" value="0" {{($status == "0") ? 'checked':'' }}> Inactive</label>
                                            @error('status')
                                                <p class="error">{{$message}}</p>
                                            @enderror
                                        </div>
                                    </div>
                                    @if($editable)
                                    <div class="row mt-3">
                                        <div class="col-md-9 offset-md-3">
                                            <input type="submit" value="Verify and Update" class="btn btn-rounded btn-success">
                                        </div>
                                    </div>
                                    @endif
                                </form>
                            </div>
                        </div>
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
