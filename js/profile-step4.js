$("document").ready(function(){


setTimeout(function(){
  if ($('.success').length > 0) {
    $('.success').slideToggle();
  }
}, 5000);


   	$("#addLocation").click(function(){
                 $(".warning").remove();$(".address_main").removeClass("error-input");
		 $("#result").html("<div class='loading-center'><img src='images/loading.gif'></div>");
 		 var flag 	= 0;
		 var error      = "";
         	 var location 	= $("#autocomplete").val();
		 var suite	= $("#aptsuite").val();
		 var zip 	= $("#postal_code").val();
		 var snumber	= $("#street_number").val();
			
	 
	
		
		if(location.trim()=="")
		{
		        $(".address_main").addClass("error-input");
                        $("<span class='red-text'>Enter your Address</span>").insertAfter("#autocomplete");
			flag++;		
		}
		
		
		/** if error **/
		if(flag!=0)
		{
			$("#result").html("");
		}
		/** end **/

		/** if no error **/
		if(flag==0)
		{
		 
			
			$.ajax({
				url: 'function.php',
				type: 'post',
				dataType:'text',
				data: {function_name:'addAddress',address: location,snumber:snumber,zip:zip,apt:suite,mode:'<?php echo $mode;?>',address_id:'<?php echo $addressId;?>'},
				success: function(text) 
				{

					if(typeof $(text).find(".warning").html()=="undefined")//success
					{
						$("#result").html('');
						setTimeout(function close(){ window.location.href='profile-step5.php'}, 1000);
					}
					else
					{
						$("#result").html(text);
					}
				},
			
			});

			
		}
		/** end **/

        });

	 
});