<?php  require "function.php";// load main function file ?>
<?php isLogin(); ?>
<?php

	if(isset($_POST['submit']))
	{
	    	if(preg_match("/\+/i",$_POST['register-email']))
	    	{
			$number = $_POST['register-email'];
           	}
	        else
	        {
          		$number = "+1 ".$_POST['register-email'];
                }
	    	$data = array(
	  	 'phone'      => $number,
	 	 'password'   => $_POST['register-password']  	
	    	);

 		$url = "api/auth/register";
		$return = json_decode(PostData($url,$data));// call login Api

	        if(count($return))
		{
		 

		   /** success **/
		   if(isset($return->success) and $return->success!="")
		   {
			$_SESSION['user_token'] =  $return->token; 
			$_SESSION['success']    = "Thank you for the Registration. Please Complete Your Profile.";
			redirect("profile-step1.php");
		   }	
		   /** end **/

		   /** error has been encounter **/
		   if(isset($return->errors))
		   {?>
		   <div class="warning container">
			<?php foreach($return->errors as $key=>$error)
			{
			   echo strtoUpper($key)."-".$error[0]."<br>"; 			
			}?>
		   </div>
		   <?php }
	           /** end **/		
		}

	}
?>	
<!-- header -->
<?php getHeader();?>
<!-- end header -->

<div class="container">
	<!-- load all page responses -->
	<div id="page-result"></div> 
	<!-- end -->

	<div class="row">
		<h3 class="form-head">Create Account <span><i class="header-i">If you have an iPhone, please make the request on <a target="_blank" href="https://itunes.apple.com/us/app/pediaq/id973830641" style="text-decoration: underline !important;">your PediaQ app</a></i> </span></h3>
 		<div class="col-md-3"></div>
		<div class="col-md-6">
			<!-- Form starts here -->
			<form class="add-patient-form"  method="post">
				<div class="form-group mobile-main">
					<label for="mobile">Mobile #</label>
					<input type="text" name="register-email" placeholder="Enter your Mobile #" autocomplete="off" id="mobile" class="form-control">
				</div>
				<div class="form-group password-main">
					<label for="password">Password</label>
					<input type="password"  name="register-password" autocomplete="off" placeholder="Create your Password" id="password" class="form-control">
				</div>
				<div class="clear"> </div>
				<span class="small-span  pull-right">By creating an account, I accept PediaQ's <a href="http://www.pediaq.care/terms" target="_blank">Terms of Use</a> and <a href="http://www.pediaq.care/privacy" target="_blank">Privacy Policy</a> </span>
				<div class="clear"> </div>
				<div class="form-group">
				       <button type="submit" name="submit" class="btn btn-info next">Create Account</button><button type="button" class="btn btn-info back">Back</button>
				</div>
			</form>
			<!-- end of form -->
			<div class="col-md-12"><br></div> 
		</div>
		<div class="col-md-3"></div>
	</div>
</div>
<script src="js/jquery.maskedinput-1.3.js" type="text/javascript"></script>
<script src="js/register.js" type="text/javascript"></script>
<?php getFooter();?>
