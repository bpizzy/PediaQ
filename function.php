<?php
getsession();


/*****************************************************************************************************************************/
/****************************************************************************************************************************/
/***************************************************************************************************************************/
/**************************************************************************************************************************/
/*****************		       PediaQ Web Application                         		**************************/
/*****************	Developed by 	: Indiwork Software Solutions, Mohali India   		*************************/
/*****************	Developer Name 	: Ashpreet Singh, ashpreet@indiwork.com                 ************************/
/**********************************************************************************************************************/
/*********************************************************************************************************************/
/********************************************************************************************************************/
/*******************************************************************************************************************/
 
/** include profile functions **/
require "includes/profile-functions.php";
/** end **/

/** include Payment functions **/
require "includes/payment-functions.php";
/** end **/

 

require "function-maps.php";

function getsession()
{

   if (!session_id()) 
   {
	ini_set('session.use_only_cookies', 'On');
	ini_set('session.use_trans_sid', 'Off');
	ini_set('session.cookie_httponly', 'On');
        session_set_cookie_params(0, '/');
	session_start();
   }

}

function DashboardRedirect()//redirect user if profile is not complete.
{
	if(getUserToken()!=0)
	{
	        
		$data = array("token"=>getUserToken());
		$url  = "api/user/getprofile";
		$getprofile = json_decode(PostData($url,$data));// call getchild api
		if(count($getprofile->address)==0 || $getprofile->first_name=="" || $getprofile->last_name=="" || $getprofile->email=="")
		{
			 redirect("profile-step1.php");		
		}

		/** check current request status **/
		$data = array("token"=>getUserToken());
		$url = "api/request/current";
		$current = json_decode(PostData($url,$data));// call getchild api	
		if($current->success=="1" && $current->request->status=="12")
		{
			$_SESSION['request_child']        = $current->request->childIds;
			$_SESSION['request_address']	  = $current->request->addressId;
			$_SESSION['actual_payment_token'] = $current->request->braintreeToken;
			$_SESSION['request_id']		  = $current->request->id;
			redirect("request_summary.php");		
		}
		/** end **/
		 
	}

/** check if child is not there **/
if(!count(getChildIds()))
{
	redirect("profile-step2.php");
}	 
/** end **/
}

function pr($a)
{
	echo "<pre>"; print_r($a); echo "</pre>";

}

function getHeader()
{
	include "includes/header.php";
	include "config.php";
}


function isLogin()
{
   if(isset($_SESSION['user_token']))
   {
	Header( "HTTP/1.1 301 Moved Permanently" ); 
	Header( "Location: dashboard.php" ); 
   }
}


function CheckLogin()
{
   if(isset($_SESSION['user_token']))
   {
	if(!isset($_SESSION['insurance_providers'])){ setInsuranceProviders();}
   }
   else
   {
	Header( "HTTP/1.1 301 Moved Permanently" ); 
	Header( "Location: index.php" ); 
   }
}



function getFooter()
{
	include "includes/footer.php";
}


function logout()
{
	if(isset($_SESSION['user_token']))
	{
	    session_destroy();
	    Header( "HTTP/1.1 301 Moved Permanently" ); 
	    Header( "Location: index.php" ); 
	}
	else
	{
	    Header( "HTTP/1.1 301 Moved Permanently" ); 
	    Header( "Location: index.php" ); 	
	}
}



function PostData($url,$data)// Dont Modify this function this is the core function of the application
{
	if(count($data))
	{
		$fields = array();
		foreach($data as $key=>$val)
		{
			$fields[$key]=$val;
		}

	        $actual_url  = "http://devpediaq.webprv.com/".$url;                                                           
		$data_string = json_encode($fields);                                                                                   
				                                                                                                     
		$ch = curl_init($actual_url);                                                                      
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");                                                                     
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);                                                                      
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
		    'Content-Type: application/json',                                                                                
		    'Content-Length: ' . strlen($data_string),
                    'platform: web'
                    )                                                                       
		);                                                                                                                   
		                                                                                                             
		$result = curl_exec($ch);
		return $result;
	}
	else
	{
		return array();
	}

}

