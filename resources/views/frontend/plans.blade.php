@extends('layouts.app')

@section('content')
	<div class="container">
<?php $vendor_id = false; ?>

	@if($count == 0 || $package_limit_already_complete)
		<div class="row text-center">
			<div class="col-md-12 head_txt text-danger">
				Link Expired
			</div>
		</div>
	@else
		<div class="row text-center">
			<div class="col-md-12 head_txt">
				Group Leads Plans
			</div>
		</div>
		@php
			$col_class = ' col-md-4 ';
			if($count == 2){$col_class =' col-md-6 ';}
			if($count == 3){$col_class =' col-md-4 ';}
			if($count == 4){$col_class =' col-md-3 ';}
		@endphp
		<div class="row align-items-center">
		  
			@foreach($plans_list as $plan)
	        <div class='{{$col_class}}'>
	            @php
	            	$type = "";
	            	if($plan->type == 'monthly'){
	            		$type = 'mo';
	            	}
	            	elseif($plan->type == 'yearly'){
	            		$type = 'yr';
	            	}
	            @endphp
	            <div class="pricing--item text-center mb-4">
	                <div class="pricing--header text-green text-uppercase">
	                    <h3 class="h3">{{$plan->name}}</h3>
	                </div>

	                <div class="pricing--title pricing--title--box text-white bg-green">
	                    <p><strong><sup>$</sup>{{$plan->price}}</strong>/{{$type}}</p>
	                </div>

	                <div class="pricing--features">
	                    <ul class="list-unstyled">
	                        <li>Easy to use</li>
	                        <li>No monthly zapier fees</li>
	                        <li>Groups allowed</li>
	                        <li>Up to {{$plan->fb_groups}} facebook groups</li>
	                        <li>Google sheet integration</li>
	                        <li>Email support</li>
	                        <li>Lifetime software updates</li>
	                    </ul>
	                </div>

	                <div class="pricing--action">
	                    <a href="#{{$plan->live_plan_id}}" data-theme="none" data-product="{{$plan->live_plan_id}}" class="btn btn-rounded btn-success paddle_button">Get Now</a>
	                </div>
	            </div>
	        </div>
	        <?php
	        $vendor_id = json_decode($plan->credentials, true)
	        ?>
	        
	        @endforeach
	     
	        
	    </div>
	    </div>
    @endif
    <style>
    	.head_txt{
    		font-family: "Poppins", sans-serif;
		    font-weight: 700;
		    font-size: 40px;
		    margin-bottom: 35px;
		    line-height: 1.2em;
		    color: #333333;
		    margin-top: 60px;
		}
    </style>
@endsection
<script src="https://cdn.paddle.com/paddle/paddle.js"></script>
<script type="text/javascript">
	Paddle.Setup({ vendor: <?php echo $vendor_id['vendor_id']; ?> });
</script>