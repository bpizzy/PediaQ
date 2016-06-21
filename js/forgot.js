$("document").ready(function(){
var flag  = 0;
var error = "";
	$(".next").click(function(){
         var mobile = $("#mobile").val();
	 var password = $("#password").val();
	 	if(mobile=="")
   		{
		   error = "Enter your Mobile # or Email";
		   flag++;		
		}
		
		if(flag==0)
		{
		return true;		
		}
		else
		{
		$("#page-result").html("<div class='warning'>"+error+"</div>");
		return false;	
		}
				
	});
	
   	$(".back").click(function(){
		window.location.href="index.php";
	});
});