function addChildajax()
{
	if(isset($_POST['name']))
	{
		$name = explode(" ",$_POST['name']);
		/** extracting firstname and lastname from name **/
		if(count($name)>1)
		{
			$firstname = $name[0];
			for($i=1;$i<=count($name);$i++)
			{
				$lastname.=$name[$i];			
			}		
		}
		elseif(count($name)==1)
		{
			$firstname = $name[0];
			$lastname  = "";
		}
		/** end **/

		$data = array(
		'gender'     =>  $_POST['gender'],
		'last_name'  => $lastname,
		'birthday'   =>  $_POST['dob'],
		'token'      =>  getUserToken(),
		'first_name' =>  $firstname
		);

		$url = "api/parent/addchild";
		$addchild = json_decode(PostData($url,$data));	
		/** if error **/		
		if(!$addchild->success)
		{
			$errors = "";
			if(count($addchild->errors))
			{
				foreach($addchild->errors as $key=>$val)
				{
					$errors.="<p>".$val[0]."</p>"; 			
				}
			}
			echo "<div class='warning'>".$errors."</div>";		
		}	
		/** end error **/

		/** if success **/
		if($addchild->success)
		{
			echo "<div class='success'>Child has been added successfully</div>";		
		}
		/** end **/
	}

}

function getUserToken()
{
	if(isset($_SESSION['user_token']))
	{
		return $_SESSION['user_token'];
	}
	else
	{
		return 0;	
	}
} 

function getRequestChilds()
{
	if(isset($_SESSION['request_child']))
	{
		return $_SESSION['request_child'];
	}
	else
	{
		return 0;	
	}
} 
function getChildIds()//get all childs of login user
{
	$user_token = getUserToken();
	$data = array("token"=>$user_token);
	$url  = "api/user/family";
	$getfamily = json_decode(PostData($url,$data));// call getchild api
	$childs = array();
	if($getfamily->success && count($getfamily->family->children))
	{
	foreach($getfamily->family->children as $child){
	array_push($childs,$child->childId);
	}

	return $childs;
	}


}
function deleteChild($child_id)
{

	$user_token = getUserToken();
	$data = array("token"=>$user_token,"childId"=>$child_id);
	$url  = "api/parent/removechild";
	$delete = json_decode(PostData($url,$data));// call getchild api
	if($delete->success)
	{
		echo "child has been delete";	
		$key = array_search($child_id, getRequestChilds());
		 
		
		if($key!==false)
		{ 
			unset($_SESSION['request_child'][$key]);
		}
	}
	else
	{ 
		echo "error while deleting child";
	}
}

function deleteChildfromRequest($child_id)
{
	$key = array_search($child_id, getRequestChilds());
	
	if(isset($_SESSION['request_child'][$key]) && $_SESSION['request_child'][$key]==$child_id)
        {
		unset($_SESSION['request_child'][$key]);
                if(count($_SESSION['request_child'])) //check if there are other childs available.
                {
                      echo "0";
                }
                else
                {
                     echo "1";
                }
	}
	else
	{
		echo "error while deleting child from Request";
	}
}

function getExcludedChildIds($childs)//get all childs of login user
{
	$user_token = getUserToken();
	$data = array("token"=>$user_token);
	$url  = "api/user/family";
	$getfamily = json_decode(PostData($url,$data));// call getchild api
	$childs    = array();
	if($getfamily->success && count($getfamily->family->children))
	{
	foreach($getfamily->family->children as $child){
	
	if(!in_array($child->childId, $childs))
	{
		array_push($childs,$child->childId);
	}
	}

	return $childs;
	}


}

function getExcludedajaxChildIds($exchilds)//get all childs of login user
{
	$user_token = getUserToken();
	$data = array("token"=>$user_token);
	$url  = "api/user/family";
	$getfamily = json_decode(PostData($url,$data));// call getchild api
	$childs    = array();
	if($getfamily->success && count($getfamily->family->children))
	{
	foreach($getfamily->family->children as $child){
	
	if(!in_array($child->childId, $exchilds))
	{
		array_push($childs,$child->childId);
	}
	}
	}
      
	if(count($childs))
	{
		$child_data = array();
		$i = 0;
		foreach($childs as $child)
		{
		     $child_info = getChildInfo($child);
		?>
		<div class="form-group ajaxchilds child pointer" id="<?php echo $child;?>">
			<?php echo $child_info[0]->first_name." ".$child_info[0]->last_name;?><a data-dismiss="modal" class="edit-childs pull-right" child_id="<?php echo $child;?>">Edit</a>
		</div>
		<?php
		      
		}
	}
	else
	{
	echo "No Child Found, Please Add";	
	}

}

