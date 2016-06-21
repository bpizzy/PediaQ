<?php  require "function.php";// load main function file ?>
<?php CheckLogin(); ?>	
<!-- header -->
<?php getHeader();?>
<!-- end header -->
<?php

	$getAddress   = getDefaultAddress();
	if(isset($getAddress->id))
	{
		$address   = $getAddress->street;
		$apisuite  = $getAddress->apt_suite;
		$mode 	   = "edit";	
		$addressId = $getAddress->id;
		$zip 	   = $getAddress->zip;
		$streetnumber = $getAddress->street_number;
	}
	else
	{
		$address   = "";
		$apisuite  = "";	
		$mode 	   = "add";
		$addressId = "0";
		$zip 	   = "";
		$streetnumber = "";
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
		<h3 class="form-head">Visit Location<span><b class="steps">Step 4 of 5</b></span></h3>
 		<div class="col-md-3"></div>
		<div class="col-md-6">
			<div id="result"></div>
			<form class="add-patient-form"  method="post">
			      <div class="form-group address_main">
				<label for="autocomplete">ADDRESS</label>
				<input type="text" value="<?php echo $address;?>" name="address" onfocus="geolocate()" placeholder="Enter your address" autocomplete="off" id="autocomplete" class="form-control">
			      </div>
			      <div class="form-group">
				  <label for="aptsuite">APT/SUITE#</label>
				  <input type="text"  value="<?php echo $apisuite;?>" name="aptsuite" placeholder="APT / SUITE#" autocomplete="off" id="aptsuite" class="form-control">
			      </div>
			      <div class="clear"> </div>
			      <div class="form-group">
			          <button type="button" name="submit" id="addLocation"  class="btn btn-info next">Next</button><button type="button" class="btn btn-info back" onclick="window.location.href='profile-step3.php'">Back</button>
			      </div>
			</form>
			<table id="address" class="hide">
			      <tr>
				<td class="label">Street address</td>
				<td class="slimField"><input class="field" id="street_number" value="<?php echo $streetnumber;?>"/></td>
				<td class="wideField" colspan="2"><input class="field" id="route"/></td>
			      </tr>
			      <tr>
				<td class="label">City</td>
				<td class="wideField" colspan="3"><input class="field" id="locality"/></td>
			      </tr>
			      <tr>
				<td class="label">State</td>
				<td class="slimField"><input class="field" id="administrative_area_level_1"/></td>
				<td class="label">Zip code</td>
				<td class="wideField"><input class="field" value="<?php echo $zip;?>" id="postal_code"/></td>
			      </tr>
			      <tr>
				<td class="label">Country</td>
				<td class="wideField" colspan="3"><input class="field" id="country"/></td>
			      </tr>
			    </table>
			    <div class="col-md-12"></div> 
		</div>
		<div class="col-md-3"></div>
	</div>
</div>
<script src="js/profile-step4.js"></script>
<script src="js/autocompletelocations.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDSQ2Um7BMl7Pb4t-SqnQPO-lE_snXUXi4&libraries=places&callback=initAutocomplete"
        async defer></script>
<?php getFooter();?>
