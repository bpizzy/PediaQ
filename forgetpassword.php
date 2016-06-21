<?php  require "function.php";// load main function file ?>
<?php isLogin(); ?>
<?php
	if(isset($_POST['submit']))
	{
		$field = $_POST['register-email'];
		/** if it is email **/
		if(preg_match("/\@/i",$field))
		{
			$data = array('email' => $field);
			$url = "api/auth/recovery-password-email";
			$return = json_decode(PostData($url,$data));// call recover password through sms Api

			/** error **/
	 
			if(!$return->success)
			{
				echo "<div class='warning container'>".$return->errors->message."</div>";
			}
			/** end **/
			/** success **/
			if($return->success)
			{
				echo "<div class='success container'>Reset Password Link has been Sent to your Email-Id.</div>";
			}
			/** end **/
		}
		/** end **/

		/** if it is phone **/
		if(preg_match("/\+/i",$field))
		{
		
			$data = array('phone' => $field);
			$url = "api/auth/recovery-password-sms";
			$return = json_decode(PostData($url,$data));// call recover password through sms Api

			/** error **/
			if(!$return->success)
			{
				echo "<div class='warning container'>".$return->errors->message."</div>";
			}
			/** end **/
			/** success **/
			if($return->success)
			{
				echo "<div class='success container'>".$return->errors->message."</div>";
			}
			/** end **/
		}
		/** end **/
	}
?>	
<!-- header -->
<?php getHeader();?>
<!-- end header -->
<div class="container">
	<div class="row">
		<h3 class="form-head">Forget Password<span><i class="forget-i">We will send you an email with a link to reset your password</i></span></h3>
		<!-- start page response --> 	
		<div id="page-result"></div>
		<!-- end -->
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<form class="add-patient-form"  method="post">
				<div class="form-group">
					<label for="mobile">Mobile # / Email</label>
					<input type="text" name="register-email" required="required" placeholder="Enter your Mobile # or Email" autocomplete="off" id="mobile" class="form-control">
				</div>
				<div class="clear"> </div>
				<div class="form-group">
				       <button type="submit" name="submit" class="btn btn-info next">Send</button> 
				</div>
			</form>
			<div class="col-md-12"><br></div> 
		</div>
		<div class="col-md-3"></div>
	</div>
</div>
<script src="js/forgot.js" type="text/javascript"></script> 
<?php getFooter();?> 