function redirect($url)
{?>
<script type="text/javascript">
<!--
   window.location.assign("<?php echo $url;?>");
//-->
</script>
<?php }


function getChildInfo($child_id)//get particular child  of login user
{
	$user_token = getUserToken();
	$data = array("token"=>$user_token);
	$url  = "api/user/family";
	$getfamily = json_decode(PostData($url,$data));// call getchild api
	$childs = array();
	
	if($getfamily->success && count($getfamily->family->children))
	{
		foreach($getfamily->family->children as $child){
		{
			if($child->childId==$child_id)
			{
				array_push($childs,$child);
			}	
		}
	}
	return $childs;
	}


}
function getRaces()
{
	$user_token = getUserToken();
	$data = array("token"=>$user_token);
	$url  = "api/user/getraces";
	$getraces = json_decode(PostData($url,$data));// call getchild api
	if($getraces->success)
	{
		return $getraces->races;	
	}
	else
	{
		return array();	
	}

}
function getLanguages()
{
	$user_token = getUserToken();
	$data = array("token"=>$user_token);
	$url  = "api/user/getlanguages";
	$getlanguages = json_decode(PostData($url,$data));// call getchild api
	if($getlanguages->success)
	{
		return $getlanguages->languages;	
	}
	else
	{
		return array();	
	}

}
function editChild($data)
{
	if(isset($data['submit']))
	{
		$user_token = getUserToken();
	 	$data = array(
			'first_name' => $_POST['first_name'],
			'last_name'  => $_POST['last_name'],
			'first_name' => $_POST['first_name'],
			'childId'    => $_POST['child_id'],
			'gender'     => $_POST['gender'],
			'race'       => $_POST['race'],
			'language'   => $_POST['language'],
			'token'	     => $user_token,
			'birthday'   => $_POST['year']."-".$_POST['month']."-".$_POST['day'],
		);
		$url  = "api/parent/updatechild";
		$updatechild = json_decode(PostData($url,$data));
		if($updatechild->success=="1")
		{
			$_SESSION['success'] = "Child has been updated";
		}
		else
		{
			$_SESSION['error']  =  $updatechild->errors->message;	
		}
                
		$url = "dashboard.php";
		redirect($url);
			
	}

}
function addchildtosession($data)
{
    	if(getUserToken())// check if user has a token value
	{
		$_SESSION['request_child'] = $_POST['child'];
	}
}

function unsetchildsession($data)
{
    	if(getUserToken())// check if user has a token value
	{
		$_SESSION['success'] = "You have Successfully Cancelled Visit Request";
		unset($_SESSION['request_child']);
		unset($_SESSION['promo_code']);
		unset($_SESSION['request_address']);
		unset($_SESSION['insurance_providers']);
	}
}

function getTariff($tariff)
{
	$user_token = getUserToken();
	$data = array("token"=>$user_token);
	$url  = "api/tariff/get";
	$gettariff = json_decode(PostData($url,$data));// call getchild api
	if($gettariff->success)
	{
		$tariffs = array();
		if(count($gettariff->tariff->fields))
		{
			$i=0;
			foreach($gettariff->tariff->fields as $key=>$val)
			{
				if((count($tariff) && in_array($key, $tariff)) || count($tariff)==0)
				{
					$tariffs[$i] = $val;
					$i++;
				}
			}	
		}	
		return $tariffs;
	}
	else
	{
		return array();	
	}

}
/** This function is to add patient in existing session **/ 
function addchildtoexistingsession($data,$html_data)
{
	if(getRequestChilds())
	{
	    	foreach(getRequestChilds() as $child)
		{
			array_push($data['child'],$child);
		}
	}
	if(count($data['child']))
	{
		$_SESSION['request_child'] = $data['child'];	
	} 

	/** if request want html to be return **/
	if($html_data && getRequestChilds())
	{
		$child_data = array();
		$i = 0;
		foreach(getRequestChilds() as $childs)
		{
			 $child_info = getChildInfo($childs);
		?>
		<div class="form-group child pointer" id="<?php echo $childs;?>" >
			<?php echo $child_info[0]->first_name." ".$child_info[0]->last_name;?><a class="delete-childs pull-right" child_id="<?php echo $childs;?>" name="<?php echo $child_info[0]->first_name." ".$child_info[0]->last_name;?>">X</a>
		</div>
		<div class="clear"> </div>
		<?php
   		}	
			
	}
	else
	{
		getRequestChilds();
	}
	
}
/** end **/

