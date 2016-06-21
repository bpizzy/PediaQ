<?php  require "function.php";// load main function file ?>
<?php CheckLogin(); ?>	
<!-- header -->
<?php getHeader();?>
<!-- end header -->
<?php $profile = getProfile("all");// get all profile data?>
<div class="container">
	<div class="row">
		<h3 class="form-head">Complete Profile<span><b class="steps">Step 1 of 5</b></span></h3>
 		<div class="col-md-3"></div>
		<div class="col-md-6">
			<!-- form starts here -->
			<form class="add-patient-form"  method="post">
				<div id="results"></div>
				<div class="form-group name-main">
					<label for="register-name">Full Name</label>
					<input type="text" name="register-name" placeholder="Enter your first & last name" value="<?php if($profile->first_name){echo $profile->first_name.' '.$profile->last_name;}?>" autocomplete="off" id="register-name" class="form-control">
				</div>
				<div class="form-group email-main">
					<label for="register-email">Email</label>
					<input type="email"  name="register-email" autocomplete="off" placeholder="Enter your email address" id="register-email" value="<?php echo $profile->email;?>" class="form-control">
				</div>
				<div class="clear"> </div>
				      <div class="form-group">
				      <button type="button" name="button"  class="btn btn-info next">Next</button><button type="button" class="btn btn-info back">Cancel</button>
				</div>
			</form>
			<!-- end -->
			<div class="col-md-12"><br></div> 
		</div>
		<div class="col-md-3"></div>
	</div>
</div>
<!-- error modal Starts here -->
<!-- Trigger the modal with a button -->
<button type="button" class="btn btn-info btn-lg hide" id="error-modal" data-toggle="modal" data-target="#myModal">Open Modal</button>
<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">
    <!-- Modal content-->
	    <div class="modal-content">
		      <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h3 class="model-title">Error</h3>
		      </div>
	      <div class="modal-body">
		      <div id="error-text"></div>
	      </div>
	      <div class="modal-footer">
		      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	      </div>
	    </div>
    </div>
</div>
<!-- end -->
<script src="js/profile-step1.js" type="text/javascript"></script> 
<?php getFooter();?>
