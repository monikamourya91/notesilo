
var apiBaseUrl = 'http://localhost/page2lead/api';

$(document).ready(function(){
	$('#expire_on_datepicker').datepicker({
		"dateFormat" : "yy-mm-dd",
		"minDate": "0",
	});

	$("#reseller_add_payment_mode").validate({
		rules: {
			payment_mode: {
				required: true,
			},
			vendor_id:{
				required: true,
			},
			api_key:{
				required: true,
			}
		},
	  	messages: {
			payment_mode: {
				required: "Payment mode is required",
			},
			vendor_id:{
				required: "Vendor ID is required",
			},
			api_key:{
				required: "API key is required",
			}
		},
		submitHandler: function(form) {
			$("input[type='submit']").val('Verifying..');
			form.submit();
		}
	});//reseller add payment end

	$("#reseller_edit_payment_mode").validate({
		rules: {
			payment_mode: {
				required: true,
			},
			vendor_id:{
				required: true,
			},
			api_key:{
				required: true,
			}
		},
	  	messages: {
			payment_mode: {
				required: "Payment mode is required",
			},
			vendor_id:{
				required: "Vendor ID is required",
			},
			api_key:{
				required: "API key is required",
			}
		},
		submitHandler: function() {  
			$("input[type='submit']").val('Verifying..');           
			form.submit();
		}
	});//reseller edit payment end

	$("#reseller_add_plan").validate({
		rules: {
			payment_mode: {
				required: true,
			},
			plan_id:{
				required: true,
			}
		},
	  	messages: {
			payment_mode: {
				required: "Payment mode is required",
			},
			plan_id:{
				required: "Plan ID is required",
			}
		},
		submitHandler: function() {
			$("input[type='submit']").val('Verifying..');            
			form.submit();
		}
	}); //reseller add plan end

	$("#reseller_edit_plan").validate({
		rules: {
			payment_mode: {
				required: true,
			},
			plan_id:{
				required: true,
			}
		},
	  	messages: {
			payment_mode: {
				required: "Payment mode is required",
			},
			plan_id:{
				required: "Plan ID is required",
			}
		},
		submitHandler: function() {   
			$("input[type='submit']").val('Verifying..');             
			form.submit();
		}
	}); //reseller edit plan end

	/*$(".verify_plan").click(function(){
		var payment_mode = $("#reseller_add_plan select[name='payment_mode']").val();
		var plan_id = $("#reseller_add_plan input[name='plan_id']").val();
		if(payment_mode == "" || plan_id == ""){
			alert("Payment mode and plan id can't be blank.");
			return false;
		}
		$('.verify_plan').val('Verifying..');
		$.ajax({
	        type: 'POST',
	        url: apiBaseUrl+'/live-plan-details',
	        data: {payment_mode:payment_mode,plan_id:plan_id},
	        dataType: 'json',
	        success: function(response){ //
	        	console.log(response);
	        	if(response.success){
	        		$('.verify_plan').hide();
	        		$("input[name='name']").val(response.response[0]['name']);
	        		if(response.response[0]['billing_type'] == 'month'){
	        			$("input[name='type']").val('Monthly');
	        		}else if(response.response[0]['billing_type'] == 'year'){
	        			$("input[name='type']").val('Yearly');
	        		}
	        		$("input[name='price']").val(response.response[0]['recurring_price']['USD']);
	        		if(response.response[0]['trial_days'] == '0'){ 
	        			var trial = "No";
	        		}else{
	        			var trial = "Yes ("+response.response[0]['trial_days']+" days)"
	        		}
	        		$('#trial_txt').html(trial);
	        		$('.form-group.row').show();
	        	}else{
	        		$('.live_plan_response').html('<div class="alert alert-danger"><p>Plan id is invalid.</p></div>');
	        		$('.verify_plan').val('Verify');
	        	}
	        }
	    });
		//console.log(payment_mode+" "+plan_id);
	});*/

}); // document.ready end