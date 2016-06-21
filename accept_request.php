<?php  require "function.php";// load main function file ?>
<?php CheckLogin(); ?>	
<!-- header -->
<?php getHeader();?>
<!-- end header -->
<?php
	$current = getCurrentRequestStatus();
	/** check if not accepted **/
	if($current->success=="1" and $current->request->nurseId=="")
	{	
		redirect("waiting.php");	
	}
	/** end **/ 
	
	$_SESSION['request_nurse_id'] = $current->request->nurseId;
	$profile  		= getProfile("all");
	$getaddress 		= getAddressbyId($_SESSION['request_address']);
	$nurseProfile 		= getNurseProfile($_SESSION['request_nurse_id']);
	$nurses  		= getAvailabeleNurse();
	$getNurseLocation 	= getNurseLocation($nurseProfile->nurse->id);
	if($nurseProfile->success=="1")
	{
		$image      	= $nurseProfile->nurse->image;
		$name       	= $nurseProfile->nurse->name;
		$average_rating = ($nurseProfile->nurse->averageRating/10);
		$reviews 	= count($nurseProfile->nurse->comments);
		$comments 	= $nurseProfile->nurse->comments;
		$specialist	= $nurseProfile->nurse->specialization;
		$language       = "English";
		$education      = $nurseProfile->nurse->education;
		if($nurseProfile->nurse->resume!="")
		{
			$bio 	= $nurseProfile->nurse->resume; 
		}
		else
		{
			$bio 	= "No Information Specified";	
		}
	}
	else
	{
		$image = "images/nurse_dummy.png";
	}

 	/** save card **/
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
		 
	}
	/** end **/
	$payment = getPaymentCards();
	
?>

<div class="container">
	<div id="page-result"></div>
 	<div class="row">
		<h3 class="form-head">Request Accepted<span> <i> You will receive a call from your Pediatric Specialist in  10 minutes </i></span></h3><br>
		<form method="post">
			<div class="col-md-7">
				<div class="col-md-2">
					<img alt="profile-image" src="<?php echo $image;?>" class="pull-right nurse_image"/>
				</div>
				<div class="col-md-9">
					<p><b><?php echo $name;?></b><br><span id="review-tab" data-toggle="modal" data-target="#reviews-modal" class="pointer"><img height="20" alt="reviews" src="images/stars.png"/><span class="blue-text"> <?php echo $average_rating." / ".$reviews;?> Reviews</span> </span></p>
					<p><br></p>
					<p class="text-uppercase">Specialist</p>
					<p><?php echo $specialist;?></p>
					<p><br></p>
					<p class="text-uppercase">Bio</p>
					<p class="bio"><?php echo $bio;?></p>
					<?php if(strlen($bio)>200){?>
					<a class="read-more-button pointer" id="bio" data-toggle="modal" data-target="#resumetrigger">Read More</a>
					<?php }?>
 					<p><br></p>
					<p class="text-uppercase">Education</p>
					<?php if(count($education)){?>
					<p class="education"><?php echo $education[0]->university;?>, <?php echo $education[0]->degree;?> <?php echo $education[0]->year;?> </p>
					<?php }else{
					echo '<p class="education">No Information Specified</p>';
					}?>
 					<?php if(count($education)>1){?>
					<a class="read-more-button pointer" id="education" data-toggle="modal" data-target="#educationtrigger">Read More</a>
					<?php }?>
					<p><br></p>
					<p class="text-uppercase">Language</p>
					<p class="language"> <?php echo $language;?> </p>
				</div>
			</div>
			<div class="col-md-4 border">
				<div id="map" style="width:100%; height: 250px;"></div>
				<br><br>
				<div class="col-md-12">
					<b>Visit Location </b><br><br>
					<p class="visitss"><?php echo $getaddress->street; ?></p>
				</div>
				<div class="col-md-12">
					<b>Payment Method <a class="pull-right pointer" id="change-payment-trigger">Change</a></b> <br><br>
					<?php if(count($payment)){ foreach($payment as $card){ if($card->isDefault=="1"){?>
					<p><?php echo $card->paymentMethodInfo->paymentTypeName." ...".$card->paymentMethodInfo->last4;?></p>
					<?php }}}?>
				<!-- end payments -->
				</div>
				<button type="button" name="submit" data-toggle="modal" data-target="#contact_us_modal" class="btn btn-info next_blank_contact pull-left">Got Questions? Contact us!</button>
				<p class="text-center pointer" id="wait-loading-cancel" style="clear:both">Cancel Request</p>
	     		</div>
		</form>
	</div>
