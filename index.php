<?php  require "function.php";// load main function file ?>
<?php isLogin(); ?>	
<!-- header -->
<?php getHeader();?>
<!-- end header -->
<div class="container">
	<div class="row">
		<h3 class="form-head">Request a PediaQ Pediatric Specialist <span>Do you already have account with PediaQ?</span></h3>
 		<div class="col-md-3"></div>
		<div class="col-md-6">
			<!-- form starts here -->
			<form class="add-patient-form" onsubmit="return false;">
				<div class="form-group">
       					<button type="button" class="btn btn-info next-center">Yes, let me Sign in</button>
      				</div>
            			<div class="clear"></div>
      				<div class="form-group">
			       		<button type="button" class="btn btn-info white-center">No, let me create an Account</button>
			      	</div>
			</form>
			<!-- form ends here -->
			<div class="clearfix"></div>
			<div class="col-md-12"><br><br>  
				<i class="notification-bold">If you have an iPhone, please make the request on <a target="_blank" href="https://itunes.apple.com/us/app/pediaq/id973830641" style="text-decoration: underline !important;">your PediaQ app</a></i> 
			</div> 
		</div>
		<div class="col-md-3"></div>
	</div>
</div>
<script src="js/index.js" type="text/javascript"></script> 
<?php getFooter();?>
