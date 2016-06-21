<?php require "function.php";CheckLogin(); getHeader();// load main function file and then load header ?>

<?php 

	$child_id = base64_decode($_GET['child_id']);
	$childs = getChildIds();//get all childs of logged in parent
	if(!in_array($child_id,$childs))
	{
		$_SESSION['error'] = "You dont have access rights for this child";
		redirect("dashboard.php");
	}
	else
	{
		$childdata = getChildInfo($child_id);
		$races     = getRaces();
		$languages = getLanguages();
		$dob       = explode("-",$childdata[0]->birthday);
		$month     = $dob[1];
		$year      = $dob[0];
		$day       = $dob[2];
		$gender    = $childdata[0]->gender;
		$childrace = $childdata[0]->race;
		$childlanguage = $childdata[0]->language;
		 
	}
?>

<div class="container">

 
<div class="row">

<h3 class="form-head">Edit Child <span>Select one or more children for your visit Request.</span></h3>
 
<div class="col-md-3"></div>

<div class="col-md-6">

<form class="add-patient-form" action="function.php" method="post">

 <input type="hidden" name="child_id" value="<?php echo $childdata[0]->childId;?>" class="form-control">
 <input type="hidden" name="function_name" value="editChild"/>

<div class="form-group">
        <label for="fullname">First name</label>
        <input type="text" name="first_name" value="<?php echo $childdata[0]->first_name;?>" class="form-control">
      </div>
<div class="form-group">
        <label for="fullname">Last name</label>
        <input type="text" name="last_name" value="<?php echo $childdata[0]->last_name;?>" class="form-control">
      </div>
      
      
      <div class="form-group dob-box">
        <label for="DOB">Birth Date</label>
	<div class="clear"></div>
	

<div class="db-styled-select styled-select  blue semi-square">
  <select name="month">
    <option value="">MM</option>
    <?php for($i=1;$i<=12;$i++){?>
	 <option <?php if($i==$month){echo "selected";}?> value="<?php echo $i;?>"><?php echo $i;?></option>
    <?php }?>
  </select>
</div><span class="devider">/</span>
<div class="db-styled-select styled-select  blue semi-square">
  <select name="day">
    <option value="">DD</option>
     <?php for($i=1;$i<=31;$i++){?>
	 <option <?php if($i==$day){echo "selected";}?> value="<?php echo $i;?>"><?php echo $i;?></option>
    <?php }?>
  </select>
</div><span class="devider">/</span>
<div class="db-styled-select styled-select  blue semi-square">
  <select name="year">
    <option value="">YYYY</option>
     <?php for($i=1998;$i<=2016;$i++){?>
	 <option <?php if($i==$year){echo "selected";}?> value="<?php echo $i;?>"><?php echo $i;?></option>
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
  <select name="gender">
    <option value="">Gender</option>
    <option <?php if($gender=="male"){echo "selected";}?> value="male">Male</option>
    <option <?php if($gender=="female"){echo "selected";}?> value="female">Female</option>
  </select>
</div>
	</div>


<?php  if(count($races)){?> 
<div class="form-group">
        <label for="races">Race</label>
<div class="styled-select styled-select-1 blue semi-square">	
	<select name="race">
		<?php foreach($races as $key=>$race){?>
		 <option <?php if($key==$childrace){echo "selected";}?> value="<?php echo $key;?>"><?php echo $race;?></option>
		<?php }?>
        </select>  
</div>
      </div>
<?php }?>


<?php  if(count($languages)){?> 
<div class="form-group">
        <label for="races">Languages</label>
        <?php foreach($languages as $key=>$language){?>
	<div class="languages"><input type="checkbox" <?php if(in_array($key, $childlanguage)){echo "checked";}?> id="<?php echo $key;?>" name="language[]" value="<?php echo $key;?>" /><span for="<?php echo $key;?>"><?php echo $language;?></span></div>
	<?php }?>
</div>
<?php }?>
      

<div class="form-group">
<button type="submit" name="submit" id="editchildsave" class="btn btn-info next1">Save</button><button onclick="window.location.href='dashboard.php'" type="button" class="btn btn-info back">Back</button>
</div>
 
</form>

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
           <form class="add-patient-form add-child-form">

<div class="form-group">
        <label for="fullname">Full name</label>
        <input type="email" placeholder="Enter your child’s first &amp; last name" id="fullname" class="form-control">
      </div>
      
      
      <div class="form-group dob-box">
        <label for="DOB">Birth Date</label>
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
<button type="submit" name="submit" value="submit" class="btn btn-info next addchild">Add Child</button><button type="button" class="btn btn-info back">Back</button>
</div>

	
	<div class="clear"> </div>
<div id="result"></div>
      	</div>
      </div>
      
    </div>
   
  
</div>

</div>

<div class="col-md-3"></div>
</div>
</div>


<?php getFooter();?>
<script>
$("document").ready(function(){
   	$(".addchild").click(function(){
 		 var flag 	= 0;
		 var error      = "";
             	 var name 	= $("#fullname").val();
		 var gender     = $("#gender").val();
		 var birthday   = $("#days").val();
		 var birthmonth = $("#month").val();
		 var birthyear  = $("#year").val();
	
		
		if(name=="")
		{
			error+="Please Enter Firstname & Lastname<br>";
			flag++;		
		}
		if(gender=="")
		{
			error+="Please Specify Gender<br>";		
			flag++;	
		}
		if(birthday=="" || birthmonth=="" || birthyear=="")
		{
			error+="Please Enter Birthday<br>";	
			flag++;		
		}
		if(name=="" && gender=="" && (birthday=="" || birthmonth=="" || birthyear=="") )
		{
			error="Please fill complete form<br>";
			flag++;
		}
		
		/** if error **/
		if(flag!=0)
		{
			$("#result").html("<div class='warning'>"+error+"</div>");
		}
		/** end **/

		/** if no error **/
		if(flag==0)
		{
			var dob = birthyear+"-"+birthmonth+"-"+birthday;
			$("#result").html("<div class='loading'><img src='images/loading.gif'></div>");
			$.ajax({
				url: 'function.php',
				type: 'post',
				dataType:'text',
				data: {function_name:'addChildajax',name: name,gender:gender,dob:dob},
				success: function(text){
				$("#result").html(text);
				setTimeout(location.reload, 5000);
				},
			
			});
		}
		/** end **/

        });

	 
});
</script>
<style>
.styled-select-1
{
 
}
.styled-select select
{
width:340px;
}
</style>