</div>

<br><br><br>


<!-- error modal -->

<!-- Trigger the modal with a button -->
<button type="button" class="btn btn-info btn-lg hide" id="error-modal" data-toggle="modal" data-target="#myModal">Open Modal</button>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h3 class="model-title">Error!</h3>
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

<!-- delete cancel request -->

<!-- Trigger the modal with a button -->
<button type="button" id="delete-request-buttons" class="btn btn-info btn-lg hide" data-toggle="modal" data-target="#deleteRequest">Open Modal</button>

<!-- Modal -->
<div id="deleteRequest" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="model-title">Cancel Request</h4>
      </div>
      <div class="modal-body">
        <div id="delete-request-result"></div>
        <p>Are you sure you want to delete this Request?</p>
      </div>
      <div class="modal-footer">
        	<button type="button" name="submit"  id="delete-request-submit" class="btn btn-info next2">Cancel Request</button><button data-dismiss="modal" type="button" class="btn btn-info back">Never Mind</button>
      </div>
    </div>

  </div>
</div>
<!-- end -->




<!-- change payment -->

<!-- Trigger the modal with a button -->
<button type="button" id="change-payment-button" class="btn btn-info btn-lg hide" data-toggle="modal" data-target="#change-payment">Open Modal</button>

<!-- Modal -->
<div id="change-payment" class="modal fade" role="dialog">
<form class="add-patient-form"   id="checkout" method="post">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header no-border">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="text-center model-title" style="text-align:center;">Add Payment Method</h4>
      </div>
      <div class="modal-body">
      
       
  <div id="payment-form"></div>
      	   
      </div>
      <div class="modal-footer">
        	<button type="submit" name="submit"  class="btn btn-info next">Save</button> 
      </div>
    </div>

  </div>
</form>
</div>
<!-- end -->




<!-- contact us -->

 
<!-- Modal -->
<div id="contact_us_modal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="text-center model-title">Contact Us</h4>
      </div>
      <div class="modal-body">
	<br><br>
        <div class="col-md-12 fee"><b>Call</b> <a class="pull-right  fee-cost">(214) 984-3900</a></div>
	<div class="col-md-12 fee"><b>Email</b> <a class="pull-right fee-cost" href="mailto:support@pediaq.co">support@PediaQ.co</a></div><br><br>
        <span class="request-red-text">For medical emergencies, Please dial 911</span>
      </div>
      <div class="modal-footer">
        	<button type="button" class="btn btn-default white-color" data-dismiss="modal">Close</button>
      </div>
    </div>
 
  </div>
</div>
<!-- end -->





<!-- Trigger the modal with a button -->
<button type="button" class="btn btn-info btn-lg hide" id="workinghourend-trigger" data-toggle="modal" data-target="#workinghourend">Open Modal</button>

<!-- working hour end Modal -->
<div id="workinghourend" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="model-title">Request Cancelled</h4>
      </div>
      <div class="modal-body">
        <p>We are sorry, PediaQ has closed for the day, and your visit request has been cancelled.</p><br><br>
	<i>For medical emergencies, please dial 911 </i>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="window.location.href='dashboard.php'">ok</button>
      </div>
    </div>

  </div>
</div>
<!-- end -->

<!-- Reviews Modal -->
<div id="reviews-modal" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="model-title">Reviews for <?php echo $name;?></h4>
        <br><span class="pointer"><img alt="reviews" height="28" src="images/stars.png"/><span class="span-text-modal"> <?php echo $average_rating." / ".$reviews;?> Reviews</span> </span>
      </div>
      <div class="modal-body">
     		<?php if(count($comments)){ foreach($comments as $comment){ ?>
		<div class="col-md-12">
			<div class="col-md-7"><p><?php if($comment->comment!=""){echo $comment->comment;}else{echo "No Comment Found";}?> <br> <?php echo $comment->author;?></p></div>		
			<div class="col-md-5 pull-right text-right"><img alt="reviews" src="images/stars.png" height="20"/><?php if($comment->rating!=0){ echo "<b>".($comment->rating/10)."</b>";}else{ echo "<b>0</b>"; } ?></div>	
		</div>
		<?php }}else{?>
		No Reviews Found
		<?php }?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<!-- end of review modal -->



