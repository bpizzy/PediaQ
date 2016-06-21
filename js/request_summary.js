$("document").ready(function(e){

  
/** delete child **/
$(document).on("click",".delete-childs",function(e){
var child_id = $(this).attr("child_id");
var name     = $(this).attr("name");

$("#delete-child-submit").attr("child_id",child_id);
$("#delete-child-name").text(name);
$("#delete-child-button").click();

});

/** delete child ajax **/
$("#delete-child-submit").click(function(){

var child_id = $(this).attr('child_id');
$(".delete-child-loading").html("<div class='loading1'><img src='images/loading.gif'></div>");
$.ajax({
				url: 'function.php',
				type: 'post',
				dataType:'text',
				data: {function_name:'deleteChildfromRequest',childs:child_id},
				success: function(text) {
				if(text==="0")
                                {
				  $(".delete-child-loading").html("");
				  $(".close").click();
				  $("#"+child_id).fadeOut();
                                }
                                else
                                {
                                  $(".delete-child-loading").html("");
				  $(".close").click();
				  $("#"+child_id).fadeOut(); 
                                  $(".addpatient").click();
                                }

			        },
			
			});

});
/** end **/

$(".add-patient-body").html("<div class='loading1'><img src='images/loading.gif'></div>");

	$(".addpatient").click(function(){
			$(".add-patient-body").html("<div class='loading1'><img src='images/loading.gif'></div>");
			$.ajax({
				url: 'function.php',
				type: 'post',
				dataType:'text',
				data: {function_name:'getExcludedajaxChildIds',childs:'1'},
				success: function(text) {
				$(".add-patient-body").html(text);
				},
			
			});
	});

	$("#cancel-request,#cancel_request").click(function(){
			
			$.ajax({
				url: 'function.php',
				type: 'post',
				dataType:'text',
				data: {function_name:'unsetchildsession',unset:'1'},
				success: function(text) {
				window.location.href='dashboard.php'; // Redirect after the child selection
				},
			
			});
	});



/** add child to request **/
$(document).on("click",".addchildtorequest",function(e){
$(".add-patient-body").append("<div class='loading1'><img src='images/loading.gif'></div>");
		var child =[];
		$(".selected").each(function(){
			child.push(this.id);	
		});
		if(child.length)
		{
			$.ajax({
				url: 'function.php',
				type: 'post',
				dataType:'text',
				data: {function_name:'addchildtoexistingsession',child:child},
				success: function(text) {
				$(".patients").html(text);
				$(".close").click();
				$(".loading1").hide();
				},
			
			});	
		}
		else
		{
			$(".close").click();
			$(".error-modal1").click(); // load error modal
		}

 
}); 
/** end **/



$(document).on("click",".ajaxchilds",function(e){
		if($(this).hasClass("selected"))
		{
			$(this).removeClass("selected");		
		}
		else
		{
			$(this).addClass("selected");
		}
	});

	$(".next").click(function(){
		var child =[];
		$(".selected").each(function(){
			child.push(this.id);	
		});	
	
		if(child.length)
		{
			$.ajax({
				url: 'function.php',
				type: 'post',
				dataType:'text',
				data: {function_name:'addchildtosession',child:child},
				success: function(text) {
				window.location.href='request_summary.php'; // Redirect after the child selection
				},
			
			});
		}
		else
		{
			$(".error-modal").click(); // load error modal
		}
	});

   	$(".addchild").click(function(){
		 $("#result").html();
 		 var flag 	= 0;
		 var error      = "";
             	 var name 	= $("#fullname").val();
		 var gender     = $("#gender").val();
		 var birthday   = $("#days").val();
		 var birthmonth = $("#month").val();
		 var birthyear  = $("#year").val();
	
		
		if(name=="")
		{
			error+="Please Enter Firstname & Lastname<br>";
			flag++;		
		}
		if(gender=="")
		{
			error+="Please Specify Gender<br>";		
			flag++;	
		}
		if(birthday=="" || birthmonth=="" || birthyear=="")
		{
			error+="Please Enter Birthday<br>";	
			flag++;		
		}
		if(name=="" && gender=="" && (birthday=="" || birthmonth=="" || birthyear=="") )
		{
			error="Please fill complete form<br>";
			flag++;
		}
		
		/** if error **/
		if(flag!=0)
		{
			$("#result").html("<div class='warning'>"+error+"</div>");
		}
		/** end **/

		/** if no error **/
		if(flag==0)
		{
			var dob = birthyear+"-"+birthmonth+"-"+birthday;
			$("#result").html("<div class='loading1'><img src='images/loading.gif'></div>");
			$.ajax({
				url: 'function.php',
				type: 'post',
				dataType:'text',
				data: {function_name:'addChildajax',name: name,gender:gender,dob:dob},
				success: function(text) {
				$("#result").html(text);
				$(".close").click();
				$(".addpatient").click();
$("#result").html('');
                                $("#fullname").val('');
		                $("#gender").val('');
		                $("#days").val('');
		                $("#month").val('');
	                        $("#year").val('');


				},
			
			});

			
		}
		/** end **/

        });


/** edit child jquery  **/

$(document).on("click",".edit-childs",function(e){
$("#editchildmodalbutton").click();
$(".edit-patient-body").html("<div class='loading2'><img src='images/loading.gif'></div>");
$("#edit-result").html("");
var id = $(this).attr('child_id');

$.ajax({
				url: 'function.php',
				type: 'post',
				dataType:'text',
				data: {function_name:'editChildajaxform',id:id},
				success: function(text) {
				$(".edit-patient-body").html(text);
				},
			
			});
 
});

/** save child edit **/
$(document).on("click","#savebuttoneditchild",function(e){
 
$(".edit-patient-body").append("<div class='loading1'><img src='images/loading.gif'></div>");

		 $("#edit-result").html();
 		 var flag 	= 0;
		 var error      = "";
		 var name 	= $("#edit-name").val();
		 var gender     = $("#edit-gender").val();
		 var birthday   = $("#edit-day").val();
		 var birthmonth = $("#edit-month").val();
		 var birthyear  = $("#edit-year").val();

		if(name=="")
		{
			error+="Please Enter Firstname & Lastname<br>";
			flag++;		
		}
		if(gender=="")
		{
			error+="Please Specify Gender<br>";		
			flag++;	
		}
		if(birthday=="" || birthmonth=="" || birthyear=="")
		{
			error+="Please Enter Birthday<br>";	
			flag++;		
		}
		if(name=="" && gender=="" && (birthday=="" || birthmonth=="" || birthyear=="") )
		{
			error="Please fill complete form<br>";
			flag++;
		}
		
		
		/** if error **/
		if(flag!=0)
		{
			$("#edit-result").html("<div class='warning'>"+error+"</div>");
		}
		/** end **/

		/** if no error **/
		if(flag==0)
		{
			
			var dob = birthyear+"-"+birthmonth+"-"+birthday;
			$("#edit-result").html("");
			$.ajax({
				url: 'function.php',
				type: 'post',
				dataType:'text',
				data: {function_name:'editchildajax',name: name,gender:gender,dob:dob,child_id:$("#edit_child_id").val()},
				success: function(text) {
				$("#edit-result").html(text);
				$(".close").click();
				$(".addpatient").delay("slow").click();
				
				},
			
			});

			
		}
		/** end **/
 
});



/** end **/

/** open location **/
$(".addlocationform,#list-address").click(function(){
	$("#openlocationform").click();
	$(".select-address-body").html("<div class='loading1'><img src='images/loading.gif'></div>");
	$.ajax({
				url: 'function.php',
				type: 'post',
				dataType:'text',
				data: {function_name:'getAddressesajax',status:1},
				success: function(text) {
				$(".select-address-body").html(text);
				},
			
			});
});

/** end **/




/** add address submit **/ 
$("#addaddress-submit").click(function(){  
	$("#add-address-result").html('');
$("#add-address-result").append("<div class='loading1'><img src='images/loading.gif'></div>");
	var address		= $("#autocomplete").val();  
	var street_number 	= $("#street_number").val();
	var city		= $("#locality").val();  
	var state	 	= $("#administrative_area_level_1").val(); 
	var post_code	 	= $("#postal_code").val(); 
	var country             = $("#country").val();
	var apt 		= $("#aptsuite").val();

	if(post_code=="")
	{
		post_code = "00000";	
	}
	
	if(address!="")
	{
	$.ajax({
		url: 'function.php',
		type: 'post',
		dataType:'text',
		data:{function_name:'addAddress',address:address,snumber:street_number,city:city,country:country,zip:post_code,apt:apt,state:state,default:0},
		success: function(text) {

			if(typeof $(text).find(".warning").html()==="undefined")
			{
			/** call another ajax to see default address existence **/ 

				$.ajax({
				url: 'function.php',
				type: 'post',
				dataType:'text',
				data: {function_name:'getDefaultAddressAjax',status:1},
				success: function(result) {
					if(result!="")
					{
						$(".visits").html(result);
						$(".close").click();
						$(".addlocationform").click();
						$("#add-address-result").html('');
					}
					else
					{
						$("#add-address-result").html(text);	
					}
					
				},
			
			});

			/** end **/
			}
			else
			{
					$("#add-address-result").html(text);
			}
		
		},
	});
  	}
	else
	{
		$("#add-address-result").html("<div class='warning'>Please Specify Address</div>");
	}
}); 
/** end **/ 



/** add default address **/
$(document).on("click",".ajax-address",function(e){

	var address_id = $(this).attr("id");
	if(address_id)
	{
		$.ajax({
			url: 'function.php',
			type: 'post',
			dataType:'text',
			data:{function_name:'updatedefaultaddress',address_id:address_id},
			success: function(text) {
				$(this).remove();
				$(".visits").html(text);
				$(".close").click();
			},
		});				
	}

});	
/** end **/ 

/** delete address **/
$(document).on("click",".delete-address",function(e){ 
	var id = $(this).attr("address_id");
        $("#deleteaddress-result").html("");
	$("#delete-address-button").click();
	$("#delete-address-submit").attr('address_id',id);

});

$("#delete-address-submit").click(function(){

$("#deleteaddress-result").append("<div class='loading1'><img src='images/loading.gif'></div>");
	var id = $(this).attr("address_id");
	if(id!="")
	{
		$.ajax({
			url: 'function.php',
			type: 'post',
			dataType:'text',
			data:{function_name:'deleteAddressfromRequest',address_id:id},
			success: function(text) {
				if($(text).find(".warning").html()!=="undefined")
				{
					$(".visits").prepend(text);
					$(".close").click();
				}
				else
				{
					 if(text=="1")//there is address present
              				 {
					 	$(".addlocationform").click();
					 }
					 else // trigger add location modal 
					 {
						$("#addaddress_request_modal").click();
					 }
				}
			},
		});

	}

});

/** end **/
	 

/** edit location **/
$(document).on("click",".edit-address",function(e){ 
	$("#editLocationform-button").click();
	var id = $(this).attr('address_id');
	$.ajax({
			url: 'function.php',
			type: 'POST',
			dataType: 'text',
			data:{function_name:'getEditAddressForm',address_id:id},
			success : function(text)
			{
				$('#autocomplete3').val($(text).find(".street").html());
				$('.postal_code').val($(text).find(".zip").html());
				$('.aptsuite').val($(text).find(".apt_suite").html());
				$('.street_number').val($(text).find(".street_number").html());
				$('#edit_address_id').val($(text).find(".address_id").html());
			},
		});
});
/** end **/


/** add payment form **/
$(".addpaymentform").click(function(){
	$("#addPaymentform-trigger").click();	
});
/** end **/


/** update location **/
$("#update-location-submit").click(function(){
	 
     $("#edit-address-result").append("<div class='loading1'><img src='images/loading.gif'></div>");
	var street_number = $(".street_number").val();
	var aptsuite      = $(".aptsuite").val();
	var street        = $("#autocomplete3").val();
	var id            = $("#edit_address_id").val();
	var zip           = $(".postal_code").val();

	
	if(zip=="")
	{
		zip = "00000";
	}

	if(street=="")
	{
		 $("#edit-address-result").html("<div class='warning'>Please fill the address</div>");
	}
	else
	{
		/** edit address ajax **/
		$.ajax({
			url: 'function.php',
			type: 'POST',
			dataType: 'text',
			data:{function_name:'getEditAddress',id:id,zip:zip,street:street,apt_suite:aptsuite,street_number:street_number},
			success : function(text)
			{
				if($(text).find(".success").html()!=="undefined")
				{
					$("#edit-address-result").append(text);
					
					setTimeout(function close(){$(".close").click(); $(".addlocationform").click();$("#edit-address-result").html('');}, 1000);
				
				}
				else
				{
					$("#edit-address-result").html(text);
				}			
			},
		});
		/** end **/
	}
});
/** end **/

/** add promocode modal **/
$(document).on("click","#add-promo",function(e){ 
	$("#add-promo-result").html("");
	$("#add-promocode").click();
});
/** end **/

/** add promocode submit **/
$("#add-promo-submit").click(function(){

	$("#add-promo-result").html('');
	$("#add-promo-result").append("<div class='loading1'><img src='images/loading.gif'></div>");
	var promocode = $("#promocode").val();
	if(promocode!="")
	{	
		$.ajax({
			url: 'function.php',
			type: 'POST',
			dataType: 'text',
			data:{function_name:'addPromoCode',promocode:promocode},
			success : function(text)
			{
	
				if(typeof $(text).find(".success").html()!=="undefined")
				{
					$("#add-promo-result").append(text); 
					$("#add-promo").hide();
					$(".promo-code").html('<div class="promo-code"><div class="form-group applied-promo-code pointer" id="coupon-applied">'+promocode+'<a class="delete-promo-code pull-right"  name="'+promocode+'">X</a></div>');
					setTimeout(function close(){$(".close").click();}, 1000);
					$("#edit_promocode_input").val(promocode);
				}
				else
				{
					$("#add-promo-result").html(text); 
				}
				$("#promocode").val('');			
			},
		});	
				
	}
	else
	{
			setTimeout(function close(){$("#add-promo-result").html("<div class='warning'>Please Enter Promo Code</div>");}, 1000);
	}
});
/** end **/

/** delete Coupon code **/
$(document).on("click",".delete-promo-code",function(e){ 

$("#deletepromocode_trigger").click();
});

/** end **/




/** edit promocode submit **/
$("#edit-promo-submit").click(function(){

	$("#edit-promo-result").html('');
	$("#edit-promo-result").append("<div class='loading1'><img src='images/loading.gif'></div>");
	var promocode = $("#edit_promocode_input").val();
	if(promocode!="")
	{	
		$.ajax({
			url: 'function.php',
			type: 'POST',
			dataType: 'text',
			data:{function_name:'addPromoCode',promocode:promocode},
			success : function(text)
			{
				if(typeof $(text).find(".success").html()!=="undefined")
				{
					$("#edit-promo-result").append(text); 
					$("#add-promo").hide();
					$(".promo-code").html('<div class="promo-code"><div class="form-group applied-promo-code pointer" id="coupon-applied">'+promocode+'<a class="delete-promo-code pull-right"  name="'+promocode+'">X</a></div>');
					setTimeout(function close(){$(".close").click();$("#edit-promo-result").html(""); }, 1000);
					$("#edit_promocode_input").val(promocode);
					
				}
				else
				{
					$("#edit-promo-result").html(text); 
				}
							
			},
		});	
				
	}
	else
	{
			setTimeout(function close(){$("#add-promo-result").html("<div class='warning'>Please Enter Promo Code</div>");}, 1000);
	}
});
/** end **/




/** add insurance to request **/
$("#add-insurance-submit").click(function(){
	$("#add-insurance-result").html("");
	$("#add-insurance-result").append("<div class='loading1'><img src='images/loading.gif'></div>");
	var insurance_id      = $("#provider-name").attr('insurance_id');
	var insurance_name    = $("#provider-name").val();
	var memberid          = $("#memberid").val();
	var group	      = $("#group").val();
	var primary_name      = $("#primary_policy").val();
	var insurance_terms   = $("#insurance-terms:checked").length;
	var flag = 0;

	flag = 0;
	if(insurance_terms)
	{
		if(primary_name=="")
		{
			$("#add-insurance-result").html("<div class='warning'>Fill the Primary Name</div>");	
			flag++;
		}
		if(group=="")
		{
			$("#add-insurance-result").html("<div class='warning'>Enter Group</div>");	
			flag++;
		}
		
		if(memberid=="")
		{
			$("#add-insurance-result").html("<div class='warning'>Enter Member's Id</div>");	
			flag++;
		}
		if(insurance_name=="" && insurance_name.trim()=="")
		{
			$("#add-insurance-result").html("<div class='warning'> Enter a Insurance Provider</div>");	
			flag++;
		}	
		if(insurance_id=="0")
		{
			$("#add-insurance-result").html("<div class='warning'> Enter a Valid Insurance Provider</div>");	
			flag++;
		}
		if(insurance_id=="0" && memberid=="" && group=="" && primary_name=="")
		{
			$("#add-insurance-result").html("<div class='warning'>Fill out all of the fields below</div>");	
			flag++;
		}
		if(flag==0)
		{
			/** add insurance **/
			$.ajax({
				url: 'function.php',
				type: 'POST',
				dataType: 'text',
				data:{function_name:'addInsurancetoRequest',id:insurance_id,memberid:memberid,group:group,name:primary_name},
				success : function(text)
				{
					if(typeof $(text).find(".success").html()!=="undefined")
					{
						$("#add-insurance-result").append(text); 
						$(".insurance").html('<div class="apply-insurance"><div class="form-group applied-insurance pointer" id="insurance-applied">'+$("#provider-name").val()+'<a class="delete-insurance pull-right"  data-toggle="modal" data-target="#deleteinsurance">X</a></div>');
						setTimeout(function close(){$(".close").click();}, 1000);
					}
					else
					{
						$("#add-insurance-result").html(text); 
					}
				},
			});
			/** end **/	
		}
		 	
	}
	else
	{
			$("#add-insurance-result").html("<div class='warning'>Fill out all of the fields below</div>");
	}

});
/** end **/


/** delete Insurance **/
$(document).on("click","#delete_insurance",function(e){ 
	$("#delete-insurance-result").html("");
	$("#delete-insurance-result").html("<div class='loading1'><img src='images/loading.gif'></div>");
	 
	$.ajax({
			url: 'function.php',
			type: 'post',
			dataType:'text',
			data:{function_name:'deleteInsurance',status:"1"},
			success: function(text) {
					if(typeof $(text).find(".success").html()!=="undefined")
					{
						$("#delete-insurance-result").append(text); 
						$(".insurance").html('<br><input type="text" id="open-add-insurance" placeholder="Add Insurance" autocomplete="off" class="form-control input-height pointer">');
						setTimeout(function close(){$(".close").click();}, 1000);
						$("#delete-insurance-result").html(""); 
						 
					}
					else
					{
						$("#delete-insurance-result").html(text); 
					}
			},
		});	
});
/** end **/

/** Add Insurance to request trigger Request **/
$(document).on("click","#open-add-insurance",function(e){
	$("#add-insurance-result").html('');
	$("#provider-name").attr('insurance_id','0');
	$("#provider-name").val('');
	$("#memberid").val('');
	$("#group").val('');
	$("#primary_policy").val('');
	$("#add-insurance-forms").click();
});
/** end **/


/** edit Insurance trigger **/
$("#edit_insurance").click(function(){
	$(".close").click();
	$("#trigger_editinsurance").click();
});
/** end **/



/** get insurance **/
$("#trigger_editinsurance").click(function(){
	$("#edit_insurance_result").html("<div class='loading1'><img src='images/loading.gif'></div>");
	$.ajax({
			url: 'function.php',
			type: 'post',
			dataType:'text',
			data:{function_name:'getInsurance',status:"1"},
			success: function(text) {
				 if(typeof $(text).find(".success").html()!=="undefined")
				 { 
						var id            = $(text).find(".id").html();
						var provider_name = $(text).find(".provider_name").html();
						var memberid      = $(text).find(".memberid").html();
						var groupNumber   = $(text).find(".groupNumber").html();
						var name 	  = $(text).find(".name").html();

						$("#edit-provider-name").val(provider_name);
						$("#edit-provider-name").attr('insurance_id',id); 
						$("#memberid_1").val(memberid); 
						$("#group_1").val(groupNumber); 
						$("#primary_policy_1").val(name);
						$("#edit_insurance_result").html("");
				 }
				else
				{
				              	$("#edit_insurance_result").html(text);
				}
			},
		});	

});
/** end **/



/** edit Insurance button **/
$("#save-edit-insurance").click(function(){

	$("#edit_insurance_result").html("");
	$("#edit_insurance_result").append("<div class='loading1'><img src='images/loading.gif'></div>");
	var insurance_id      = $("#edit-provider-name").attr('insurance_id');
	var memberid          = $("#memberid_1").val();
	var group	      = $("#group_1").val();
	var primary_name      = $("#primary_policy_1").val();
	var insurance_terms   = $("#insurance-terms_1:checked").length;
	var flag = 0;

	flag = 0;
	if(insurance_terms)
	{
		if(primary_name=="")
		{
			$("#edit_insurance_result").html("<div class='warning'>Please Fill the Primary Name</div>");	
			flag++;
		}
		if(group=="")
		{
			$("#edit_insurance_result").html("<div class='warning'>Please Enter Group</div>");	
			flag++;
		}
		
		if(memberid=="")
		{
			$("#edit_insurance_result").html("<div class='warning'>Please Fill the Member's Id</div>");	
			flag++;
		}	
		if(insurance_id=="0")
		{
			$("#edit_insurance_result").html("<div class='warning'>Please Enter Insurance Provider</div>");	
			flag++;
		}
		if(insurance_id=="0" && memberid=="" && group=="" && primary_name=="")
		{
			$("#edit_insurance_result").html("<div class='warning'>Please Fill the Entire Form</div>");	
			flag++;
		}
		if(flag==0)
		{
			/** add insurance **/
			$.ajax({
				url: 'function.php',
				type: 'POST',
				dataType: 'text',
				data:{function_name:'addInsurancetoRequest',id:insurance_id,memberid:memberid,group:group,name:primary_name},
				success : function(text)
				{
					if(typeof $(text).find(".success").html()!=="undefined")
					{
						$("#edit_insurance_result").append("<br><div class='success'>Insurance has been successfully updated</div>"); 
						$(".insurance").html('<div class="apply-insurance"><div class="form-group applied-insurance pointer" id="insurance-applied">'+$("#edit-provider-name").val()+'<a class="delete-insurance pull-right"  data-toggle="modal" data-target="#deleteinsurance">X</a></div>');
						setTimeout(function close(){$(".close").click();}, 1000);
					}
					else
					{
						$("#edit_insurance_result").html(text); 
					}
				},
			});
			/** end **/	
		}
		 	
	}
	else
	{
			$("#edit_insurance_result").html("<div class='warning'>Please Check Terms</div>");
	}


}); 
/** end **/


/** delete Insurance in edit **/
$("#delete_insurance_edit").click(function(){
	$("#edit_insurance_result").html("");
	$("#edit_insurance_result").html("<div class='loading1'><img src='images/loading.gif'></div>");
	 
	$.ajax({
			url: 'function.php',
			type: 'post',
			dataType:'text',
			data:{function_name:'deleteInsurance',status:"1"},
			success: function(text) {
					if(typeof $(text).find(".success").html()!=="undefined")
					{
						$("#edit_insurance_result").append(text); 
						$(".insurance").html('<br><a class="pointer" id="open-add-insurance">+ Add Insurance</a>');
						setTimeout(function close(){$(".close").click();}, 1000);
						$("#edit_insurance_result").html(""); 
						 
					}
					else
					{
						$("#edit_insurance_result").html(text); 
					}
			},
		});	

});
/** end **/



/** create request **/
$("#comfirm-request").click(function(){
	var flag = 0;
	 
	$("#confirm-result").html("<div class='loading-center'><img src='images/loading.gif'></div>");

	$.ajax({
					url: 'function.php',
					type: 'POST',
					dataType: 'text',
					data:{function_name:'ComfirmRequest',status:"1"},
					success : function(text)
					{
						
					 	
						if(typeof $(text).find(".warning").html()!=="undefined" && text!=="1")
						{
							if(typeof $(text).find("#working_hour_error").html()!=="undefined")
							{
								$("#confirm-result").html('');
								$("#working_hours_buton").click();
								$("#working-operation").html("<div class='loading1'><img src='images/loading.gif'></div>");
								$("#working-operation").html($(text).find(".working").html());
									
							}
							else
							{
								$("#confirm-result").html(text);
							}
						}
						else
						{
							$("#alternateSubmit").click();// submit comfirm request
											
						}
					},
	});
});
/** end **/ 


/** delete Request **/
$("#cancel_request_button,#wait-loading-cancel").click(function(){
	$(".close").click();
	$("#delete-request-buttons").click();
});
/** end **/

/** delete Request redirect **/
$("#delete-request-submit").click(function(){

	$("#delete-request-result").html("<div class='loading1'><img src='images/loading.gif'></div>");
	
	$.ajax({
					url: 'function.php',
					type: 'POST',
					dataType: 'text',
					data:{function_name:'CancelRequest',status:"1"},
					success : function(text)
					{
						if(typeof $(text).find(".success").html()!=="undefined")
						{
							  window.setTimeout(function() {
    					       			window.location.href = 'cancel_request.php';
					      		  }, 2000);
						}
						else
						{
							$("#delete-request-result").html(text);												
						}
					},
	});	


});
/** end **/


/** notify working hour **/
$("#notify-submit").click(function(){
	

	$.ajax({
			url      : 'function.php',
			type	 : 'POST',
			dataType : 'text',
			data	 : {function_name:'Notifywhenavailable',status:"1"},
			success  : function(text)
			{
				$("#working-operation").append("<div class='loading1'><img src='images/loading.gif'></div>");
				$(".close").click();				
			 
				if(typeof $(text).find(".success").text()!=="undefned")
				{
					$("#notify-head").text($(text).find(".header").text());
					$("#notify-body").text($(text).find(".body").text());
					$("#notifymessage-button").click();			
				}
				else
				{		
					$("#notify-body").text($(text).find(".body").text());		
					$("#notifymessage-button").click();
				}
			},
	});	
	

});
/** end **/

/** load sorry modal **/
$("#close-working").click(function(){
 //$("#service-unavailable-button").click();
	
});
/** end **/



/** click back **/
$("#back_request").click(function(){
	window.location.href='dashboard.php';
});
/** end **/





/** delete promocode modal trigger **/
$("#delete_promocode").click(function(){
         $("#delete-promocode-result").html("<div class='loading-center'><img src='images/loading.gif'></div>");
	 /** delete promocode ajax **/
		$.ajax({
			url: 'function.php',
			type: 'POST',
			dataType: 'text',
			data:{function_name:'removePromoCode',status:'1'},
			success : function(text)
			{
                                $("#delete-promocode-result").html("");
				if(typeof $(text).find(".success").html()!=="undefined")
				{
					 $("#coupon-applied").before('<br><input type="text"  id="add-promo" placeholder="Enter Promo Code" autocomplete="off" class="form-control input-height pointer">');
					 $("#coupon-applied").remove();
					 $("#add-promo").show();
					 $(".close").click();
				}
				else
				{
					 
				}			
			},
		});
		/** end **/

});
$("#delete_promocode_edit").click(function(){
         $("#edit-promo-result").html("<div class='loading-center'><img src='images/loading.gif'></div>");
	 /** delete promocode ajax **/
		$.ajax({
			url: 'function.php',
			type: 'POST',
			dataType: 'text',
			data:{function_name:'removePromoCode',status:'1'},
			success : function(text)
			{
                                $("#edit-promo-result").html("");
				if(typeof $(text).find(".success").html()!=="undefined")
				{
					 $("#coupon-applied").before('<br><input type="text"  id="add-promo" placeholder="Enter Promo Code" autocomplete="off" class="form-control input-height pointer">');
					 $("#coupon-applied").remove();
					 $("#add-promo").show();
					 $(".close").click();
				}
				else
				{
					 
				}			
			},
		});
		/** end **/

});
/** end **/






/** edit promo-code trigger model **/
$("#edit_promocode").click(function(){
	$(".close").click();
	$("#edit-promocode-trigger").click();
});
/** end **/

});
$(function() {
				 
		$("#provider-name").autocomplete({
		source: "function.php?function_name=getInsuranceProvidersFilter",
		select: function( event, ui ) {
			if(typeof ui.item.value!=="undefined" && ui.item.value!="")
			{
				$.ajax({
					url  : 'function.php',
					type : 'POST',
					dataType : 'text',
					data     : {function_name:'getInsuranceIDbyLAbel',label:ui.item.value},
					success  : function(text)
					{
						$("#provider-name").attr("insurance_id",text);			
					},
				});
			
			}
		}
		});
		
		$("#edit-provider-name").autocomplete({
		source: "function.php?function_name=getInsuranceProvidersFilter",
		select: function( event, ui ) {
			if(typeof ui.item.value!=="undefined" && ui.item.value!="")
			{
				$.ajax({
					url  : 'function.php',
					type : 'POST',
					dataType : 'text',
					data     : {function_name:'getInsuranceIDbyLAbel',label:ui.item.value},
					success  : function(text)
					{
						$("#edit-provider-name").attr("insurance_id",text);			
					},
				}); 
			
			}
		}
		});	
});
 

