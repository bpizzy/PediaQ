<?php  require "function.php";// load main function file ?>
<?php CheckLogin(); ?>	
<!-- header -->
<?php getHeader();?>
<!-- end header -->
<div class="container">
	<div class="col-md-12">
		<h3 class="form-head">Your Request has been Cancelled<span>If you have any questions or feedback, please contact us at <a href="mailto:support@pediaq.care" target="_top">support@pediaQ.care</a></span></h3>
 		<div class="col-md-2"></div>
		<div class="col-md-8 no-margin-left">
			<div id="result"></div>
			<div id="add-insurance-result"></div>
			<form class="add-patient-form add-child-form" method="post">
 				<div class="col-md-12 pull-right no-margin-left">
        				<button type="button" name="submit"   onclick="window.location.href='dashboard.php'" class="btn btn-info next pull-left no-margin-left">Request a Pediatric Specialist</button>
	 				<button type="button" name="submit" onclick="window.open('https://www.facebook.com/pediaq/?ref=py_c','_blank'); "     class="btn btn-info next_blank pull-left">Follow Us on Facebook</button>
				</div>
 			</form>
			<div class="col-md-12"><br></div> 
		</div>
		<div class="col-md-2"></div>
	</div>
</div>
<?php getFooter();?>
