<?php  require "function.php";// load main function file ?>
<?php CheckLogin(); ?>	
<!-- header -->
<?php getHeader();?>
<!-- end header -->
<?php
	
	$getinsurance = getParentInsurance();
	if($getinsurance->success=="1" and count($getinsurance->insurance)) // get insurance data if any
	{
		$insurance_id    	=   $getinsurance->insurance->provider->id;
		$insurance_name		=   $getinsurance->insurance->provider->name;
		$member_id		=   $getinsurance->insurance->memberId;
		$group_id 		=   $getinsurance->insurance->groupNumber;
		$name			=   $getinsurance->insurance->holderFirstName." ".$getinsurance->insurance->holderLastName;
	}
	else
	{
		$insurance_id    	=   0;
		$insurance_name		=   "";
		$member_id		=   "";
		$group_id 		=   "";
		$name			=   "";
	}
	
	if(isset($_POST['submit']))
	{
	  				
			if($return->success)
			{
		           $_SESSION['user_token'] =  $return->token;
			   Header( "HTTP/1.1 301 Moved Permanently" ); 
			   Header( "Location: profile-step5.php" ); 
			}
			/** end **/	
	}
?>
<div class="container">
	<div class="row">
		<h3 class="form-head">Add Insurance<span><b class="steps">Step 5 of 5</b></span></h3>
 		<div class="col-md-3"></div>
		<div class="col-md-6">
			<div id="result"></div>
			<div id="add-insurance-result"></div>
			<form class="add-patient-form add-insurance-form" method="post">
				<div class="form-group">
					<label for="provider-name">PROVIDER</label>
					<div class="ui-widget">
						<input type="text" id="provider-name" name="provider" value="<?php echo $insurance_name;?>" placeholder="Enter Provider"   class="form-control" insurance_id="<?php echo $insurance_id;?>">
					</div>
				</div>
				<div class="form-group">
				 	<label for="memberid">MEMBER ID</label>
					<input type="text" value="<?php echo $member_id;?>" placeholder="Enter your member ID" autocomplete="off" id="memberid" class="form-control">
				</div>
				<div class="form-group">
					<label for="group">GROUP #</label>
					<input type="text" id="group" value="<?php echo $group_id;?>" placeholder="Enter your group #" autocomplete="off"   class="form-control">
				</div> 
				<div class="form-group">
					<label for="primary_policy">PRIMARY POLICY HOLDER</label>
					<input type="text" id="primary_policy" value="<?php echo $name;?>" placeholder="Enter primary policy holder name" autocomplete="off" class="form-control">
			      </div>
			      <div class="clear"> </div>
			      <div class="form-group">
				   	<label for="insurance-terms"></label>
				   	<input type="checkbox" id="insurance-terms">
				   	<span class="charged">I understand that health insurance is intended to cover some, but not all of the cost of my child's healthcare.Most plans require co-payments, deductibles and/or co-insurance expenses, which much be paid by the patient.&nbsp;&nbsp;<a href="http://www.pediaq.care/insurance/" target="_blank" class="insurance-read-more">Learn more</a></span>
			      </div>
			      <div class="clear"> </div>
  			      <div class="form-group">
				 <div class="col-md-7 pull-left request-a text-right">
					<a class="pointer" id="back_request">Back</a>&nbsp;|&nbsp;<a class="pointer" href="dashboard.php">Skip</a>&nbsp;
				 </div> 
				 <div class="col-md-5 pull-right">
					<button type="button" name="submit" id="add-insurance-submit" class="btn btn-info next">Next</button>
				 </div>
	                     </div>
			</form>
			<div class="col-md-12"></div> 
		</div>
		<div class="col-md-3"></div>
	</div>
</div>
<script src="js/libs-1.11.3.js" type="text/javascript"></script> 
<script src="js/bootstrap.js" type="text/javascript"></script>
<link href="//code.jquery.com/ui/1.10.2/themes/smoothness/jquery-ui.css" rel="Stylesheet" />
<script src="//code.jquery.com/ui/1.10.2/jquery-ui.js" ></script>
<script>
$(document).ready(function() {
				 
		$("#provider-name").autocomplete({
			  
		source: "function.php?function_name=getInsuranceProvidersFilter",
		select: function( event, ui ) {
			if(typeof ui.item.value!=="undefined" && ui.item.value!="")
			{
				$.ajax({
					url: 'function.php',
					type: 'POST',
					dataType: 'text',
					data:{function_name:'getInsuranceIDbyLAbel',label:ui.item.value},
					success : function(text)
					{
						$("#provider-name").attr("insurance_id",text);			
					},
				});
			
			}
		}
		});
	});

</script>
<script src="js/profile-step5.js" ></script>
</body>
<?php unsetGlobalWarning();?>
</html>
