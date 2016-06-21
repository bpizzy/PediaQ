<?php  require "function.php";// load main function file ?>
<?php CheckLogin();DashboardRedirect(); ?>	
<!-- header -->
<?php getHeader();?>
<!-- end header -->
<?php 
	if(isset($_SESSION['wait_state']))
	{
		unset($_SESSION['wait_state']);
	}
	$user_token = $_SESSION['user_token'];
	$data = array("token"=>$user_token);
	$url = "api/user/family";
	$getfamily = json_decode(PostData($url,$data));// call getchild api
?>

<div class="container">
	<div class="row">
		<h3 class="form-head">Select Patient <span><i>Select one or more children for your visit Request.</i></span></h3>
 		<div id="page-result"></div>
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<form class="add-patient-form" onsubmit="return false;">
				<?php if($getfamily->success and count($getfamily->family->children))
				{?>
					<label>Children</label>
					<?php
					foreach($getfamily->family->children as $child){?>
					<div class="form-group child pointer" id="<?php echo $child->childId;?>">
						<?php echo $child->first_name." ".$child->last_name;?><a href="editchild.php?child_id=<?php echo base64_encode($child->childId);?>" class="pointer pull-right dasboard-edit">Edit</a>
					</div>
					<div class="clear"> </div>

					<?php }?> 
					<br><a class="pointer" data-toggle="modal" data-target="#myModal">+ Add Another Child</a>
            				<div class="clear"> </div>
   					<div class="form-group">
       						<button type="submit" name="submit" id="request_button" class="btn btn-info next">Next</button><button type="button" class="btn btn-info back" onclick="window.location.href='profile-step1.php';">Back</button>
      					</div>
				<?php }else{?>      
					<label for="mobile">Children</label>
					<div class="col-md-12 warning">No Child Found please add Child First.</div>
					<div class="clear"> </div>
					<br><a class="pointer" data-toggle="modal" data-target="#myModal">+ Add Another Child</a>
					<div class="form-group">
					<button type="button" name="submit" id="request_button"  class="btn btn-info next">Next</button><button type="button" class="btn btn-info back" onclick="window.location.href='profile-step1.php';">Back</button>
					</div>
				<?php }?>     
			</form>
			<!-- Modal for child adding -->
			  <div class="modal fade" id="myModal" role="dialog">
			    <div class="modal-dialog">
			     <!-- Modal content-->
			      <div class="modal-content">
				<div class="modal-header">
				  <button type="button" class="close" data-dismiss="modal">&times;</button>
				  <h3 class="model-title">Add Child</h3>
				<br>
				<div id="result"></div>
				</div>
				<div class="modal-body no-padding-top">
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
<!-- Trigger the modal with a button -->
<button type="button" style="display:none;" class="btn btn-info btn-lg error-modal" data-toggle="modal" data-target="#myModal1">Open Modal</button>
<!-- Modal -->
<div id="myModal1" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h3 class="model-title">Error</h3>
      </div>
      <div class="modal-body">
        <p>Please Select at least one Child to Proceed with request.</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>

  </div>
</div>
<script src="js/dashboard.js"></script>
<?php getFooter();?>

