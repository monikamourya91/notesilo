console.log('dashboard');
console.log( Math.random());

var apiBaseUrl = 'http://reseller.groupleads.net/api';

var hiddenHtml;

var custom_fields =[];

var textHtml =	`<div  class="textTypeform type-connector" >
					<input type="hidden" id="updated" value="">
					<div class="form-group row">
                        <span class="label-text col-md-3 col-form-label">Field Label*: </span>
                        <div class="col-md-9">
                            <input type="text" id="label"   name="label" class="form-control custom-label-text">
                        </div>
                    </div>
					<div class="form-group row">
                        <span class="label-text col-md-3 col-form-label">Default Value: </span>
                        <div class="col-md-9">
                            <input type="text"  name="default" class="form-control default-value placeholder">
                        </div>
                    </div>
                    <div class="form-group row">
                        <span class="label-text col-md-3 col-form-label">Placeholder Text: </span>
                        <div class="col-md-9">
                            <input type="text"  name="placeholder" class="form-control placeholder">
                        </div>
                    </div>
                    <div class="form-group row">
                        <span class="label-text col-md-3 col-form-label">Required: </span>
                        <div class="col-md-9">
                            <input type="checkbox" class="isrequired" name="required" data-toggle="toggle">
                        </div>
                    </div>
                   
           		</div>`;

var checkboxHtml =`<div  class="checkboxTypeform type-connector" name="checkboxTypeform">
						<input type="hidden" id="updated" value="">
							<div class="form-group row">
		                        <span class="label-text col-md-3 col-form-label">Field Label*: </span>
		                        <div class="col-md-9">
		                            <input type="text" id="label"  name="label" class="form-control custom-label-text">
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
		                            <input type="checkbox" class="isrequired"  name="required" data-toggle="toggle">
		                        </div>
		                    </div>
		                    <div class="form-group row">
		                        <span class="label-text col-md-3 col-form-label">Choices*: </span>
		                        <div class="col-md-9">
		                            <textarea required name="choice" rows="3" class="form-control custom-label-text choice" cols="50"></textarea>
		                        </div>
		                    </div>		                    
		                </div>`;

var radioHtml =`<div  class="radioTypeform type-connector" name="radioTypeform">
						<input type="hidden" id="updated" value="">
							<div class="form-group row">
		                        <span class="label-text col-md-3 col-form-label">Field Label*: </span>
		                        <div class="col-md-9">
		                            <input type="text" id="label"  name="label" class="form-control custom-label-text">
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
		                            <input type="checkbox" class="isrequired" name="required" data-toggle="toggle">
		                        </div>
		                    </div>
		                    <div class="form-group row">
		                        <span class="label-text col-md-3 col-form-label">Choices*: </span>
		                        <div class="col-md-9">
		                            <textarea required name="choice" rows="3" class="form-control custom-label-text choice" cols="50"></textarea>
		                        </div>
		                    </div>		              
		                </div>`;

var selectHtml =`<div  class="selectTypeform type-connector" name="selectTypeform">
							<input type="hidden" id="updated" value="">
							<div class="form-group row">
		                        <span class="label-text col-md-3 col-form-label">Field Label*: </span>
		                        <div class="col-md-9">
		                            <input type="text" id="label" required  name="label" class="form-control custom-label-text">
		                        </div>
		                    </div>
							<div class="form-group row">
		                        <span class="label-text col-md-3 col-form-label">Default Value: </span>
		                        <div class="col-md-9">
		                            <input type="text" id="default"  name="default" class="form-control">
		                        </div>
		                    </div>
		                    <div class="form-group row">
		                        <span class="label-text col-md-3 col-form-label">Required: </span>
		                        <div class="col-md-9">
		                            <input type="checkbox" class="isrequired"  name="required" data-toggle="toggle">
		                        </div>
		                    </div>
		                    <div class="form-group row">
		                        <span class="label-text col-md-3 col-form-label">Choices*: </span>
		                        <div class="col-md-9">
		                            <textarea id="choice" required name="choice" required placeholder="Enter each choice on a new line." rows="3" class="form-control custom-label-text choice" cols="50"></textarea>
		                        </div>
		                    </div>
		                     <div class="form-group row">
		                        <span class="label-text col-md-3 col-form-label">Multiple select: </span>
		                        <div class="col-md-9">
		                            <input type="checkbox" class="multipleSelect"   name="multipleSelect" data-toggle="toggle">
		                        </div>
		                    </div>
		                </div>`;

