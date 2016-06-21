<?php  require "function.php";// load main function file ?>
<?php CheckLogin(); ?>	
<!-- header -->
<?php getHeader();?>
<!-- end header -->
<?php  $childIds = getChildIds(); // get all child of the Parent ?>
<div class="container">
	<div class="row">
		<h3 class="form-head">Add Patient<span><b class="steps">Step 2 of 5</b></span></h3>
		<div class="col-md-3"></div>
		<div class="col-md-6">
			<div id="page-results"></div>
		 	<form class="add-patient-form add-child-form" method="post">
			<div id="exercises">
				<?php
				if(count($childIds))
				{
					$j = 1;$k = 1;$z=1;
					foreach($childIds as $ids)
					{
						$child_info 	= getChildInfo($ids);
						$dob    	= explode("-",$child_info[0]->birthday); 
						$month  	= $dob[1];
						$year   	= $dob[0];
						$day    	= $dob[2];
						$gender 	= $child_info[0]->gender;
					?>
					 <div class="exercise" id="child-<?php echo $child_info[0]->childId;?>" >
					    <div class="form-group">
								<input type="hidden" name="child_ids[]" value="<?php echo $child_info[0]->childId;?>" />
								<h3 class="form-head"><span class="profile2-child"><b>Child #<?php echo $j;$j++;?></b></span></h3>
								<label for="fullname<?php echo $z;?>">Full name</label>
								<input type="text" placeholder="Enter your child’s first &amp; last name" name="fullname[]" id="fullname<?php echo $z;?>" class="form-control" value="<?php echo $child_info[0]->first_name.' '.$child_info[0]->last_name;?>">
							</div>
							<div class="form-group dob-box">
								<label for="month<?php echo $z;?>">Birth Date</label>
								<div class="clear"></div>
								<div class="db-styled-select styled-select  blue semi-square">
									<select id="month<?php echo $z;?>" name="month[]">
										<option value="">MM</option>
										<?php for($i=1;$i<=12;$i++){?>
										<option  <?php if($i==$month){echo "selected";}?>  value="<?php echo $i;?>"><?php echo $i;?></option>
										<?php }?>
									</select>
								</div>
								<span class="devider">/</span>
								<div class="db-styled-select styled-select  blue semi-square">
								  <select id="days<?php echo $z;?>" name="days[]">
									<option value="">DD</option>
									 <?php for($i=1;$i<=31;$i++){?>
									 <option  <?php if($i==$day){echo "selected";}?>  value="<?php echo $i;?>"><?php echo $i;?></option>
									<?php }?>
								  </select>
								</div>
								<span class="devider">/</span>
								<div class="db-styled-select styled-select  blue semi-square">
								  <select id="year<?php echo $z;?>" name="year[]">
									<option value="">YYYY</option>
									 <?php for($i=1998;$i<=2016;$i++){?>
									 <option  <?php if($i==$year){echo "selected";}?>  value="<?php echo $i;?>"><?php echo $i;?></option>
									<?php }?>
								  </select>
								</div>
								<div class="clear"> </div>
							</div>
							<div class="clear"> </div>
							<div class="form-group">
								<label for="gender<?php echo $z;?>">Gender</label>
								<div class="clear"> </div>
								<div class="styled-select blue semi-square">
									<select id="gender<?php echo $z;?>" name="gender[]">
										 <option value="">Gender</option>
										 <option <?php if($gender=="male"){echo "selected";}?> value="male">Male</option>
						   				 <option <?php if($gender=="female"){echo "selected";}?> value="female">Female</option>
									</select>
								</div>
							</div>
							<?php $z++;if($k!=1){?>
				 			<!-- dont show delete in case of first child -->
							<button class="remove rem deletechildren" name="<?php echo $child_info[0]->first_name.' '.$child_info[0]->last_name;?>"  child_id="<?php echo $child_info[0]->childId;?>">DELETE THIS CHILD</button>
							<!-- end -->
							<?php }?>			
							</div>
				    
	
				<?php $k++;	}
				}else{?>
					<div class="exercise">
					    <div class="form-group">
								<h3 class="form-head"><span class="profile2-child"><b>Child #1</b></span></h3>
								<label for="fullname">Full name</label>
								<input type="text" placeholder="Enter your child’s first &amp; last name" name="fullname[]" id="fullname" class="form-control">
							</div>
							<input type="hidden" name="child_ids[]" value="0" />
							<div class="form-group dob-box">
								<label for="DOB">Birth Date</label>
								<div class="clear"></div>
								<div class="db-styled-select styled-select  blue semi-square">
									<select id="month" name="month[]">
										<option value="">MM</option>
										<?php for($i=1;$i<=12;$i++){?>
										<option value="<?php echo $i;?>"><?php echo $i;?></option>
										<?php }?>
									</select>
								</div>
								<span class="devider">/</span>
								<div class="db-styled-select styled-select  blue semi-square">
								  <select id="days" name="days[]">
									<option value="">DD</option>
									 <?php for($i=1;$i<=31;$i++){?>
									 <option value="<?php echo $i;?>"><?php echo $i;?></option>
									<?php }?>
								  </select>
								</div>
								<span class="devider">/</span>
								<div class="db-styled-select styled-select  blue semi-square">
								  <select id="year" name="year[]">
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
									<select id="gender" name="gender[]">
										<option value="">Gender</option>
										<option value="male">Male</option>
										<option value="female">Female</option>
									</select>
								</div>
							</div>
							<button class="remove rem deletechildren" style="display:none;">DELETE THIS CHILD</button>
							</div>
			
				  
				<?php }?>
				<div id="results"></div>
			 </div>
			<div class="form-group">
		<br><a   class="addchildren pointer" id="add_exercise">+ ADD ANOTHER CHILD</a>
						<button type="button" name="submit"  class="btn btn-info next addchild">Next</button><button type="button" class="btn btn-info back" onclick="window.location.href='profile-step1.php'">Back</button>
		
				<div class="col-md-12"><br> 
				</div> 

				</div>
			</form>

				<div class="col-md-3"></div>
			</div>
	</div>
