<?php  require "function.php";// load main function file ?>
<?php CheckLogin();checkValidRequest(); ?>	
<!-- header -->
<?php getHeader();?>
<!-- end header -->
<?php 

	$user_token   = $_SESSION['user_token'];
	$data         = array("token"=>$user_token);
	$url          = "api/user/family";
	$getfamily    = json_decode(PostData($url,$data)); // call getchild api 
	$tariff       = array(8,7,5);
	$gettariff    = getTariff($tariff);
	$getAddress   = getDefaultAddress();
	$getpayment   = getPaymentCards();
	$paymentToken = getPaymentToken();
	

	/** submitting the create request **/ 
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
			$_SESSION['actual_payment_token'] = $post->paymentMethod->paymentToken;
			
			$service_available = true;
			if($service_available)
			{
				
				$request_params = array(
						"token" 	=> getUserToken(),
						"paymentToken"  => $_SESSION['actual_payment_token'],
						"addressId"     => $_SESSION['request_address'],
						"nurseId"	=> null,
						"childIds"	=> $_SESSION['request_child'],
 						
				);
				$url_create    = "api/request/create-children-request";
				$createrequest = json_decode(PostData($url_create,$request_params));
				if($createrequest->success==1)
				{
					$_SESSION['request_id'] = $createrequest->id;
					$_SESSION['wait_state'] = 1;
				}
				elseif(isset($_SESSION['request_id']))
				{
					$_SESSION['wait_state'] = 1;
				}
				else
				{
					echo "<div class='container'><div class='warning'>".$createrequest->errors->message."</div></div>";
				}
	
			}
			
		}
	}
	/** end **/ 
?>

