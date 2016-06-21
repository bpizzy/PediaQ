<?php  require "function.php";// load main function file ?>
<?php CheckLogin();AddCommentVerification(); ?>	
<!-- header -->
<?php getHeader();?>
<!-- end header -->
<link rel="stylesheet" href="rating/min/jquery.rateyo.min.css"/>
<?php 
	$nurseProfile = getNurseProfile($_SESSION['request_nurse_id']);
	if($nurseProfile->success=="1")
	{
		$image = $nurseProfile->nurse->image;
	}
	else
	{
		$image = "images/nurse_dummy.png";
	}
?>
<div class="container">
	<div id="page-result"></div> 
	<div class="row">
		<h3 class="form-head">Visit Total: $<?php echo $_SESSION['amount'];?><span><?php echo $_SESSION['end_time'];?></span></h3>
 		<div class="col-md-3"></div>
		<div class="col-md-6 no-margin-left text-center">
			<div id="result"></div>
			<div id="add-insurance-result"></div>
			<form class="add-patient-form add-child-form" method="post">
  				<img alt="nurse-profile" src="<?php echo $image;?>" class="nurse_image"/>
				<div class="rateyo"></div>
  				<div class="counter"></div>
				<div class="clearfix"></div>
				<p id="experience">How was your experience with PediaQ?</p>
				<div class="form-group" id="comments-div">
					<p>Any Comments?</p>
					<textarea cols="50" id="comments" rows="5"  placeholder="Enter your Comment"></textarea>
					<input type="hidden" id="counter" name="counter"/> 
					<div class="form-group">
						<button type="button" name="submit" id="savecomment" class="btn btn-info next1" style="float:none;">Save</button>
					</div>
				</div>
			</form>
		<div class="col-md-12"><br></div> 
		</div>
		<div class="col-md-3"></div>
	</div>
</div>
<script type="text/javascript" src="rating/src/new.js"></script>
<script type="text/javascript" src="rating/src/jquery.rateyo.js"></script>
<script src="js/add-comment.js"></script>
