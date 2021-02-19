//var apiBaseUrl = 'https://barterit.org/admin/api';
//var apiBaseUrl = 'https://barterit.org/api';
//var apiBaseUrl = 'http://localhost/barterit/api';
//var url ='http://localhost/barterit/';

var apiBaseUrl = 'https://barterit.org/admin/api';

var hiddenHtml;

var custom_fields =[];

$( document ).ready(function() { 

	

	
	$('#back-user-list').on('click',function(){
        window.history.back();
    });
    
    
    $('.model_close').click(function(){
        $("#rejectStatus-form")[0].reset();
        $('#basicModal').hide();
    })

    setTimeout(function(){
		$(".alert.alert-success").hide();
	}, 2000);

	setTimeout(function(){
		$(".alert.alert-danger").hide();
	}, 2000);

    $('#country_id').on('change',function(){
    	$id=$(this).val();
    	$('#state_id').find('option').remove();
    	$('#city_id').find('option').remove();
    	$.ajax({
            type: "GET",
            url: apiBaseUrl+"/getstate/"+$id,
            dataType: 'json',
        }).done(function(response) {
            console.log(response);
        var  html ='<option value="">Please Select State</option>';
            if (response.status == true) {
              	response.state.forEach(function(item, index){
           		 	html +=`<option value="`+item.id+`">`+item.name+`</option>`;
       		 	});
       		 	console.log(html);
       		 	$('#state_id').append(html);
            } 
        });
    	
    });

    $('#state_id').on('change',function(){
    	$id=$(this).val();
    	$('#city_id').find('option').remove();
    	$.ajax({
            type: "GET",
            url: apiBaseUrl+"/getcity/"+$id,
            dataType: 'json',
        }).done(function(response) {
        	html ='<option value="">Please Select city</option>';
            if (response.status == true) {
              	response.city.forEach(function(item, index){
           		 	html +=`<option value="`+item.id+`">`+item.name+`</option>`;
       		 	});
       		 	$('#city_id').append(html);
            } 
        });
    	
    })

	$("#add-user-form").validate({
		rules: {
			firstName: {
				required: true
			},
			lastName: {
				required: true
			},
			email: {
				required: true,
				email: true,
					remote: {
	                    type: 'POST',
	                    url: apiBaseUrl+'/validateUserEmailCheck',
	                    data: {
	                        'id': function() {
	                           return $('#add-user-form input.email').val();
	                        }
	                    },
	                    dataType: 'json'
	                }
			},
			phone_no: {
				// required: true,
				number: true,
				minlength:10,
				maxlength:12
			},
			
			address_line:{
			//	required: true
			},
			zip_code:{
			//	required: true,
				number: true
			}

		},
		  messages: {
		   
			firstName: {
			  required: "FirstName is required."
			},
			lastName: {
			  required: "LastName is required."
			},
			email: {
			  required: "Email is required.",
			  remote: "Email already registered."
			},
			phone_no: {
			  required: "Phone No is required.",
			  number: "Number must be in the integer."
			},
			address_line: {
			  required: "Address is required."
			},
			zip_code: {
			  required: "Zipcode is required."
			}
		  },
		
		submitHandler: function() {            
			form.submit();
		}
	});

	$("#edit-user-form").validate({
		rules: {
			firstName: {
				required: true
			},
			lastName: {
				required: true
			},
			phone_no: {
			//	required: true,
				number: true,
				minlength:10,
				maxlength:12
			},
			
			address_line:{
			//	required: true
			},
			zip_code:{
			//	required: true,
				number: true
			}

		},
		  messages: {
		   
			firstName: {
			  required: "FirstName is required."
			},
			lastName: {
			  required: "LastName is required."
			},
			phone_no: {
			  required: "Phone is required.",
			  number: "Number must be in the integer."
			},
			address_line: {
			  required: "Address is required."
			},
			zip_code: {
			  required: "Zipcode is required."
			}
		  },
		
		submitHandler: function() {            
			form.submit();
		}
	});
    
    $("#admin-change-password").validate({
		rules: {
			password: {
				required: true,
				minlength:6,
				maxlength:15
			},
			confirmpassword:{
				required: true,
				minlength:6,
				maxlength:15,
				equalTo: "#admin-password"
			}
		},
		  messages: {
		   
			confirmpassword: {
			  required: "Confirm Password is required.",
			  equalTo: "Password is not matched."
			}
		  },
		
		submitHandler: function() {            
			form.submit();
		}
	});

    $("#profile-admin-edit").validate({
        rules: {
            
            email:{
                required: true,
                email:true,
                    remote: {
	                    type: 'POST',
	                    url: apiBaseUrl+'/validateAdminEmailCheck',
	                    data: {
	                        'id': function() {
	                           return $('#profile-admin-edit.email').val();
	                        }
	                    },
	                    dataType: 'json'
	                }
            }
        },
          messages: {
          
            email: {
              required: "Email is required.",
              remote: "Email already registered.",
            }
          },
        
        submitHandler: function() {            
            form.submit();
        }
    });
    
    $("#add-category-form").validate({
		rules: {
			categoryName: {
				required: true
			},
			status: {
				required: true
			}
		},
		  messages: {
			categoryName: {
			  required: "Category Name is required."
			},
			status: {
			  required: "Status is required."
			}
		  },
		
		submitHandler: function() {            
			form.submit();
		}
	});

	$("#edit-category-form").validate({
		rules: {
			categoryName: {
				required: true
			},
			status: {
				required: true
			}
		},
		  messages: {
			categoryName: {
			  required: "Category Name is required."
			},
			status: {
			  required: "Status is required."
			}
		  },
		
		submitHandler: function() {            
			form.submit();
		}
	});
	
	
    
    
    $("#rejectStatus-form").validate({
		rules: {
			reject_reason: {
				required: true
			}
		},
		  messages: {
			reject_reason: {
			  required: "Reason is required.",
			}
		  },
		
		submitHandler: function() {  
			reasonStatus();
			return false;
		}
	});

	
	$(document).on('change','#extensionId',function(){ 
	    var $this = $(this);
		 //var status =$(this).("option:selected" )[0].getAttribute("value")
		var status =$( "option:selected",this ).val()
		var extensionId =$(this).attr('extension_id');
		
		if(status==5){
		    $('#basicModal').show();
		    $('#extId').val(extensionId);
		    return false;
		}else{
		    $this.closest('#extensionId').hide();
			$this.closest('td').append('<div class="loader"></div>');
    		$.ajax({
                type: 'POST',
                url: apiBaseUrl+'/extensionStatus/'+extensionId,
                data: {status:status},
                dataType: 'json',
                success: function(response){
                    //console.log(response);
                    if(response.status==200){
                            $('.getLoder').html('');
                            $('#extensionId').show();
        	                setTimeout(function(){
                               location.reload();
                            }, 3000);
                    	
                    }else{
                    	$('.alert-danger').find('p').text(response.message);
                    }
                },error: function(jqXHR, textStatus) {
                   console.warn(textStatus);
                },
            });
		}
		
		
	});
    
    

	var getSearchParameter =   getUrlParameter('search');

	if(getSearchParameter != ''){
		$('#search-tab').val(getSearchParameter);
	}
	
	var getSearchCategoryParameter =   getUrlParameter('category');

	if(getSearchCategoryParameter != ''){
		$('#search-category').val(getSearchCategoryParameter);
	}

	var getSearchEmailParameter =   getUrlParameter('email');

	if(getSearchEmailParameter != ''){
		$('#search-email').val(getSearchEmailParameter);
	}
	
	var getSearchExtensionParameter =   getUrlParameter('extension');

	if(getSearchExtensionParameter != ''){
		$('#search-extension').val(getSearchExtensionParameter);
	}
	
	
	$(document).on('change','#field_type',function(){ 
		var $type = $(this).val();
		customFieldHtml($type);
	});


	/*$('#textTypeform').rules('add', {  // <- a single field with custom messages
	    required: true, 
	    digits: true,
	    messages: {
	        required: "this field is mandatory",
	        digits: "this field can only contain digits"
	    }
	});*/

	
	$(document).on('click','#save_text_custom_field',function(){
		alert();
		var index=$('#updated').val();

		if(index){
			console.log('if');	
			label=$('#label').val();
			type =$('#field_type').val();
				if($('#required').is(':checked')){
			    	$required='Yes';
			    }else{
			    	$required='No';
			    }
			required = $required;
			placeholder =$('#placeholder').val();
			default_value = $('#default').val();
			
			/*custom_fields[index]=data;*/
			custom_fields[index].label=label;
			custom_fields[index].type=type;
			custom_fields[index].required=required;
			custom_fields[index].placeholder=placeholder;
			custom_fields[index].default_value=default_value;

		}else{
			var data={};		
			data.label=$('#label').val();
			data.type =$('#field_type').val();
			if($('#required').is(':checked')){
		    	$required='Yes';
		    }else{
		    	$required='No';
		    }
		    data.required = $required;
			data.placeholder =$('#placeholder').val();
			data.default_value = $('#default').val();
			custom_fields.push(data);

			custom_fields.forEach(function(item,i){
	  			itemString= encodeURIComponent(JSON.stringify(item))
	  			hiddenHtml = `<input type="hidden" index="`+i+`" name="validations[]" value="`+ itemString+`" >`;
	  		});
			
		}
				
		displayCustomFields(custom_fields);

  		/*custom_fields.forEach(function(item,i){
  			itemString= encodeURIComponent(JSON.stringify(item))
  			hiddenHtml = `<input type="hidden" index="`+i+`" name="validations[]" value="`+ itemString+`" >`;
  		});*/

  		$('#add-category-form').append(hiddenHtml);
		$("#field").html('');
		$("#field_type").val('');
		$('#myModal').modal('toggle');
		console.log(custom_fields);

	});

	$(document).on('click','#save_select_custom_field',function(){ 
		
		index=$('#updated').val();

		var data={};
		var choice=$('#choice').val();
		choiceArray=choice.split(/\n/);	

		data.label=$('#label').val();
		data.type =$('#field_type').val();
		
		if($('#required').is(':checked')){
	    	$required='Yes';
	    } else{
	    	$required='No';
	    }
	    
	    if($('#multipleSelect').is(':checked')){
	    	$multipleSelect='Yes';
	    }else{
	    	$multipleSelect='No';
	    }

	    data.required = $required;
		data.default_value = $('#default').val();
		data.choice=choiceArray;	
		data.multiple=$multipleSelect;

		if(index){
			custom_fields[index]=data;
		}else{
			custom_fields.push(data);
		}

		displayCustomFields(custom_fields);
  		var dataString=JSON.stringify(data);

  		custom_fields.forEach(function(item,i){
  			itemString= encodeURIComponent(JSON.stringify(item))
  			hiddenHtml = `<input type="hidden" index="`+i+`" name="validations[]" value="`+ itemString+`" >`;
  		});

  		$('#add-category-form').append(hiddenHtml);
		$("#field").html('');
		$("#field_type").val('');
		$('#myModal').modal('toggle');

	});

	$(document).on('click','#save_radio_custom_field',function(){ 
		index=$('#updated').val();
		var data={};
		var choice=$('#choice').val();
		choiceArray=choice.split(/\n/);	

		data.label=$('#label').val();
		data.type =$('#field_type').val();
		
		if($('#required').is(':checked')){
	    	$required='Yes';
	    } else{
	    	$required='No';
	    }

	    data.required = $required;
		data.default_value = $('#default').val();
		data.choice=choiceArray;

		if(index){
			custom_fields[index]=data;
		}else{
			custom_fields.push(data);
		}
		console.log(custom_fields);
		displayCustomFields(custom_fields);
  		custom_fields.forEach(function(item,i){
  			itemString= encodeURIComponent(JSON.stringify(item))
  			hiddenHtml = `<input type="hidden" index="`+i+`" name="validations[]" value="`+ itemString+`" >`;
  		});

  		$('#add-category-form').append(hiddenHtml);
		//$('#validations').val(custom_fields);
		$("#field").html('');
		$("#field_type").val('');
		$('#myModal').modal('toggle');

	});


	$(document).on('click','#save_checkbox_custom_field',function(){ 
		index=$('#updated').val();
		var data={};
		var choice=$('#choice').val();
		choiceArray=choice.split(/\n/);	

		data.label=$('#label').val();
		data.type =$('#field_type').val();
		
		if($('#required').is(':checked')){
	    	$required='Yes';
	    } else{
	    	$required='No';
	    }

	    data.required = $required;
		data.default_value = $('#default').val();
		data.choice=choiceArray;

		if(index){
			custom_fields[index]=data;
		}else{
			custom_fields.push(data);
		}
		console.log(custom_fields);
		displayCustomFields(custom_fields);
  		custom_fields.forEach(function(item,i){
  			itemString= encodeURIComponent(JSON.stringify(item))
  			hiddenHtml = `<input type="hidden" index="`+i+`" name="validations[]" value="`+ itemString+`" >`;
  		});

  		$('#add-category-form').append(hiddenHtml);
		//$('#validations').val(custom_fields);
		$("#field").html('');
		$("#field_type").val('');
		$('#myModal').modal('toggle');

	});

	


	$(document).on('click','#deleteCustomField',function(){
		index=$(this).attr('index');
		custom_fields.splice(index,1);
		$(this).closest("tr").remove();
	});

	$(document).on('click','#editCustomField',function(){
		
		index=$(this).attr('index');
		//console.log(custom_fields[index]);
		custom_fields.forEach(function(item,i){
			if(index==i){
				$('#myModal').modal('show');
				$('#field_type').val(item.type);
				$type=item.type;
				customFieldHtml($type);
				
				$('#updated').val(index);
				$('#label').val(item.label);
				$('#default').val(item.default_value);
				if(item.placeholder){
					$('#placeholder').val(item.placeholder);
				}
				if(item.choice){
					$('#choice').val(item.choice.join('\n'));
				}
				if(item.required=="Yes"){	
					$('#required').prop('checked',true);
				}else{
					$('#required').prop('checked',false);
				}
				if(item.multiple=="Yes"){
					$('#multipleSelect').prop('checked',true);
				}else{
					$('#multipleSelect').prop('checked',false);
				}

			}
		});
		
	});

	$('.deleteEditCustom').on('click',function(){
		alert('delete');
		var validationId=$(this).attr('index');
		$.ajax({
                type: 'GET',
                url: apiBaseUrl+'/deleteEditCustom/'+validationId,
                dataType: 'json',
                success: function(response){
                    if(response.status==200){
                    		$('.alert-success').find('p').text(response.message);
        	                setTimeout(function(){
                               location.reload();
                            }, 2000);
                    	
                    }else{
                    	$('.alert-danger').find('p').text(response.message);
                    }
                },error: function(jqXHR, textStatus) {
                   console.warn(textStatus);
                },
            });

	});

	


});	