<div class="container">
	<div class="row">
		<h3 class="form-head">Request Summary <span><i>Please review the information below and click "Confirm Request" when Ready.</i></span></h3>
 		<div id="confirm-result"></div>
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<form class="add-patient-form" method="post" id="confirm-result-form">
				<div style="display:none"><input id="alternateSubmit" type="submit" value="submit" /></div>
				<br>
				<!-- patients -->
				<label>Patient</label>
				<div class="patients">
					<?php if(isset($_SESSION['request_child']))
					{
					foreach($_SESSION['request_child'] as $childs){ $child= getChildInfo($childs);?>
					<div class="form-group child pointer" id="<?php echo $childs;?>">

					<?php  echo $child[0]->first_name." ".$child[0]->last_name;?><a  class="delete-childs pull-right pointer" child_id=
					"<?php echo  $childs;?>"  name="<?php echo $child[0]->first_name." ".$child[0]->last_name;?>">X</a>
					</div>
					<div class="clear"> </div>

					<?php }?> 
					</div>
					<br><a class="pointer addpatient" data-toggle="modal" data-target="#addpatient">+ Add Another Patient</a>
						    <div class="clear"> </div>
					  
					<?php }else{?>      
					<b>Children</b>
					<div class="col-md-12 warning">No Child Found please add Child First.</div>
					<div class="clear"> </div>
					<br><a class="pointer" data-toggle="modal" data-target="#myModal">+ Add Child</a>
					<?php }?>    
				<!-- end patients-->
				<!-- Visit Location -->
				<br><br>
				<label>Visit Location</label>
				<div class="visits">
				<?php 
				if($getAddress)
				{
				$address = $getAddress;
				?>
				 
				<div class="form-group address pointer" id="<?php echo $address->id;?>">
				<?php  echo $address->street;?><a  class="delete-address pull-right pointer" address_id="<?php echo $address->id;?>"  name="<?php  echo $address->street;?>">X</a>
				</div>
				<div class="clear"> </div>

				</div>
				<a class="pointer addlocationform hide">+ Add Location</a>
				 <div class="clear"> </div>
				  
				<?php }else{?>      
				<div class="col-md-12 warning">No Address Found please add Default Address First.</div></div>
				<div class="clear"> </div>
				<br><a class="pointer" data-toggle="modal" data-dismiss="modal" data-target="#addlocation">+ Add Location</a>
				<?php }?>    
				<!-- end Locations -->
				<!-- Payments -->
				<br><br>
				<label>Payment Method</label><br><br>
				<div class="payments">
				  <div id="payment-form"></div> 
				</div>  
				<!-- end payments -->
				<!-- Promo codes -->
				<br>
				<label>Promo Code</label>
					<div class="promo-code">
					<?php if(isset($_SESSION['promo_code'])){?>
					 	<div class="form-group applied-promo-code pointer" id="coupon-applied">
				 			<?php echo $_SESSION['promo_code'];?><a class="delete-promo-code pull-right"  name="<?php echo $_SESSION['promo_code'];?>">X</a>
					</div>
					<?php }else{?>
					<br><input type="text"  id="add-promo" placeholder="Enter Promo Code" autocomplete="off" class="form-control input-height pointer">
					<?php }?> 
					</div>  
				<!-- end promo-code -->
				<!-- Insurance -->
				<br><br>
				<label>Insurance</label>
					<div class="insurance">
					<?php if(isset($_SESSION['request_provider_name'])){?>
					 	<div class="apply-insurance"><div class="form-group applied-insurance pointer" id="insurance-applied"><?php echo $_SESSION['request_provider_name'];?> <a class="delete-insurance pull-right"  data-toggle="modal" data-target="#deleteinsurance">X</a></div></div>
					<?php }else{?>
					<br><input type="text"  id="open-add-insurance" placeholder="Add Insurance" autocomplete="off" class="form-control input-height pointer"> 
					<?php }?> 
					</div>  
	
				<!-- end Insurance -->
				<div class="form-group">
					<div class="col-md-6 pull-left request-a">
						<a class="pointer" id="cancel_request_button">Cancel</a>&nbsp;|&nbsp;<a class="pointer" id="back_request">Back</a>&nbsp;|&nbsp;<a id="estimate_request" data-toggle="modal" data-target="#estimatemodal" class="pointer">Estimate</a>
					</div> 
					<div class="col-md-6 pull-right">
					<button type="button" name="submit" class="btn btn-info next" id="comfirm-request">Confirm Request</button>
					</div>
				</div>
			</form>

		<!-- Trigger the modal with a button -->
		<button type="button" class="hide btn btn-info btn-lg" id="btn1" data-toggle="modal" data-target="#myModal1">Open Modal</button>

		<!-- Modal -->
		<div id="myModal1" class="modal fade estimate-fee-modal" role="dialog">
		  <div class="modal-dialog">

		    <!-- Modal content-->
		    <div class="modal-content">
		      <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
		       <h3 class="model-title">Fee Estimate</h3>
		      </div>
		      <div class="modal-body">
			<p> <i> No charge for phone consultation.</i></p> 
			<p> <span class="request-fee-span">The following fees are applied when PediaQ Pediatric<br> Specialist Visits the Patient.</span></p> 
	
			<?php if(count($gettariff)){   


			    foreach($gettariff as $arr){

			      array_unshift($gettariff, $arr); 
			      array_pop($gettariff);
			    }
				foreach($gettariff as $tariff){?>
				<div class="col-md-12 fee"><b><?php echo $tariff->field_name;?></b><a href="http://www.pediaq.care/pricing/"  target="_blank" data-toggle="tooltip" title="<?php if($tariff->service_description!=''){echo $tariff->service_description;}else{echo $tariff->field_name;}?>" data-placement="top"><span class="glyphicon glyphicon-question-sign span-q"></span></a>
		<a class="pull-right  fee-cost">$<?php echo ($tariff->price+$tarif->insurance)/100;?></a>
		</div>
			<?php }}?>

			<br>
			<span class="light-font">Estimate does not include additional services, discounts, or promotions.</span><br><br>
			<b class="request-red-text">For medical emergencies, Please dial 911.</b>
		      </div>
		      <div class="modal-footer">
			 

			<button type="button" name="submit" data-dismiss="modal" class="btn btn-info next">Yes, I understand</button>
			<button type="button" id="cancel-request" class="btn btn-info back">Cancel Request</button>
		      </div>
		    </div>

		  </div>
		</div>
		  <!-- Modal for child adding -->
		  <div class="modal fade" id="myModal" role="dialog">
		    <div class="modal-dialog">
		    
		      <!-- Modal content-->
		      <div class="modal-content">
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
			  <h3 class="model-title">Add Child</h3>
			</div>
			<div class="modal-body no-padding-top">
		  <div id="result"></div>

			   <form class="add-patient-form add-child-form">

		<div class="form-group">
			<label for="fullname">Full name</label>
			<input type="email" placeholder="Enter your child’s first &amp; last name" id="fullname" class="form-control">
		      </div>
		      
		      
		      <div class="form-group dob-box">
			<label for="month">Birth Date</label>
			<div class="clear"></div>
	

		<div class="db-styled-select styled-select  blue semi-square">
		  <select id="month">
		    <option value="">MM</option>
		    <?php for($i=1;$i<=12;$i++){?>
			 <option value="<?php echo $i;?>"><?php echo $i;?></option>
		    <?php }?>
		  </select>
		</div><span class="devider">/</span>
		<div class="db-styled-select styled-select  blue semi-square">
		  <select id="days">
		    <option value="">DD</option>
		     <?php for($i=1;$i<=31;$i++){?>
			 <option value="<?php echo $i;?>"><?php echo $i;?></option>
		    <?php }?>
		  </select>
		</div><span class="devider">/</span>
		<div class="db-styled-select styled-select  blue semi-square">
		  <select id="year">
		    <option value="">YYYY</option>
		     <?php for($i=1998;$i<=2016;$i++){?>
			 <option value="<?php echo $i;?>"><?php echo $i;?></option>
		    <?php }?>
		  </select>
		</div>
		 <div class="clear"> </div>
		      
	
		      </div>
		      
		      <div class="clear"> </div>
		      
		      <div class="form-group">
		      	 <label for="fullname">Gender</label>
				<div class="clear"> </div>
	
		<div class="styled-select blue semi-square">
		  <select id="gender">
		    <option value="">Gender</option>
		    <option value="male">Male</option>
		    <option value="female">Female</option>
		  </select>
		</div>
			</div>

		 

		</form>
			</div>
			<div class="modal-footer">
		   	<div class="form-group">
		<button type="submit" name="submit"  class="btn btn-info next addchild">Add Child</button><button data-dismiss="modal" type="button" class="btn btn-info back">Back</button>
		</div>

	
			<div class="clear"> </div>

		      	</div>
		      </div>
		      
		    </div>
		   
		  
		</div>

		</div>

		<div class="col-md-3"></div>
		</div>
		</div>



		<!-- Add Patient modal -->
		<div id="addpatient" class="modal fade" role="dialog">
		  <div class="modal-dialog">

		    <!-- Modal content-->
		    <div class="modal-content">

		      <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			    <h3 class="model-title">Select Patient</h3>
		      </div>
		      <div class="modal-body">
			<label>CHILD</label>
			<div class="add-patient-body"></div>
			<a class="pointer" data-toggle="modal"  data-dismiss="modal" data-target="#myModal">+ Add Another Patient</a>
		      </div>
		      <div class="modal-footer">
			<button type="button" name="submit"  class="btn btn-info next1 addchildtorequest">Add to Request</button><button   type="button" class="btn btn-info back">Back</button>
		      </div>
		    </div>

		  </div>
		</div>
		<!-- end of add patient Modal -->


		<!-- Trigger the modal with a button -->
		<button type="button" style="display:none;" class="btn btn-info btn-lg error-modal1" data-toggle="modal" data-target="#myModal11">Open Modal</button>

		<!-- Modal -->
		<div id="myModal11" class="modal fade" role="dialog">
		  <div class="modal-dialog">

		    <!-- Modal content-->
		    <div class="modal-content">
		      <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" data-target="#addpatient">&times;</button>
			<h3 class="model-title">Error</h3>
		      </div>
		      <div class="modal-body">
			<p>Please Select atleast one Child to Proceed with request.</p>
		      </div>
		      <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal" data-target="#addpatient">Close</button>
		      </div>
		    </div>

		  </div>
		</div>


		<!-- delete child modal -->
		<button type="button" class="btn btn-info btn-lg hide" id="delete-child-button" data-toggle="modal" data-target="#deletechild">Open Modal</button>
		<!-- Modal -->
		<div id="deletechild" class="modal fade" role="dialog">
		  <div class="modal-dialog">

		    <!-- Modal content-->
		    <div class="modal-content">
		      <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h3 class="model-title">Delete Child</h3>
		      </div>
		      <div class="modal-body">
		<div class="delete-child-loading"></div>
			<p>Are you sure you want to delete <span id="delete-child-name"></span> ?</p>
		      </div>
		      <div class="modal-footer">
		      <button type="button" name="submit"  id="delete-child-submit" class="btn btn-info next2">Delete</button><button data-dismiss="modal" type="button" class="btn btn-info back">Cancel</button>
		      </div>
		    </div>

		  </div>
		</div>

		<!-- end -->



		<!-- Edit Child Modal -->
		<button type="button" id="editchildmodalbutton" class="btn btn-info btn-lg hide" data-toggle="modal" data-target="#editchildmodal">Open Modal</button>
		<div id="editchildmodal" class="modal fade" role="dialog">
		  <div class="modal-dialog">

		    <!-- Modal content-->
		    <div class="modal-content">
		      <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			 <h3 class="model-title">Edit Child</h3>
		      </div>
		      <div class="modal-body no-padding-top">
		<div id="edit-result"></div>
			   <div class="edit-patient-body"></div>
	
		      </div>
		      <div class="modal-footer">
			    <button type="button" name="submit" id="savebuttoneditchild" class="btn btn-info next1">Save</button><button data-dismiss="modal" type="button" class="btn btn-info back" onclick='$(".addpatient").click();'>Back</button>
	
		      </div>


		    </div>

		  </div>
		</div>
		<!-- end modal -->


		<!-- select location -->
		<!-- Trigger the modal with a button -->
		<button type="button" id="openlocationform" class="btn btn-info btn-lg hide" data-toggle="modal" data-target="#addlocationform">Open Modal</button>

		<!-- Modal -->
		<div id="addlocationform" class="modal fade" role="dialog" data-keyboard="false" data-backdrop="static">
		  <div class="modal-dialog">

		    <!-- Modal content-->
		    <div class="modal-content">
		      <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h3 class="model-title">Select Visit Location</h3>
		      </div>
		      <div class="modal-body">
			 <label>Address</label>
			  <div class="select-address-body"></div>
				<a class="pointer" data-toggle="modal" id="addaddress_request_modal"  data-dismiss="modal" data-target="#addlocation">+ ADD ANOTHER ADDRESS</a>
		      </div>
		 <div class="modal-footer">
			   
		      </div>

		      </div>

		  </div>
		</div>
		<!-- end -->


		<!-- add location -->
		<!-- Modal -->
		<div id="addlocation" class="modal fade" role="dialog"  data-keyboard="false" data-backdrop="static" >
		  <div class="modal-dialog">

		    <!-- Modal content-->
		    <div class="modal-content">
		      <div class="modal-header">
		       
			<h3 class="model-title">Add Address</h3>
		      </div>
		      <div class="modal-body">
		  <div  id="add-address-result"></div>
		   <form class="add-patient-form add-child-form" method="post">
		<div class="form-group" id="locationField">
			<label for="autocomplete">ADDRESS</label>
			<input id="autocomplete" placeholder="Enter your address" onFocus="geolocate()" type="text" class="form-control">
		      </div>
			  <div class="form-group">
			<label for="aptsuite">APT/SUITE#</label>
			<input type="text" name="aptsuite" placeholder="Enter APT/SUITE#" autocomplete="off" id="aptsuite" class="form-control">
		      </div>

		 
		 <div class="clear"> </div>
		  	
		</form>
		    <table id="address" class="hide">
		      <tr>
			<td class="label">Street address</td>
			<td class="slimField"><input class="field" id="street_number"/></td>
			<td class="wideField" colspan="2"><input class="field" id="route"/></td>
		      </tr>
		      <tr>
			<td class="label">City</td>
			<td class="wideField" colspan="3"><input class="field" id="locality"/></td>
		      </tr>
		      <tr>
			<td class="label">State</td>
			<td class="slimField"><input class="field" id="administrative_area_level_1" /></td>
			<td class="label">Zip code</td>
			<td class="wideField"><input class="field" id="postal_code" /></td>
		      </tr>
		      <tr>
			<td class="label">Country</td>
			<td class="wideField" colspan="3"><input class="field" id="country" /></td>
		      </tr>
		    </table>
		 </div>
		 <div class="modal-footer">
			    <button type="button" name="submit"  id="addaddress-submit" class="btn btn-info next1">Save</button><button data-dismiss="modal" id="list-address" type="button" class="btn btn-info back">Back</button>
	
		      </div>

		      </div>

		  </div>
		</div>
		<!-- end -->




		<!-- delete address modal -->
		<button type="button" class="btn btn-info btn-lg hide" id="delete-address-button" data-toggle="modal" data-target="#deleteaddress">Open Modal</button>
		<!-- Modal -->
		<div id="deleteaddress" class="modal fade" role="dialog">
		  <div class="modal-dialog">

		    <!-- Modal content-->
		    <div class="modal-content">
		      <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h3 class="model-title">Delete Visit Location</h3>
		      </div>
		      <div class="modal-body">
		<div id="deleteaddress-result"></div>
			<p>Are you sure you want to delete this address?</p>
		      </div>
		      <div class="modal-footer">
		      <button type="button" name="submit"  id="delete-address-submit" class="btn btn-info next2">Delete</button><button data-dismiss="modal" type="button" class="btn btn-info back">Cancel</button>
		      </div>
		    </div>

		  </div>
		</div>

		<!-- end -->



		<!-- modal for edit address -->
		<!-- Trigger the edit address modal with a button -->
		<button type="button" class="btn btn-info btn-lg hide" id="editLocationform-button" data-toggle="modal" data-target="#editLocationform">Open Modal</button>

		<!-- Modal -->
		<div id="editLocationform" class="modal fade" role="dialog">
		  <div class="modal-dialog">

		<!-- Modal content-->
		<div class="modal-content">
		      <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h3 class="model-title">Edit Address</h3>
		      </div>
		      <div class="modal-body edit-address-form">
		      <div id="edit-address-result"></div>
		      <form class="add-patient-form add-child-form" method="post">
				<div class="form-group" id="locationField1">
					<label for="autocomplete3">ADDRESS</label>
					<input id="autocomplete3" placeholder="Enter your address" onfocus="geolocate()" type="text" class="form-control" autocomplete="off">
			       </div>
			       <div class="form-group">
				<label for="aptsuite">APT/SUITE#</label>
				<input type="hidden" id="edit_address_id" />
				<input type="text" name="aptsuite" placeholder="Enter APT/SUITE#" autocomplete="off"   class="form-control aptsuite">
			      </div>
			<div class="clear"> </div>
			</form>
			<table id="edit_address" class="hide">
			      <tr>
				<td class="label">Street address</td>
				<td class="slimField"><input class="field street_number"/></td>
				<td class="wideField" colspan="2"><input class="field route"/></td>
			      </tr>
			      <tr>
				<td class="label">City</td>
				<td class="wideField" colspan="3"><input class="field locality"/></td>
			      </tr>
			      <tr>
				<td class="label">State</td>
				<td class="slimField"><input class="field administrative_area_level_1"/></td>
				<td class="label">Zip code</td>
				<td class="wideField"><input class="field postal_code"/></td>
			      </tr>
			      <tr>
				<td class="label">Country</td>
				<td class="wideField" colspan="3"><input class="field country"/></td>
			      </tr>
			</table>
		</div>
		<div class="modal-footer">
			   <button type="button" name="submit" id="update-location-submit" class="btn btn-info next1">Save</button>
			   <button data-dismiss="modal" type="button" class="btn btn-info back" onclick='$(".addlocationform").click();'>Cancel</button>
		      </div>
		</div>

		  </div>
		</div>
		<!-- end -->


		<!-- add payment modal -->
		<!-- Trigger the modal with a button -->
		<button type="button" class="btn btn-info btn-lg hide" id="addPaymentform-trigger" data-toggle="modal" data-target="#addPaymentform">Open Modal</button>

		<!-- Modal -->
		<div id="addPaymentform" class="modal fade" role="dialog">
		  <div class="modal-dialog">

		    <!-- Modal content-->
		    <div class="modal-content">
		      <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h3 class="model-title">Add Payment</h3>
		      </div>
		      <div class="modal-body">
			<form class="add-patient-form" method="post">
			<div class="form-group">
				<label for="cardnumber">CARD NUMBER</label>
				<input type="text" name="cardnumber" placeholder="Enter your credit card number" autocomplete="off" id="cardnumber" class="form-control">
			      </div>

				<div class="form-group dob-box">
					<label for="payment_month">EXP DATE</label>
					<div class="clear"></div>
					<div class="db-styled-select styled-select  blue semi-square">
						<select id="payment_month" name="month">
							<option value="">MM</option>
							<?php for($i=1;$i<=12;$i++){?>				
							<option value="<?php echo $i;?>"><?php echo $i;?></option>
							<?php }?>				
						</select>
					</div>
					<span class="devider">/</span>
					<div class="db-styled-select styled-select  blue semi-square">
					  <select id="payment_year" name="year">
						<option value="">YYYY</option>
						 	<?php for($i=2016;$i<=2025;$i++){?>				
							<option value="<?php echo $i;?>"><?php echo $i;?></option>
							<?php }?>
					  </select>
					</div>
					<div class="clear"> </div>
				</div>
			 
			 <div class="clear"> </div>
			      
			      <div class="form-group">
			      <p class="charged">This payment method will only be charged when PediaQ Pediatric specialist visits the patient.<b><i><b class="black-color"> No charged for phone consulatation</b></i></b></p>
				  </div>
			</form>
		     </div>
		      <div class="modal-footer">
			 <button type="submit" name="submit" class="btn btn-info next">Next</button><button type="button" class="btn btn-info back">Back</button>
		      </div>
		    </div>

		  </div>
		</div>


		<!-- end -->



		<!-- add promo code -->
		<!-- Trigger the modal with a button -->
		<button type="button" class="btn btn-info btn-lg hide" id="add-promocode" data-toggle="modal" data-target="#add-promocode-form">Open Modal</button>

		<!-- Modal -->
		<div id="add-promocode-form" class="modal fade" role="dialog">
		  <div class="modal-dialog">

		    <!-- Modal content-->
		    <div class="modal-content">
		      <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h3 class="model-title">Add Promo Code</h3>
		      </div>
		      <div class="modal-body">
			
			<div id="add-promo-result"></div>
			<form class="add-patient-form add-child-form" method="post">
			<div class="form-group">

				<label for="promocode">Promo Code</label><br>
				<input type="text" placeholder="Enter Promo Code" autocomplete="off" id="promocode" class="form-control">
			</div>
			<div class="clear"> </div>
			  	
			</form>
			</div>
		      <div class="modal-footer">
		       <button type="button" name="submit" id="add-promo-submit" class="btn btn-info next">Add</button><button type="button" class="btn btn-info back" data-dismiss="modal">Back</button>
		      </div>
		    </div>

		  </div>
		</div>
		<!-- end promo code -->



		<!-- edit promo code -->
		<!-- Trigger the modal with a button -->
		<button type="button" class="btn btn-info btn-lg hide" id="edit-promocode-trigger" data-toggle="modal" data-target="#edit-promocode-form">Open Modal</button>

		<!-- Modal -->
		<div id="edit-promocode-form" class="modal fade" role="dialog">
		  <div class="modal-dialog">

		    <!-- Modal content-->
		    <div class="modal-content">
		      <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h3 class="model-title">Edit Promo Code</h3>
		      </div>
		      <div class="modal-body">
			
			<div id="edit-promo-result"></div>
			<form class="add-patient-form add-child-form" method="post">
			<div class="form-group">

				<label for="edit_promocode_input">Promo Code</label><br>
				<input type="text" value="<?php if(isset($_SESSION['promo_code'])){ echo $_SESSION['promo_code']; }?>" placeholder="Enter Promo Code" autocomplete="off" id="edit_promocode_input" class="form-control">
				<br><br>
				<a class="red-text pointer" id="delete_promocode_edit">DELETE THIS PROMO CODE</a>
			</div>
			<div class="clear"> </div>
		
			  	
			</form>
			</div>
		      <div class="modal-footer">
		       <button type="button" name="submit" id="edit-promo-submit" class="btn btn-info next">Save</button><button type="button" class="btn btn-info back" data-dismiss="modal">Cancel</button>
		      </div>
		    </div>

		  </div>
		</div>
		<!-- end edit promo code -->



		<!-- Add Insurance -->
		<!-- Trigger the modal with a button -->
		<button type="button" class="btn btn-info btn-lg hide" id="add-insurance-forms" data-toggle="modal" data-target="#add-insurance-form">Open Modal</button>

		<!-- Modal -->
		<div id="add-insurance-form" class="modal fade" role="dialog">
		  <div class="modal-dialog">

		    <!-- Modal content-->
		    <div class="modal-content">
		      <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h3 class="model-title">Add Insurance</h3>
		      </div>
		      <div class="modal-body no-padding-top">
			
			<div id="add-insurance-result"></div>


			<form class="add-patient-form add-child-form" method="post">
		<div class="form-group">
				<label for="provider-name">PROVIDER</label>

		<div class="ui-widget">
		  
		  <input type="text" id="provider-name" name="provider"  placeholder="Enter Provider"   class="form-control" insurance_id="0">
		</div>
			 
		
			</div>
		<div class="form-group">
			<label for="memberid">MEMBER ID</label>
			<input type="text" id="memberid" placeholder="Enter your member ID" autocomplete="off"  class="form-control">
		      </div>
			  <div class="form-group">
			<label for="group">GROUP #</label>
			<input type="text" id="group" placeholder="Enter your group #" autocomplete="off"  class="form-control">
		      </div> 
			  <div class="form-group">
			<label for="primary_policy">PRIMARY POLICY HOLDER</label>
			<input type="text" id="primary_policy" placeholder="Enter primary policy holder name" autocomplete="off" class="form-control">
		      </div>

		 
		 <div class="clear"> </div>
		  <div class="form-group">
		   <label for="insurance-terms"></label>
		  <input type="checkbox" id="insurance-terms">
		      <span class="charged">I understand that health insurance is intended to cover some, but not all of the cost of my child's healthcare.Most plans require co-payments, deductibles and/or co-insurance expenses, which much be paid by the patient. &nbsp;&nbsp;<a href="http://www.pediaq.care/insurance/" target="_blank" class="insurance-read-more">Learn more</a></span>
			  </div>
		 <div class="clear"> </div>
		  
		</form>
			</div>
		      <div class="modal-footer">
		       <button type="button" name="submit" id="add-insurance-submit" class="btn btn-info next">Add</button><button type="button" class="btn btn-info back" data-dismiss="modal">Back</button>
		      </div>
		    </div>

		  </div>
		</div>
		<!-- end promo code -->


		<!-- delete insurance modal -->
		<!-- Trigger the modal with a button -->
		<button type="button" class="btn btn-info btn-lg hide" data-toggle="modal" data-target="#deleteinsurance">Open Modal</button>

		<!-- Modal -->
		<div id="deleteinsurance" class="modal fade" role="dialog">
		  <div class="modal-dialog">

		    <!-- Modal content-->
		    <div class="modal-content">
		      <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h3 class="model-title">Delete Insurance</h3>
		      </div>
		      <div class="modal-body">
			<div id="delete-insurance-result"></div>
			<p>Are you Sure you want to delete this insurance?</p>
		      </div>
		      <div class="modal-footer">
			 

		<div class="form-group">
			<div class="col-md-7 pull-left request-a">
				<a class="pointer" data-dismiss="modal">Cancel</a>&nbsp;|&nbsp;<a id="edit_insurance" class="pointer">Edit</a>
			</div> 
			<div class="col-md-5 pull-right">
			<button type="button" name="submit" id="delete_insurance" class="btn btn-info next2">Delete</button>
			</div>
		</div>

		      </div>
		    </div>

		  </div>
		</div>

		<!-- end -->


		<!-- edit insurance modal -->
		<!-- Trigger the modal with a button -->
		<button type="button" class="btn btn-info btn-lg hide" id="trigger_editinsurance" data-toggle="modal" data-target="#editInsurance">Open Modal</button>

		<!-- Modal -->
		<div id="editInsurance" class="modal fade" role="dialog">
		  <div class="modal-dialog">

		    <!-- Modal content-->
		    <div class="modal-content">
		      <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="model-title">Edit Insurance</h4>
		      </div>
		      <div class="modal-body">

		<div id ="edit_insurance_result"></div>
			  <form class="add-patient-form add-child-form1" method="post">
		<div class="form-group">
				<label for="edit-provider-name">PROVIDER</label>

		<div class="ui-widget">
		  
		  <input type="text" id="edit-provider-name" name="provider"  placeholder="Enter Provider"   class="form-control" insurance_id="0">
		</div>
			 
		
			</div>
		<div class="form-group">
			<label for="memberid_1">MEMBER ID</label>
			<input type="text" id="memberid_1" placeholder="Enter your member ID" autocomplete="off"   class="form-control">
		      </div>
			  <div class="form-group">
			<label for="group_1">GROUP #</label>
			<input type="text" id="group_1" placeholder="Enter your group #" autocomplete="off"   class="form-control">
		      </div> 
			  <div class="form-group">
			<label for="primary_policy_1">PRIMARY POLICY HOLDER</label>
			<input type="text" id="primary_policy_1" placeholder="Enter primary policy holder name" autocomplete="off"   class="form-control">
		      </div>

		 
		 <div class="clear"> </div>
		  <div class="form-group"><br>
		   <label for="insurance-terms_1"></label>
		  <input type="checkbox" id="insurance-terms_1">
		      <span class="charged1">I understand that health insurance is intended to cover some, but not all of the cost of my child's healthcare.Most plans require co-payments, deductibles and/or co-insurance expenses, which much be paid by the patient. &nbsp;&nbsp;<a href="http://www.pediaq.care/insurance/" target="_blank" class="insurance-read-more">Learn more</a></span>
			  </div>
		 <div class="clear"> </div>
		  
		<a id="delete_insurance_edit" class="pointer">Delete Insurance</a>


		</form>
		      </div>
		      <div class="modal-footer">
			   <button type="button" name="submit" id="save-edit-insurance" class="btn btn-info next1">Save</button><button data-dismiss="modal" type="button" class="btn btn-info back">Cancel</button>
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




		<!-- modal for working Hours -->

		<!-- Trigger the modal with a button -->
		<button type="button" id="working_hours_buton" class="btn btn-info btn-lg hide" data-toggle="modal" data-target="#working_hours">Open Modal</button>

		<!-- Modal -->
		<div id="working_hours" class="modal fade" role="dialog">
		  <div class="modal-dialog">

		    <!-- Modal content-->
		    <div class="modal-content">
		      <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="model-title">Hours of Operation</h4>
		      </div>
		      <div class="modal-body">
			    <div id="working-operation">
			    </div>
			    <span class="red-text">For medical emergencies, Please dial 911</span>	
		      </div>
		      <div class="modal-footer">
			 <button type="button" name="submit"  id="notify-submit" class="btn btn-info next">Notify me when open</button><button data-dismiss="modal" type="button" class="btn btn-info back" id="close-working">Close</button>
		      </div>
		    </div>

		  </div>
		</div>


		<!-- end -->


		<!-- start notify message -->
		<!-- Trigger the modal with a button -->
		<button type="button" class="btn btn-info btn-lg hide" id="notifymessage-button" data-toggle="modal" data-target="#notifymessage">Open Modal</button>

		<!-- Modal -->
		<div id="notifymessage" class="modal fade" role="dialog">
		  <div class="modal-dialog">

		    <!-- Modal content-->
		    <div class="modal-content">
		      <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="model-title" id="notify-head">Modal Header</h4>
		      </div>
		      <div class="modal-body" id="notify-body">
		      
		      </div>
		      <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		      </div>
		    </div>

		  </div>
		</div>
		<!-- end -->


		<!-- service unavailable -->
		<!-- Trigger the modal with a button -->
		<button type="button" class="btn btn-info btn-lg hide" id="service-unavailable-button" data-toggle="modal" data-target="#service-unavailable">Open Modal</button>

		<!-- Modal -->
		<div id="service-unavailable" class="modal fade" role="dialog">
		  <div class="modal-dialog">

		    <!-- Modal content-->
		    <div class="modal-content">
		      <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="model-title">We are Sorry</h4>
		      </div>
		      <div class="modal-body">
			<p>All of our Pediatric Specialists are currently busy helping other Patients.</p><br>
			<p>Please Try Again Shortly.</p>
		      </div>
		      <div class="modal-footer">
			<button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
		      </div>
		    </div>

		  </div>
		</div>

		<!-- end -->



		<!-- wait screen -->
		<!-- Trigger the modal with a button -->
		<button type="button" class="btn btn-info btn-lg hide" id="waiting-screen-submit" data-toggle="modal" data-target="#waiting-screen">Open Modal</button>

		<!-- Modal -->
		<div id="waiting-screen" class="modal fade" role="dialog">
		  <div class="modal-dialog">

		    <!-- Modal content-->
		    <div class="modal-content">
		      <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="text-center model-title" style="text-align:center;">Requesting</h4>
		      </div>
		      <div class="modal-body">
			<p class="text-center">We are looking for a Pediatric Specialists available in your area. This may take a few minutes.</p>
			<div class="text-center" id="wait-loading"><span class="loading3"><img alt="loading" src='images/loading.gif'></span></div>
			<p class="text-center pointer" id="wait-loading-cancel">Cancel Request</p>

		      </div>
		      <div class="modal-footer">
		       
		      </div>
		    </div>

		  </div>
		</div>
		<!-- end -->


		<!-- estimate modal -->

		<!-- Modal -->
		<div id="estimatemodal" class="modal fade" role="dialog">
		  <div class="modal-dialog">

		    <!-- Modal content-->
		    <div class="modal-content">
		      <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
		       <h3 class="model-title">Fee Estimate</h3>
		      </div>
		      <div class="modal-body">
			<p> <i>No Charge for Phone Consulatation.</i></p> 
			<p> <span class="request-fee-span">The following fees are applied when PediaQ Pediatric<br> Specialist Visits the Patient.</span></p> 
	
			<?php if(count($gettariff)){ 
				foreach($gettariff as $arr){

				array_unshift($gettariff, $arr); 
				array_pop($gettariff);
				}
				foreach($gettariff as $tariff){?>
				<div class="col-md-12 fee"><b><?php echo $tariff->field_name;?></b><a href="http://www.pediaq.care/pricing/" target="_blank"  data-toggle="tooltip" title="<?php if($tariff->service_description!=''){echo $tariff->service_description;}else{echo $tariff->field_name;}?>" data-placement="top"><span class="glyphicon glyphicon-question-sign span-q"></span></a>
		<a class="pull-right fee-cost">$<?php echo ($tariff->price+$tarif->insurance)/100;?></a>
		</div>
			<?php }}?>

			<br>
			<span class="light-font">Estimate does not include additional services, discounts, or promotions.</span><br><br>
			<b class="request-red-text">For medical emergencies, Please dial 911.</b>
		      </div>
		      <div class="modal-footer">
			 

			<button type="button" name="submit" data-dismiss="modal" class="btn btn-info next">Close</button>
			 
		      </div>
		    </div>

		  </div>
		</div>
		<!-- end estimate modal -->



		<!-- delete promo  code -->
		<!-- Trigger the modal with a button -->
		<button type="button" class="btn btn-info btn-lg hide" id="deletepromocode_trigger" data-toggle="modal" data-target="#deletepromo">Open Modal</button>

		<!-- Modal -->
		<div id="deletepromo" class="modal fade" role="dialog">
		  <div class="modal-dialog">

		    <!-- Modal content-->
		    <div class="modal-content">
		      <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal">&times;</button>
			<h4 class="model-title">Delete Promo Code</h4>
		      </div>
		      <div class="modal-body">
			<p>Are you sure you want to delete this promo code?</p>
			<div id="delete-promocode-result"></div>
		      </div>
		      <div class="modal-footer">
		       		
				<div class="form-group">
					<div class="col-md-7 pull-left request-a">
						<a class="pointer" data-dismiss="modal">Cancel</a>&nbsp;|&nbsp;<a id="edit_promocode" class="pointer">Edit</a>
					</div> 
					<div class="col-md-5 pull-right">
					<button type="button" name="submit" id="delete_promocode" class="btn btn-info next2">Delete</button>
					</div>
				</div>
		      </div>
		    </div>

		  </div>
		</div>

		<!-- end edit promo -->



