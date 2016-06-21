$("document").ready(function(){

$(".pointer").click(function(){
      $("#result,#page-result").html('');
});

$(".child").click(function()
	{
		if($(this).hasClass("selected"))
		{
			$(this).removeClass("selected");		
		}
		else
		{	
			$("#page-result").html('');
			$(this).addClass("selected");
		}
	});

	$("#request_button").click(function(){
		$("#page-result").html('');
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
			$("#page-result").html("<br><div class='warning'>Please Select at least one Child to Proceed with request.</div><br><br>"); // load error modal
		}
	});

   	$(".addchild").click(function(){
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
			$("#result").html("<div class='loading'><img src='images/loading.gif'></div>");
			$.ajax({
				url: 'function.php',
				type: 'post',
				dataType:'text',
				data: {function_name:'addChildajax',name: name,gender:gender,dob:dob},
				success: function(text) {
				$("#result").html(text);
				setTimeout(function(){
				    location.reload();
				},5000);
				},
			
			});

			
		}
		/** end **/

        });

	 
});