function getUrlParameter(sParam) {
    var sPageURL = window.location.search.substring(1),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : decodeURIComponent(sParameterName[1]);
        }
    }
};


 function reasonStatus(){
    var reason=$('#reject_reason').val();
       var extId=$('#extId').val();
       var status=5;
        $.ajax({
            type: 'POST',
            url: apiBaseUrl+'/extensionStatusReject/'+extId,
            data: {status:status,reason:reason},
            dataType: 'json',
            success: function(response){
                if(response.status==200){
                    $('.getLoder').html('');
        	      	$('#extensionId').show();
	               $('.msg').html('<div class="alert alert-success"><p>'+response.message+'</p></div>');
	               setTimeout(function(){
	                    $("#rejectStatus-form")[0].reset();
	                    $('#basicModal').hide();
	                    location.reload();
                    }, 2000);
                	
                }else{
                    
                  $('.alert alert-info').text('sorry not updated');
                  $("#rejectStatus-form")[0].reset();
                }
            },error: function(jqXHR, textStatus) {
               console.warn(textStatus);
            },
        });
     
}


function displayCustomFields(custom_fields){

	if(custom_fields.length>0){
		var S_n = 1;
		var html=`<table style="width:100%">
						  <tr>
						    <th>S.N</th>
						    <th>Label</th> 
						    <th>Type</th>
						    <th>Action</th>
						  </tr>`;
		custom_fields.forEach(function(item,i){
			if(item){
				 html +=`<tr custom="`+i+`">
					 		<td>`+S_n+`</td>
					 		<td>`+item.label+`</td>
						    <td>`+item.type+`</td>
						    <td ><a href="javascript:void(0)" index="`+i+`" id="editCustomField" >Edit</a><br><a onclick="return confirm('Are you sure?');" index="`+i+`" href="javascript:void(0)" id="deleteCustomField" >Delete</a></td>
					  	</tr>`;
						 
			}
			S_n++;	
		});
		html +=`</table>`;

		$('#display_table').html(html);
	}

}