<script src="https://js.braintreegateway.com/js/braintree-2.22.2.min.js"></script>
<script>
// We generated a client token for you so you can test out this code
// immediately. In a production-ready integration, you will need to
// generate a client token on your server (see section below).
var clientToken = "<?php echo getPaymentToken();?>";

braintree.setup(clientToken, "dropin", {
  container: "payment-form"
});

$("document").ready(function(){

/** trigger first modal **/
<?php if(!isset($_SESSION['wait_state'])){?>
$("#btn1").click();
<?php }else{?>

$("#waiting-screen-submit").click();

$.ajax({
		url: 'function.php',
		type: 'post',
		dataType:'text',
		data: {function_name:'CheckStatusofRequest',status:'1'},
		success: function(text) {
		
		setTimeout(function close(){
			if(text==="0")
			{
				$(".close").click();
				$("#service-unavailable-button").click();
			}
			if(text==="1")
			{
				window.location.href='waiting.php';
			}
			if(text==="2")
			{
				window.location.href='accept_request.php';
			}	
		}, 2000);
			
		},
			
});
<?php }?>
/** end **/


});
</script>
 

<script src="js/libs-1.11.3.js" type="text/javascript"></script> 
<script src="js/bootstrap.js" type="text/javascript"></script>
<script src="js/request_summary.js"></script>
<script src="js/autocompletelocations.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDSQ2Um7BMl7Pb4t-SqnQPO-lE_snXUXi4&libraries=places&callback=initAutocomplete"
        async defer></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
</body>
<?php unsetGlobalWarning();?>
</html>
