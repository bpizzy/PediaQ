<?php  require "function.php";// load main function file ?>
<?php isLogin(); ?>	
<!-- header -->
<?php getHeader();?>
<!-- end header -->
<?php

	if(isset($_POST['submit']))
	{

		$flag = 0;
	  	$field = $_POST['register-email'];
	  	/** email check **/
		if(preg_match("/\@/i",$field))
		{
		    $data = array(
		  	 'email'      => $_POST['register-email'],
		 	 'password'   => $_POST['register-password']  	
		    );

 		$url = "api/auth/login";
		$return = json_decode(PostData($url,$data));// call login Api
			
			/** error **/				
			if(!$return->success)
			{
			   echo "<div class='warning container'>".$return->errors->message."</div>";		
			}
			/** end **/	

			/** error **/				
			if($return->success)
			{
		           $_SESSION['user_token'] =  $return->token;
			   redirect("dashboard.php");
			}
			/** end **/
			$flag++;	
		}
	   	/** end **/


		/** email check **/
		if(preg_match("/\-/i",$field) or preg_match("/\+/i",$field))
		{
		     if(preg_match("/\+/i",$field))
		     {
			 $number = $_POST['register-email'];
		     }
		     else
		     {
          		$number = "+1 ".$_POST['register-email'];
                     }
		     
		     $data   = array(
		  	 'phone'      => $number,
		 	 'password'   => $_POST['register-password']  	
		     );

 		     $url = "api/auth/login";
		     $return = json_decode(PostData($url,$data));// call login Api
		 
			 
			/** error **/				
			if(!$return->success)
			{
			   echo "<div class='warning container'>".$return->errors->message."</div>";		
			}
			/** end **/	

			/** error **/				
			if($return->success)
			{
		           $_SESSION['user_token'] =  $return->token;
			   redirect("dashboard.php");
			}
			/** end **/	
			$flag++;
		}
	   	/** end **/

		if($flag==0)
		{
			 echo "<div class='warning container'>Please enter correct Email/Mobile # format</div>";
		}
	}

?>
<div class="container">
	<div class="row">
		<h3 class="form-head">Welcome back!<span><i class="header-i">If you have an iPhone, please make the request on <a target="_blank" href="https://itunes.apple.com/us/app/pediaq/id973830641" style="text-decoration: underline !important;">your PediaQ app</a></i> </span></h3>
 		<div class="col-md-3"></div>
		<div class="col-md-6">
			<!-- form starts here -->			
			<form class="add-patient-form"  method="post">
				<div class="form-group mobile-main">
					<label for="mobile">Mobile # / Email</label>
					<input type="text" name="register-email" placeholder="Enter your Mobile # or Email" autocomplete="off" id="mobile" class="form-control">
	      			</div>

				<div class="form-group password-main">
					<label for="password">Password</label>
					<input type="password"  name="register-password" autocomplete="off" placeholder="Enter your Password" id="password" class="form-control">
				</div>
	 
				<div class="form-group">
				      <a class="pull-right forget" href="forgetpassword.php" style="text-decoration: underline !important;">Forget Password</a>
				</div>
				<div class="clear"></div>
		                <div class="form-group">
			       	      <button type="submit" name="submit"  class="btn btn-info next">Sign In</button><button type="button" class="btn btn-info back">Back</button>
			      </div>
			</form>
			<!-- form ends here -->
			<div class="col-md-12"><br></div> 
		</div>
		<div class="col-md-3"></div>
	</div>
</div>

<script src="js/jquery.maskedinput-1.3.js" type="text/javascript"></script>
<script src="js/login.js" type="text/javascript"></script>
<?php getFooter();?>