<!-- Resume Modal -->

 
 

<!-- Bio Modal -->
<div id="resumetrigger" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="model-title">Complete Biography</h4>
      </div>
      <div class="modal-body">
         <p><?php echo $bio;?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<!-- end -->
 



<!-- Education Modal -->
<div id="educationtrigger" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="model-title">Education History</h4>
      </div>
      <div class="modal-body">
        <?php if(count($education)){$i=1; foreach($education as $educations){?>
		<p class="education"><?php echo $i.".  ".$educations->university;?>, <?php echo $educations->degree;?> <?php echo $educations->year;$i++;?> 			</p>
	<?php }}?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>

<!-- end -->


<!-- Trigger the modal with a button -->
<button type="button" class="btn btn-info btn-lg hide" id="admin-error-trigger" data-toggle="modal" data-target="#adminerror">Open Modal</button>

<!-- Admin cancelled Modal -->
<div id="adminerror" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="model-title">Request Cancelled</h4>
      </div>
      <div class="modal-body">
        <p>We are sorry, your request has been cancelled by admin.</p><br><br>
	<i>For medical emergencies, please dial 911 </i>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="window.location.href='cancel_request.php'">ok</button>
      </div>
    </div>

  </div>
</div>
<!-- end -->



<!-- Trigger the modal with a button -->
<button type="button" class="btn btn-info btn-lg hide" id="np-error-trigger" data-toggle="modal" data-target="#nperror">Open Modal</button>

<!-- Np error Modal -->
<div id="nperror" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="model-title">Request Cancelled</h4>
      </div>
      <div class="modal-body">
        <p>We are sorry, your request has been cancelled by Pediatric Specialist.</p><br><br>
	<i>For medical emergencies, please dial 911 </i>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" onclick="window.location.href='cancel_request.php'">ok</button>
      </div>
    </div>

  </div>
</div>
<!-- end -->
  
<script src="https://js.braintreegateway.com/js/braintree-2.22.2.min.js"></script>
<script>
// We generated a client token for you so you can test out this code
// immediately. In a production-ready integration, you will need to
// generate a client token on your server (see section below).
var clientToken = "<?php echo getPaymentToken();?>";

braintree.setup(clientToken, "dropin", {
  container: "payment-form"
});
</script>
<script src="js/accept_request.js"></script>
<script type="text/javascript" src="//maps.google.com/maps/api/js?sensor=false"></script>
<script type='text/javascript'>//<![CDATA[
	window.onload=function(){
	
	var locations = [
	    ['<?php echo $getaddress->street;?>',<?php echo $getaddress->latitude;?>, <?php echo $getaddress->longitude;?>, 1, "images/circle.png"],
	    ['Pediatric Specialist',<?php echo $getNurseLocation->location->latitude;?>, <?php echo $getNurseLocation->location->longitude;?>, 2, "images/loc-icon.png"]
	];
	var map = new google.maps.Map(document.getElementById('map'), {
	    zoom: 14,
	    center: new google.maps.LatLng(<?php echo $getaddress->latitude;?>, <?php echo $getaddress->longitude;?>),
	    mapTypeId: google.maps.MapTypeId.ROADMAP
	});

	var infowindow = new google.maps.InfoWindow();

	var marker, i;

	for (i = 0; i < locations.length; i++) {
	    marker = new google.maps.Marker({
		position: new google.maps.LatLng(locations[i][1], locations[i][2]),
		icon: locations[i][4],
		map: map
	    });

	    google.maps.event.addListener(marker, 'click', (function (marker, i) {
		return function () {
		    infowindow.setContent(locations[i][0]);
		    infowindow.open(map, marker);
		}
	    })(marker, i));
	}
	}//]]> 
</script>  
<?php getFooter();?>

