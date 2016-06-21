$("document").ready(function(){
	 var flag  = 0;
	 var error = "";
	 $(".next").click(function(){
         flag  = 0;
         $(".red-text").remove();$(".warning").remove();$(".name-main,.email-main").removeClass("error-input");
	 $("#results").html("<div class='loading1'><img src='images/loading.gif'></div>");
         var full_name = $("#register-name").val();
	 var email = $("#register-email").val();
	 	if(full_name=="")
   		{
		   $(".name-main").addClass("error-input");
                   $("<span class='red-text'>Enter your full name</span>").insertAfter("#register-name");
		   flag++;		
		}
		if(email=="")
   		{
                   $(".email-main").addClass("error-input");
                   $("<span class='red-text'>Enter Email Address</span>").insertAfter("#register-email");
		   flag++;		
		}


		var atpos = email.indexOf("@");
    		var dotpos = email.lastIndexOf(".");
		/** email validation **/
    		if ((atpos<1 || dotpos<atpos+2 || dotpos+2>=email.length) && email.length!=0) {
        		
                        $(".email-main").addClass("error-input");
                        $("<span class='red-text'>Enter a valid email address</span>").insertAfter("#register-email");
		   	flag++;	 
    		}
		 
		 
		

	 
		 
		if(flag==0)
		{
			$(".close").click();
			$.ajax({
				url: 'function.php',
				type: 'post',
				dataType:'text',
				data: {function_name:'getEditProfile',name:full_name,email:email,submit:1},
				success: function(text) {
				 if(text==="1")
				 {
					//$("#results").html("<div class='success'>General Info Updated Successfully</div>");
					$("#results").append("<div class='loading1'><img src='images/loading.gif'></div>");
					window.setTimeout(function() {
    					window.location.href = 'profile-step2.php';
					}, 2000);
				 }
				 else
				 { 
				 	$("#results").html(text);
				 }				
				 },
			
			});
		        	
		}
		else
		{
			$("#results").html("");
			//$("#error-text").html(error);	
			//$("#error-modal").click();
			return false;	
		}
				
	});
	
   	$(".back").click(function(){
		window.location.href="index.php";
	});
});