function customFieldHtml($type){
	if($type=='text'){
			//console.log($type);
			var textHtml =	`<form id="textTypeform" name="textTypeform">
								<input type="hidden" id="updated" value="">
								<div class="form-group row">
			                        <span class="label-text col-md-3 col-form-label">Field Label: </span>
			                        <div class="col-md-9">
			                            <input type="text" id="label" name="label" class="form-control">
			                        </div>
			                    </div>
								<div class="form-group row">
			                        <span class="label-text col-md-3 col-form-label">Default Value: </span>
			                        <div class="col-md-9">
			                            <input type="text" id="default" name="default" class="form-control">
			                        </div>
			                    </div>
			                    <div class="form-group row">
			                        <span class="label-text col-md-3 col-form-label">Placeholder Text: </span>
			                        <div class="col-md-9">
			                            <input type="text" id="placeholder"  name="placeholder" class="form-control">
			                        </div>
			                    </div>
			                    <div class="form-group row">
			                        <span class="label-text col-md-3 col-form-label">Required: </span>
			                        <div class="col-md-9">
			                            <input type="checkbox" id="required" name="required" data-toggle="toggle">
			                        </div>
			                    </div>
			                    <div class="row mt-3">
			                        <div class="col-md-9 offset-md-3">
			                            <input type="button" value="Save" class="btn btn-rounded btn-success" id="save_text_custom_field">
			                        </div>
			                    </div>
		               		</from>`;

            $('#field').html(textHtml);  

		}else if($type=='select'){
			var selectHtml =`<form  id="selectTypeform" name="selectTypeform">
								<input type="hidden" id="updated" value="">
								<div class="form-group row">
			                        <span class="label-text col-md-3 col-form-label">Field Label: </span>
			                        <div class="col-md-9">
			                            <input type="text" id="label" required  name="label" class="form-control">
			                        </div>
			                    </div>
								<div class="form-group row">
			                        <span class="label-text col-md-3 col-form-label">Default Value: </span>
			                        <div class="col-md-9">
			                            <input type="text" id="default" required name="default" class="form-control">
			                        </div>
			                    </div>
			                    <div class="form-group row">
			                        <span class="label-text col-md-3 col-form-label">Required: </span>
			                        <div class="col-md-9">
			                            <input type="checkbox" id="required" required name="required" data-toggle="toggle">
			                        </div>
			                    </div>
			                    <div class="form-group row">
			                        <span class="label-text col-md-3 col-form-label">Choices: </span>
			                        <div class="col-md-9">
			                            <textarea id="choice" name="choice" required placeholder="Enter each choice on a new line." rows="3" class="form-control" cols="50"></textarea>
			                        </div>
			                    </div>
			                     <div class="form-group row">
			                        <span class="label-text col-md-3 col-form-label">Multiple select: </span>
			                        <div class="col-md-9">
			                            <input type="checkbox" id="multipleSelect"  required name="multipleSelect" data-toggle="toggle">
			                        </div>
			                    </div>
			                    <div class="row mt-3">
			                        <div class="col-md-9 offset-md-3">
			                            <input type="button" value="Save" class="btn btn-rounded btn-success" data-dismiss="modal" id="save_select_custom_field" >
			                        </div>
			                    </div>

			                </from>`;
			$('#field').html(selectHtml);
		}else if($type=='radio'){
			//console.log($type);
			var radioHtml =`<form  id="radioTypeform" name="radioTypeform">
							<input type="hidden" id="updated" value="">
								<div class="form-group row">
			                        <span class="label-text col-md-3 col-form-label">Field Label: </span>
			                        <div class="col-md-9">
			                            <input type="text" id="label"  name="label" class="form-control">
			                        </div>
			                    </div>
								<div class="form-group row">
			                        <span class="label-text col-md-3 col-form-label">Default Value: </span>
			                        <div class="col-md-9">
			                            <input type="text" id="default" name="default" class="form-control">
			                        </div>
			                    </div>
			                    <div class="form-group row">
			                        <span class="label-text col-md-3 col-form-label">Required: </span>
			                        <div class="col-md-9">
			                            <input type="checkbox" id="required"  name="required" data-toggle="toggle">
			                        </div>
			                    </div>
			                    <div class="form-group row">
			                        <span class="label-text col-md-3 col-form-label">Choices: </span>
			                        <div class="col-md-9">
			                            <textarea id="choice" name="choice" rows="3" class="form-control" cols="50"></textarea>
			                        </div>
			                    </div>
			                    <div class="row mt-3">
			                        <div class="col-md-9 offset-md-3">
			                            <input type="button" value="Save" class="btn btn-rounded btn-success" data-dismiss="modal" id="save_radio_custom_field" >
			                        </div>
			                    </div>

			                </from>`;
			$('#field').html(radioHtml);
		}else if($type=='checkbox'){
			//console.log($type);
			var checkboxHtml =`<form  id="checkboxTypeform" name="checkboxTypeform">
							<input type="hidden" id="updated" value="">
								<div class="form-group row">
			                        <span class="label-text col-md-3 col-form-label">Field Label: </span>
			                        <div class="col-md-9">
			                            <input type="text" id="label"  name="label" class="form-control">
			                        </div>
			                    </div>
								<div class="form-group row">
			                        <span class="label-text col-md-3 col-form-label">Default Value: </span>
			                        <div class="col-md-9">
			                            <input type="text" id="default" name="default" class="form-control">
			                        </div>
			                    </div>
			                    <div class="form-group row">
			                        <span class="label-text col-md-3 col-form-label">Required: </span>
			                        <div class="col-md-9">
			                            <input type="checkbox" id="required"  name="required" data-toggle="toggle">
			                        </div>
			                    </div>
			                    <div class="form-group row">
			                        <span class="label-text col-md-3 col-form-label">Choices: </span>
			                        <div class="col-md-9">
			                            <textarea id="choice" name="choice" rows="3" class="form-control" cols="50"></textarea>
			                        </div>
			                    </div>
			                    <div class="row mt-3">
			                        <div class="col-md-9 offset-md-3">
			                            <input type="button" value="Save" class="btn btn-rounded btn-success" data-dismiss="modal" id="save_checkbox_custom_field" >
			                        </div>
			                    </div>

			                </from>`;
			$('#field').html(checkboxHtml);
		}
}