</div>
<!-- delete child modal -->
<button type="button" class="btn btn-info btn-lg hide" id="trigger-deletechild" data-toggle="modal" data-target="#deletechild">Open Modal</button>
<div id="deletechild" class="modal fade in" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">×</button>
        <h3 class="model-title">Delete Child</h3>
      </div>
      <div class="modal-body">
<div class="delete-child-loading"></div>
<div id="delete-results"></div>
        <p>Are you sure you want to delete <span id="delete-child-name"></span> ?</p>
      </div>
      <div class="modal-footer">
      <button type="button" name="submit" id="delete-child-submit" class="btn btn-info next2">Delete</button><button data-dismiss="modal" type="button" class="btn btn-info back">Cancel</button>
      </div>
    </div>

  </div>
</div>
<!-- end -->
<script src="//code.jquery.com/jquery-1.12.0.min.js"></script>
<script src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script>
var child_id = <?php  if(count(getChildIds())==0){ echo "2";}else{echo count(getChildIds())+1;}?>;
   $('#add_exercise').on('click', function() {
   $('.rem').show();
   $('#exercises').append(
	'<div class="exercise" aid="'+child_id+'"><div class="form-group"><h3 class="form-head"><span class="profile2-child"><b>Child #'+child_id+'</b></span></h3><input type="hidden" name="child_ids[]" value="0"><label for="fullname'+child_id+'">Full name</label><input type="text" placeholder="Enter your child’s first &amp; last name" name="fullname[]" id="fullname'+child_id+'" class="form-control"></div><div class="form-group dob-box"><label for="month'+child_id+'">Birth Date</label><div class="clear"></div><div class="db-styled-select styled-select  blue semi-square"><select id="month'+child_id+'" name="month[]"><option value="">MM</option><?php for($i=1;$i<=12;$i++){?><option value="<?php echo $i;?>"><?php echo $i;?></option><?php }?></select></div><span class="devider">/</span><div class="db-styled-select styled-select  blue semi-square"><select id="days'+child_id+'" name="days[]"><option value="">DD</option><?php for($i=1;$i<=31;$i++){?><option value="<?php echo $i;?>"><?php echo $i;?></option><?php }?></select></div><span class="devider">/</span><div class="db-styled-select styled-select  blue semi-square"> <select id="year'+child_id+'" name="year[]"><option value="">YYYY</option><?php for($i=1998;$i<=2016;$i++){?><option value="<?php echo $i;?>"><?php echo $i;?></option><?php }?></select></div><div class="clear"> </div></div><div class="clear"> </div><div class="form-group"><label for="gender'+child_id+'">Gender</label><div class="clear"> </div><div class="styled-select blue semi-square"><select id="gender'+child_id+'" name="gender[]"><option value="">Gender</option><option value="male">Male</option><option value="female">Female</option></select></div></div><button class="remove deletechildren">DELETE THIS CHILD</button></div>');
    child_id++;
    return false; //prevent form submission
});

 

