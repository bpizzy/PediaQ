<?php require "function.php"; getHeader();// load main function file and then load header ?>


<div class="container">

 
<div class="row">

<h3 class="form-head">Reset Password</h3>
 
<div class="col-md-3"></div>

<div class="col-md-6">

<form class="add-patient-form"  method="post">
<div class="form-group">
        <label for="mobile">New Password</label>
        <input type="password" name="password" placeholder="Enter your New Password" autocomplete="off" id="password" class="form-control">
      </div>

  <div class="form-group">
      <a class="pull-right" id="reset">Show Password</a>
      <a class="pull-right" id="hide">hide Password</a>
      </div>
 
     <div class="clear"> </div>
      
      <div class="form-group">
       <button type="submit"  class="btn btn-info next">Reset</button> 
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
 	$("#hide").hide();
	$("#reset").click(function(){
        	$("#password").attr("type","text");
		$(this).hide();
		$("#hide").show();
	});
	
   	$("#hide").click(function(){
	 	$("#password").attr("type","password");
		$(this).hide();
		$("#reset").show();
	});
});
</script>

