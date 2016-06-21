<?php  require "function.php";// load main function file ?>
<?php CheckLogin(); ?>	
<!-- header -->
<?php getHeader();?>
<!-- end header -->
<?php
	
	if(isset($_POST["payment_method_nonce"]))
	{
	  	 
	        $data = array(
			"token" 	=> getUserToken(),
			"paymentNonce"  => $_POST["payment_method_nonce"],
			"isDefault"     => 1
		);
		$url  = "api/payment/add";
		$post = json_decode(PostData($url,$data)); 
	 	if($post->success==1)
		{
			$_SESSION['success'] = "Payment method is Saved";
			$_SESSION['actual_payment_token'] = $post->paymentMethod->paymentToken;
			redirect("profile-step4.php");	
		}
		else
		{
			$_SESSION['error'] = $post->errors->message;
		}
		redirect("profile-step4.php");	
		 
	}
?>
<div class="container">
	<div class="row">
		<h3 class="form-head">Payment Method<span><b class="steps">Step 3 of 5</b></span></h3>
	 	<div class="col-md-3"></div>
		<div class="col-md-6">
			<form class="add-patient-form"   id="checkout" method="post">
			  <div id="payment-form"></div>
			      <div class="form-group">
			      <p class="profile4-charged">This payment method will only be charged when PediaQ Pediatric specialist visits the patient.<b><i><b class="black-color"> No charged for phone consulatation</b></i></b></p>
				  </div>
				  <div class="form-group">
			       <button type="submit" name="submit"  class="btn btn-info next">Next</button><button type="button" class="btn btn-info back"  onclick="window.location.href='profile-step2.php'">Back</button>
			      </div>
			</form>
			<div class="col-md-12"><br></div> 
		</div>
		<div class="col-md-3"></div>
	</div>
</div>
<script src="https://js.braintreegateway.com/js/braintree-2.22.2.min.js"></script>
<script src="js/profile-step3.js"></script>
<script>
// We generated a client token for you so you can test out this code
// immediately. In a production-ready integration, you will need to
// generate a client token on your server (see section below).
var clientToken = "<?php echo getPaymentToken();?>";

braintree.setup(clientToken, "dropin", {
  container: "payment-form"
});
</script>
<?php getFooter();?>