/** edit child ajax request for form **/
function editChildajaxform($data)
{
	if(isset($data['id']))
	{
		$childdata = getChildInfo($data['id']);
		$races     = getRaces();
		$languages = getLanguages();
		$dob       = explode("-",$childdata[0]->birthday);
		$month     = $dob[1];
		$year      = $dob[0];
		$day       = $dob[2];
		$gender    = $childdata[0]->gender;
		
	?>
		<form class="add-patient-form add-child-form" action="function.php" method="post">

		 <input type="hidden" id="edit_child_id" value="<?php echo $childdata[0]->childId;?>" class="form-control">
		 <input type="hidden" name="function_name" value="editChild"/>

		<div class="form-group">
			<label for="fullname">Full Name</label>
			<input type="text" id="edit-name" value="<?php echo $childdata[0]->first_name.' '.$childdata[0]->last_name;?>" class="form-control">
		      </div>
		<div class="form-group dob-box">
			<label for="DOB">Birth Date</label>
			<div class="clear"></div>
	

		<div class="db-styled-select styled-select  blue semi-square">
		  <select id="edit-month">
		    <option value="">MM</option>
		    <?php for($i=1;$i<=12;$i++){?>
			 <option <?php if($i==$month){echo "selected";}?> value="<?php echo $i;?>"><?php echo $i;?></option>
		    <?php }?>
		  </select>
		</div><span class="devider">/</span>
		<div class="db-styled-select styled-select  blue semi-square">
		  <select id="edit-day">
		    <option value="">DD</option>
		     <?php for($i=1;$i<=31;$i++){?>
			 <option <?php if($i==$day){echo "selected";}?> value="<?php echo $i;?>"><?php echo $i;?></option>
		    <?php }?>
		  </select>
		</div><span class="devider">/</span>
		<div class="db-styled-select styled-select  blue semi-square">
		  <select id="edit-year">
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
		  <select id="edit-gender">
		    <option value="">Gender</option>
		    <option <?php if($gender=="male"){echo "selected";}?> value="male">Male</option>
		    <option <?php if($gender=="female"){echo "selected";}?> value="female">Female</option>
		  </select>
		</div>
			</div>
		</form>

		<a class="pointer delete-childs" data-toggle="modal"  data-dismiss="modal"  name="<?php echo $childdata[0]->first_name.' '.$childdata[0]->last_name;?>" child_id="<?php echo $data['id'];?>">DELETE THIS CHILD</a>
	<?php
 	} 

}
/** end **/


/** edit child ajax submit **/
function editChildajax($data)
{
	if(isset($data['name']))
	{

		$name = explode(" ",$data['name']);
		/** extracting firstname and lastname from name **/
		if(count($name)>1)
		{
			$firstname = $name[0];
			for($i=1;$i<=count($name);$i++)
			{
				$lastname.=$name[$i];			
			}		
		}
		elseif(count($name)==1)
		{
			$firstname = $name[0];
			$lastname  = "";
		}
		/** end **/
		$user_token = getUserToken();
	 	$data = array(
			'first_name' => $firstname,
			'last_name'  => $lastname,
			'childId'    => $data['child_id'],
			'gender'     => $data['gender'],
			'token'	     => $user_token,
			'birthday'   => $data['dob'],
		);
 
		$url  = "api/parent/updatechild";
		$updatechild = json_decode(PostData($url,$data));
		if($updatechild->success==1)
		{?>
			<div class="success">Child has been updated<br></div>  
		<?php }
		else
		{?>
			<div class="warning"><?php echo $updatechild->errors->message;?><br></div>  	
		<?php }
		 
			
	}

}
/** end **/


function unsetGlobalWarning()
{
	if(isset($_SESSION['warning']))
	{
	unset($_SESSION['warning']);
	}
	if(isset($_SESSION['success']))
	{
	unset($_SESSION['success']);
	}
	if(isset($_SESSION['error']))
	{
	unset($_SESSION['error']);
	}



}




?>
