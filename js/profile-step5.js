$("document").ready(function(){


/** add insurance to request **/
$("#add-insurance-submit").click(function(){
	$("#add-insurance-result").html("");
	$("#add-insurance-result").append("<div class='loading1'><img src='images/loading.gif'></div>");
	var insurance_id      = $("#provider-name").attr('insurance_id');
	var memberid          = $("#memberid").val();
	var group	      = $("#group").val();
	var primary_name      = $("#primary_policy").val();
	var insurance_terms   = $("#insurance-terms:checked").length;
	var insurance_name    = $("#provider-name").val();
	var flag 	      = 0;
	var flag_new 	      = 1;

	flag = 0;
	if(insurance_terms)
	{
		
		
		
		if(primary_name=="")
		{
			$("#add-insurance-result").html("<div class='warning'>Please Fill the Primary Name</div>");	
			flag++;
		}
		if(group=="")
		{
			$("#add-insurance-result").html("<div class='warning'>Please Enter Group</div>");	
			flag++;
		}
		
		if(memberid=="")
		{
			$("#add-insurance-result").html("<div class='warning'>Please Fill the Member's Id</div>");	
			flag++;
		}	
		if(insurance_id=="0" || insurance_name.trim()=="")
		{
			$("#add-insurance-result").html("<div class='warning'>Please Enter Insurance Provider</div>");	
			flag++;
		}
                
		if(insurance_id=="0" && memberid=="" && group=="" && primary_name=="")
		{
			$("#add-insurance-result").html("<div class='warning'>Please Fill the Entire Form</div>");	
			flag++;
		}
		if(flag==0)
		{
			

			/** check valid insurance **/
			$.ajax({
				url: 'function.php',
				type: 'POST',
				dataType: 'text',
				data:{function_name:'getInsuranceIDbyLAbel',label:insurance_name},
				success : function(response)
				{	
				 	 
                                        if(response=="0") // fail
					{ 
						$("#add-insurance-result").html("<div class='warning'>Please Enter Valid Insurance Provider</div>");						flag_new=0;
					}
					else  // success
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
									setTimeout(function close(){window.location.href='request_summary.php'}, 1000);
								}
								else
								{
									$("#add-insurance-result").html(text); 
								}
							},
						});
						/** end **/	
					}
				},
			});
			/** end of checking **/	

		}
		 	
	}
	else
	{
			$("#add-insurance-result").html("<div class='warning'>Fill out all of the fields below</div>");
	}

});
/** end **/


/** back button trigger **/
$("#back_request").click(function(){
	window.location.href='profile-step4.php';
});
/** end **/


	$("#search-box").keyup(function(){
		$.ajax({
		type: "POST",
		url: "function.php",
		data:'keyword='+$(this).val(),
		beforeSend: function(){
			$("#search-box").css("background","#FFF url(LoaderIcon.gif) no-repeat 165px");
		},
		success: function(data){
			$("#suggesstion-box").show();
			$("#suggesstion-box").html(data);
			$("#search-box").css("background","#FFF");
		}
		});
	});
});

function selectCountry(val) {
$("#search-box").val(val);
$("#suggesstion-box").hide();
}
 
