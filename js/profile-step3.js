$("document").ready(function(){
   	$(".addchild").click(function(){
 		 var flag 	= 0;
		 var error      = "";
         var name 	= $("#cardnumber").val();
		 var birthmonth = $("#month").val();
		 var birthyear  = $("#year").val();
	
		
		if(name=="")
		{
			error+="Please Enter Firstname & Lastname<br>";
			flag++;		
		}
		if(name=="" && (birthmonth=="" || birthyear=="") )
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
			var dob = birthyear+"-"+birthmonth;
			$("#result").html("<div class='loading'><img src='images/loading.gif'></div>");
			$.ajax({
				url: 'function.php',
				type: 'post',
				dataType:'text',
				data: {function_name:'addChildajax',name: name,dob:dob},
				success: function(text) {
				$("#result").html(text);
		
				},
			
			});

			
		}
		/** end **/

        });

	 
});