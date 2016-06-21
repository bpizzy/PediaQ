$(document).ready(function(){

/** delete Request **/
$("#wait-loading-cancel").click(function(){
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


/** change payment Request **/
$("#change-payment-trigger").click(function(){
	$(".close").click();
	$("#change-payment-button").click();
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
	var html = $(this).text();
	if(address_id)
	{
		$.ajax({
			url: 'function.php',
			type: 'post',
			dataType:'text',
			data:{function_name:'updatedefaultaddress',address_id:address_id},
			success: function(text) {
				$(".visitss").html(html);
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
			data:{function_name:'deleteAddress',address_id:id},
			success: function(text) {
				if($(text).find(".warning").html()!=="undefined")
				{
					$(".visits").prepend(text);
					$(".close").click();
				}
				else
				{
					$("#"+id).remove();
					$(".visits").html(text);
					$(".close").click();
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
$("#add-promo").click(function(){
	$("#add-promo-result").html("");
	$("#add-promocode").click();
});
/** end **/


});

/** check availability **/
setInterval( function update() {
     	$("#page-result").html("<div class='loading-center'><img src='images/loading.gif'></div>");
	$.ajax({
			url: 'function.php',
			type: 'POST',
			dataType: 'text',
			data:{function_name:'acceptrequest',status:1},
			success : function(text)
			{
			  
				if(typeof $(text).find(".success").html()==="undefined")
				{
					if(typeof $(text).find(".operation-error").html()!=="undefined")
					{
						$("#workinghourend-trigger").click();	
						$("#page-result").html(text);		
					}
					 					
					if(typeof $(text).find(".admin-error").html()!=="undefined")
					{
						$("#admin-error-trigger").click();
						$("#page-result").html(text);			
					}

 						
					 				
				}
				if(text==="1")
				{
					window.location.href='accept_request.php';
				}			
			},
		});
} , 40000 );
/** end **/