function customFieldRowHtml(customFieldsRowIds) {
	return `<div class="panel panel-default custom-field-row">
	                          <div class="panel-heading">
	                            <h4 class="panel-title w-100">
	                              <a data-toggle="collapse" data-parent="#accordion" href="#collapse`+customFieldsRowIds+`">Custom field</a>
	                              <a edit-custom-field-id="0" class="remove-custom-field-row">Remove</a>	
	                            </h4>
	                          </div>
	                          <div id="collapse`+customFieldsRowIds+`" class="panel-collapse collapse in show">
	                            <div class="panel-body">

	                           
	                         	   <div class="form-group row">
		                                <span class="label-text col-md-3 col-form-label">Field Type*:</span>
		                                <div class="col-md-9">
		                                    <select name="field_type"  class="form-control field_type">
		                                        <option value="">Please Select</option>
		                                            <option value="text">Text</option>
		                                            <option value="checkbox">CheckBox</option>     
		                                            <option value="radio">Radio Button</option>
		                                            <option value="select">Drop Down</option>        
		                                    </select>
		                                </div>
		                            </div>
		                            <div class="dynamic-type-container"></div>
	                            </div>
	                          </div>
	                        </div>`;
}

function setTempValues(update= false) {
	
	$('#multipleAppend').html('');
	var valid = 'Yes'; 
	$('.custom-field-row').each(function (index) {
		
		var tempData={};

		var selectedType = $(this).find('.field_type').val();

		if ($(this).find('.custom-label-text').val() == '') {
			valid = 'No';
		}
		if (valid) {

			if (update) {

				if ($(this).find('.custom-field-id').length > 0) {
					tempData.customFieldId = $(this).find('.custom-field-id').val();
				}else{
					tempData.customFieldId = 0; 
				}
			}

			tempData.label = $(this).find('.custom-label-text').val();
			tempData.required = ($(this).find('.isrequired').is(':checked'))?'Yes':'No';
			tempData.type = selectedType;

			if (selectedType == 'text') {
				tempData.placeholder = $.trim($(this).find('.placeholder').val());
				tempData.default_value = $.trim($(this).find('.default-value').val());
				addToParentCategoryForm(tempData);
			
			} else if (selectedType == 'checkbox' || selectedType == 'radio') {
				choice = $.trim($(this).find('.choice').val());
				tempData.choice = choice.split(/\n/);	
				addToParentCategoryForm(tempData);
				
			}else if(selectedType == 'select'){
				choice = $(this).find('.choice').val();
				tempData.choice = choice.split(/\n/);	
				tempData.multiple = ($(this).find('.multipleSelect').is(':checked'))?'Yes':'No';
				addToParentCategoryForm(tempData);
			}
		}

	})

	return valid;
}

function addToParentCategoryForm(item) {
	itemString= encodeURIComponent(JSON.stringify(item))
	hiddenHtml = `<input type="hidden" class="validation"  name="validations[]" value="`+ itemString+`" >`;
	$('#multipleAppend').append(hiddenHtml);
}