$(document).on("click",".remove",function(e){ 

    var childID = $(this).attr("child_id");
    var name    = $(this).attr("name");
    var flag = 1;
    if(typeof childID !== 'undefined')
    {
	    $("#delete-child-name").html(name);
	    $("#delete-child-submit").attr("child_id",childID);
	    $("#delete-child-submit").attr("reference",this);
	    $("#trigger-deletechild").click();
    }
    else
    {
	    $(this).parent().remove(); 
	    
   	    $(".exercise").each(function(){
		 
		if(typeof $(this).attr('aid') !== 'undefined')		
		{
			flag = parseInt($(this).attr('aid'))+1;	
		}
		else
		{
			 flag++;
		}
 	    });
         
	    child_id = flag;
    	    
	   
    }
     	 
	return false;

});



$(document).on("click","#delete-child-submit",function(e){ 
$("#delete-results").html("<div class='loading2'><img src='images/loading.gif'></div>");
	var id = $(this).attr("child_id");
	var reference = $(this).attr("reference");
	if(id!="")
	{
		$.ajax({
			url: 'function.php',
			type: 'post',
			dataType:'text',
			data:{function_name:'deleteChild',childs:id},
			success: function(text) {
				$(".close").click();
				$("#child-"+id).remove(); 
				$("#delete-results").html('');
				$("#page-results").html("<div class='success'>Child Deleted Successfully</div>");
			},
		});

	}
	
    
   
});

$("document").ready(function(){
	var flag  = 0;
	var error = "";
   	$(".addchild").click(function(){
 	$("#results").html("<div class='loading-center'><img src='images/loading.gif'></div>");
	flag  = 0;	
 
	var names    = [];
	var dob      = [];
	var genders  = [];
	var child_id = [];

	var fields = document.getElementsByName("fullname[]");
	var month  = document.getElementsByName("month[]");
	var days   = document.getElementsByName("days[]");
	var year   = document.getElementsByName("year[]");
	var gender = document.getElementsByName("gender[]");
	var child_ids = document.getElementsByName("child_ids[]");
 

	for(var i = 0; i < fields.length; i++) {
		
		/** check gender **/
		if(gender[i].value=="")
		{
			error = "Select your child’s gender";
			flag++;
		}
		else
		{
   	 		genders.push(gender[i].value);
		}
		/** end **/

	 	
		
		/** check dob **/
		if(month[i].value=="" || days[i].value=="" || year[i].value=="")
		{
			error = "Enter your child’s DOB";
			flag++;
		}
		else
		{
			var birth = year[i].value+"-"+month[i].value+"-"+days[i].value;
			dob.push(birth);
		}
		/** end **/
 
		/** check names **/
		if(fields[i].value.trim()=="")
		{
			error = "Enter your child's full name";
			flag++;
		}
		else
		{
   	 		names.push(fields[i].value);
		}
		/** end **/



		if((month[i].value=="" || days[i].value=="" || year[i].value=="") && gender[i].value=="" && fields.value=="" )
		{
			error = "Please enter fill entire forms";
			flag++;
		}

		
		/** check names **/
		if(child_ids[i].value=="")
		{
		
		}
		else
		{
   	 		child_id.push(child_ids[i].value);
		}
		/** end **/
		
		
	}
	  
 
	/** check flag **/
	if(flag!=0)
	{
	     $("#page-results").html("<div class='warning'>"+error+"</div>");
	     $("#results").html("");
	}
	else
	{
		$.ajax({
				url: 'function.php',
				type: 'post',
				dataType:'text',
				data: {function_name:'addbulkchildajax',names:names,dob:dob,genders:genders,child_id:child_id,submit:1},
				success: function(text) {
				 	if(text==="1")
					{
					       $('html, body').animate({scrollTop: $(".form-head").offset().top}, 2000);
			                       $("#page-results").html("<div class='success'>Patients Added Successfully.</div>");	
					       window.setTimeout(function() {
    					       		window.location.href = 'profile-step3.php';
					       }, 2000);									
					}
					else
					{
						$("#page-results").html(text);
						$("#results").html("");
					}				
				 },
			
			});
	}
	/** end **/

	});	 
});
</script>
<?php getFooter();?>
