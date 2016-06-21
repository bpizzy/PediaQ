<?php require "function.php"; getHeader();// load main function file and then load header ?>

<div class="container">
  <div class="row">
  <h3 class="form-head">Forget Password<span>We will send you an email with a link to reset your password</span></h3>
  <div class="col-md-3"></div>
<div class="col-md-6">

<form class="add-patient-form"  method="post">
<div class="form-group">
        <label for="mobile">Mobile # / Email</label>
        <input type="text" name="register-email" placeholder="Enter your Mobile # or Email" autocomplete="off" id="mobile" class="form-control">
      </div>

 
     <div class="clear"> </div>
      
      <div class="form-group">
       <button type="submit"  class="btn btn-info next">Send</button> 
      </div>
</form>

<div class="col-md-12"><br> 

</div> 

</div>

<div class="col-md-3"></div>
</div>
</div>


<?php getFooter();?>
<script>
$("document").ready(function(){
var flag  = 0;
var error = "";
	$(".next").click(function(){
         var mobile = $("#mobile").val();
	 var password = $("#password").val();
	 	if(mobile=="")
   		{
		   error = "please enter mobile";
		   flag++;		
		}
		if(password=="")
   		{
		   error = "please enter Password";
		   flag++;		
		}
		if(mobile=="" && password=="")
   		{
                   error = "please enter mobile # & Password";
		   flag++;		
		}
		if(flag==0)
		{
		return true;		
		}
		else
		{
		alert(error);
		return false;	
		}
				
	});
	
   	$(".back").click(function(){
		window.location.href="index.php";
	});
});
</script>

