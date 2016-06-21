<?php  require "function.php";// load main function file ?>
<?php CheckLogin(); ?>	
<!-- header -->
<?php getHeader();?>
<!-- end header -->
<div class="container">
	<div class="row">
		<h3 class="form-head">Thank you for using PediaQ<span>If you have any questions or feedback, Please contact us at <a href="mailto:support@pediaq.care" target="_top">support@pediaq.care</a></span></h3>
 		<div class="col-md-3"></div>
		<div class="col-md-8 no-margin-left">
			<div id="result"></div>
			<div id="add-insurance-result"></div>
			<form class="add-patient-form add-child-form" method="post">
 				<div class="col-md-12 pull-right no-margin-left">
        				<button type="button" name="submit"   onclick="window.location.href='dashboard.php'" class="btn btn-info next pull-left no-margin-left">Request a Pediatric Specialist</button>
	 				<button type="button" name="submit" onclick="window.location.href='https://www.facebook.com/pediaq/?ref=py_c'" class="btn btn-info next_blank pull-left">Follow Us on Facebook</button>
				</div>
 			</form>
			<div class="col-md-12"><br></div> 
		</div>
		<div class="col-md-3"></div>
	</div>
</div>
<?php getFooter();?>