$( document ).ready(function() { 
    
    
	$(".fancybox").fancybox({
        openEffect: "none",
        closeEffect: "none"
    });
    
    $(".zoom").hover(function(){
		
		$(this).addClass('transition');
	}, function(){
        
		$(this).removeClass('transition');
	});
    
    $('#overview').on('click',function(){
		$('#tabSeller').hide();
		$('#tabOverview').show();
	});

	$('#seller').on('click',function(){
		$('#tabOverview').hide();
		$('#tabSeller').show();
	});

	$('#add-new-custom-field').on('click',function(){
    	customFieldsRowIds = $('#accordion .custom-field-row').length+1;
    	$('#accordion').append(customFieldRowHtml(customFieldsRowIds));
    	$('#collapse'+customFieldsRowIds+'').show();
    });

    $(document).on('click','.remove-custom-field-row',function(){
    	if (confirm('Are you sure you want to delete ?')) {
    		var tempCustomId = $(this).attr('edit-custom-field-id');
    		console.log(tempCustomId)
    		$(this).closest('.custom-field-row').remove();
    		if (tempCustomId != 0) {
    				$.ajax({
		            type: 'GET',
		            url: apiBaseUrl+'/deleteEditCustom/'+tempCustomId,
		            dataType: 'json',
		            success: function(response){
		                if(response.status==200){
		                		// $('.alert-success').find('p').text(response.message);
		    	             //    setTimeout(function(){
		                  //          location.reload();
		                  //       }, 2000);
		                	
		                }else{
		                	//$('.alert-danger').find('p').text(response.message);
		                }
		            },error: function(jqXHR, textStatus) {
		               console.warn(textStatus);
		            },
		        });
    		}
    	}
    });


    $(document).on('change','.field_type',function(){ 
		var $type = $(this).val();
		$('.custom-field-row').removeClass('active-row');
		var customParentRow = $(this).closest('.custom-field-row').addClass('active-row');
		$('.custom-field-row.active-row .type-connector').hide();

		if ($type == 'text') {
			$('.custom-field-row.active-row .dynamic-type-container').html(textHtml);
		} else if($type == 'checkbox'){
			$('.custom-field-row.active-row .dynamic-type-container').html(checkboxHtml);
		}else if($type == 'radio'){
			$('.custom-field-row.active-row .dynamic-type-container').html(radioHtml);
		}else if($type == 'select'){
			$('.custom-field-row.active-row .dynamic-type-container').html(selectHtml);
		}else{
			return false;
		}
	    $('.custom-label-text').rules('add', {required: true});	 
	});


	$('#add-custom-field-edit').on('click',function(){
       	$('#myModal').modal('show');
    });
	
	$('#back-user-list').on('click',function(){
        window.history.back();
    });
    
    
    $('.model_close').click(function(){
        $("#rejectStatus-form")[0].reset();
        $('#basicModal').hide();
        $('.adsId').find('option[selected]').prop('selected', true);
    })

    setTimeout(function(){
		$(".alert.alert-success.autohide").hide();
	}, 2000);

	setTimeout(function(){
		$(".alert.alert-danger.autohide").hide();
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

	$("#reseller-change-password").validate({
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
            name:{
            	required:true
            },
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
          	
          	name:{
          		required: "Name is required."
          	},
            email: {
              required: "Email is required.",
              remote: "Email already registered.",
            }
          },
        
        submitHandler: function() {            
            form.submit();
        }
    });

    $("#profile-reseller-edit").validate({
        rules: {
            name:{
            	required:true
            },
            email:{
                required: true,
                email:true,
                    remote: {
	                    type: 'POST',
	                    url: apiBaseUrl+'/validateResellerEmailCheck',
	                    data: {
	                        'id': function() {
	                           return $('#profile-reseller-edit.email').val();
	                        }
	                    },
	                    dataType: 'json'
	                }
            }
        },
          messages: {
          	
          	name:{
          		required: "Name is required."
          	},
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

			var textareaErrorHtml = `<label id="label-error" class="error" for="label">This field is required.</label>`;
			var validChoice = true;
			if ($('.choice').length > 0) {
				$('.choice').each(function (index) {
					typedText = $.trim($(this).val());
					if (validChoice && typedText == '') {
						validChoice = false;
						$(this).after(textareaErrorHtml);
					}
				})
			}

			if (validChoice && setTempValues(false) == 'Yes') {
				form.submit();
			}else{
				return false;
			}

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

			var textareaErrorHtml = `<label id="label-error" class="error" for="label">This field is required.</label>`;
			var validChoice = true;
			if ($('.choice').length > 0) {
				$('.choice').each(function (index) {
					typedText = $.trim($(this).val());
					if (validChoice && typedText == '') {
						validChoice = false;
						$(this).after(textareaErrorHtml);
					}
				})
			}

			if (validChoice && setTempValues(true) == 'Yes') {
				form.submit();
			}else{
				return false;
			}
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


	
	$(document).on('change','.adsId',function(){ 

		if(confirm('Are you really want to change status')){

			var status =$( "option:selected",this ).val()
			var adId =$(this).attr('ad_id');
			if(status==2){
			    $('#basicModal').show();
			    $('#adId').val(adId);
			    return false;
			}else{
			    $('.getLoder').show();
				$.ajax({
	                type: 'POST',
	                url: apiBaseUrl+'/adStatus/'+adId,
	                data: {status:status},
	                dataType: 'json',
	                success: function(response){
	                    console.log(response);
	                    if(response.status==200){
	        	                setTimeout(function(){
	        	                    $('.getLoder').hide();
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

		}else{
			$(this).find('option[selected]').prop('selected', true);
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
	
	var getSearchExtensionParameter =   getUrlParameter('ads');

	if(getSearchExtensionParameter != ''){
		$('#search-ads').val(getSearchExtensionParameter);
	}
	
	
	/*image upload*/
	
	$('#uploadForm').on('submit', function(e){  
           e.preventDefault();  

           $.ajax({  
                url: apiBaseUrl+'/uploadItemMedia',  
                type: "POST",  
                data: new FormData(this),  
                contentType: false,  
                processData:false,  
                success: function(data)  
                {  
                	console.log(response);
                     $("#gallery").html(data);  
                     alert("Image Uploaded");  
                }  
           });  
      });

      /*Form validations*/

      $("#reseller_add_subscriber").validate({
		rules: {
			email: {
				required: true,
				email: true,
				remote: {
	                    type: 'POST',
	                    url: apiBaseUrl+'/is-subscriber-already-exist',
	                    data: {
	                        'id': function() {
	                           return $('#profile-admin-edit.email').val();
	                        }
	                    },
	                    dataType: 'json'
	                }
			},
			name:{
				required: true,
			},
			plan:{
				required: true,
			}
		},
	  	messages: {
			email: {
				required: "Email is required",
				email: "Enter a valid email address",
				remote: "Email address already exist.",
			},
			name:{
				required: "Name is required",
			},
			plan:{
				required: "Plan is required",
			}
		},
		submitHandler: function() {            
			form.submit();
		}
	});   
	 
	$("#admin_add_subscriber").validate({
		rules: {
			email: {
				required: true,
				email: true,
				remote: {
	                    type: 'POST',
	                    url: apiBaseUrl+'/is-subscriber-already-exist',
	                    data: {
	                        'id': function() {
	                           return $('#profile-admin-edit.email').val();
	                        }
	                    },
	                    dataType: 'json'
	                }
			},
			name:{
				required: true,
			},
			plan:{
				required: true,
			}
		},
	  	messages: {
			email: {
				required: "Email is required",
				email: "Enter a valid email address",
				remote: "Email address already exist",
			},
			name:{
				required: "Name is required",
			},
			plan:{
				required: "Plan is required",
			}
		},
		submitHandler: function() {            
			form.submit();
		}
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
     $('.getLoder').show();
    var reason=$('#reject_reason').val();
    var adId=$('#adId').val();
    var status=2;
    $.ajax({
        type: 'POST',
        url: apiBaseUrl+'/adStatusReject/'+adId,
        data: {status:status,reason:reason},
        dataType: 'json',
        success: function(response){
            console.log(response);
            
            if(response.status==200){
                $('.msg').html('<div class="alert alert-success"><p>'+response.message+'</p></div>');
                $('#adStatusId').show();
                $('.getLoder').hide();
                
               
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


function onlyAppendLastRow(custom_fields){
console.log(custom_fields);
	if(custom_fields.length>0){
		var S_n = 1;
		var html=``;
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
	
console.log(html)
// console.log($('#edit_display_table table tbody').text());
		$('#edit_display_table tbody').append(html);
	}

}




/*New code*/

function changePaymentMode(){
	
	var selected_option = $( "#PlanPaymentMode option:selected" ).text();
	var selected_value  = $( "#PlanPaymentMode option:selected" ).val();

	if(selected_value != "")
	{
		$("#payment_mode_label").text(selected_option);
	}else{
		$("#payment_mode_label").text('');
	}
}

$(document).ready(function(){
	changePaymentMode();
});

function deleteConfirmation(url,type) {
  var r = confirm("Are you sure to delete?");
  if (r == true) {
    window.location.href = url;
  } 
}

function sendLicenseConfirmation(url,email){
	console.log(url+' '+email); 
	var r = confirm("Are you sure to send license to "+email+"?");
	if (r == true) {
		window.location.href = url;
	} 
}

