<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>PediaQ | The pediatric House Call </title>
<link href="css/bootstrap.css" rel="stylesheet" type="text/css">
<link href="css/custom.css" rel="stylesheet" type="text/css">
<script src="js/jquery.min.js"></script>
<script src="/bootstrap/js/bootstrap.min.js"></script>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet" type="text/css">
<!-- fonts -->
<link href='//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700' rel='stylesheet' type='text/css'>
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,400italic,700' rel='stylesheet' type='text/css'>
</head>
<body>
<!-- header starts here -->
<header  class="container">
		<div class="row">
			<div class="col-md-6 divlogo">
				<a href="index.php" class="logo"><img src="images/logo.png" alt="logo"/></a>
			</div>
			<div class="col-md-6">
				<div class = "btn-group pull-right" style="display:none;">
				   <button type = "button" class = "btn btn-primary dropdown-toggle" data-toggle = "dropdown">
					   <i class="fa fa-tasks fa-fw"></i> 
				   </button>
				   
				   <ul class = "dropdown-menu" role = "menu">
					  <li><a href = "#">Action</a></li>
					  <li><a href = "#">Another action</a></li>
					  <li><a href = "#">Something else here</a></li>
					  <li class = "divider"></li>
					  <li><a href = "#">Separated link</a></li>
				   </ul>
				   
				</div>
				<?php if(getUserToken()!=0){ ?>
<div class="dropdown pull-right log-out">
<button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown"><?php 

if(getProfile("first_name")!="")
{
echo getProfile("first_name")." ".getProfile("last_name");
}
else
{ 
echo "Hi, User";
}

?><span class="caret"></span></button>
    <ul class="dropdown-menu">
      <li><a href="profile-step1.php" class="first-child-li-a">Account</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </div>
 <?php }?>
</div>
</div>
		

</header>


<?php if(isset($_SESSION['success'])){?>
	<div class="container success"><?php echo $_SESSION['success'];?></div>
<?php unset($_SESSION['success']); }?>


<?php if(isset($_SESSION['error'])){?>
	<div class="container warning"><?php echo $_SESSION['error'];?></div>
<?php unset($_SESSION['error']); }?>
