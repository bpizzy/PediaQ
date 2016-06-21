$('#mobile').keyup(function(e) {

var unicode=e.keyCode? e.keyCode : e.charCode;

if( unicode >= 48 && unicode <= 57) {

var a = parseInt($(this).val().split("-"));

if($.isNumeric(a) && a!=0)
{
  	foo = $(this).val().split("-").join(""); // remove hyphens
 	foo = foo.match(new RegExp('.{1,4}$|.{1,3}', 'g')).join("-");
        $(this).val(foo);
}
}
else
{

        // Filter non-digits from input value.
        this.value = this.value.replace(/\D/g, '');

}
});
$("document").ready(function(){

 
var flag  = 0;
var error = "";
	$(".next").click(function(){
         $(".red-text").hide();  
         $(".red-text").remove();$(".warning").remove();
	 flag  = 0;
         var mobile = $("#mobile").val();
	 var password = $("#password").val();
	 	if(mobile=="")
   		{
                   $(".mobile-main").addClass("error-input");
                   $("<span class='red-text'>Enter mobile #</span>").insertAfter("#mobile");
		   flag++;		
		}
                else
                {
                   $(".mobile-main").removeClass("error-input"); 
                }
		if(password=="")
   		{
                   $(".password-main").addClass("error-input");
		   $("<p class='red-text'>Enter Password</p>").insertAfter("#password");
		   flag++;		
		}
                else
                {
                   $(".password-main").removeClass("error-input");
                }
                if(password.length<6 && password!="")
   		{
                   $(".password-main").addClass("error-input");
                   $("<p class='red-text'>Password must be at least 6 characters</p>").insertAfter("#password");
		   flag++;		
		}
                
		 
		if(flag==0)
		{
                $(".password-main").removeClass("error-input");
		return true;		
		}
		else
		{
		return false;	
		}
				
	});
	
   	$(".back").click(function(){
		window.location.href="index.php";
	});
});
 

