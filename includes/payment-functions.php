<?php


/****************************************************************************************************************************/
/***************************************************************************************************************************/
/*****************		     PediaQ Web Application                         		 **************************/
/*****************	Developed by 	: Indiwork Software Solutions, Mohali India   		 *************************/
/*****************	Developer Name 	: Ashpreet Singh, ashpreet@indiwork.com   	       **************************/
/*****************		    This File consists all functions which are related with profile      ***************/
/**********************************************************************************************************************/
/*********************************************************************************************************************/
/********************************************************************************************************************/
/*******************************************************************************************************************/
 
function getPaymentToken()
{
	if(isset($_SESSION['payment_token']))
	{
		return $_SESSION['payment_token'];
	}
	else
	{
		$user_token = getUserToken();
		$data = array("token"=>$user_token);
		$url  = "api/payment/token";
		$paymentToken = json_decode(PostData($url,$data));// call getchild api	
		if($paymentToken->success==1)
		{
			$_SESSION['payment_token'] = $paymentToken->paymentToken;
			return $_SESSION['payment_token'];	
		}
	}
}

function getPaymentCards()
{

	$user_token = getUserToken();
	$data = array("token"=>$user_token);
	$url  = "api/payment/get-list";
	$getpayment = json_decode(PostData($url,$data));// call getchild api	
	/** if there are payment methods available **/	
	if($getpayment->success==1)
	{
		return $getpayment->paymentMethodList;	
	}
	else   /** if thier is error while fetching methods **/
	{
		return array();
	}	

}


function getNotDefaultPaymentCards()
{

	$user_token = getUserToken();
	$data = array("token"=>$user_token);
	$url  = "api/payment/get-list";
	$getpayment = json_decode(PostData($url,$data));// call getchild api	
	/** if there are payment methods available **/	
	if($getpayment->success==1 && count($getpayment->paymentMethodList))
	{
		$method = array();
		$i = 0;		
		foreach($getpayment->paymentMethodList as $method)
		{	
			return $method->paymentMethodList;
		}	
	}	
	else   /** if thier is error while fetching methods **/
	{
		return array();
	}	

}

function addCard()
{
	$data = array(
		"token" 	=> getUserToken(),
		"paymentNonce"  => "7cb309b6-4bf7-45df-bf3e-bb0f9570a20a",
		"isDefault"     => 0
	);
	$url  = "api/payment/add";
	$post = json_decode(PostData($url,$data)); 
	
}

function setInsuranceProviders()
{
	 
	if(isset($_SESSION['insurance_providers']))
	{
		return  $_SESSION['insurance_providers'];
	}
	else
	{
		$providers = getInsuranceProviders("",0);
		
		
		if(count($providers))
		{
			$filter['data'] = array();
			foreach($providers as $key=>$id)	
			{
				$filter[$key] =   $id;					
						
			}
			$_SESSION['insurance_providers'] = $filter;

		}	
	}
		return  $_SESSION['insurance_providers'];
}

function getInsuranceIDbyLAbel($data)
{
	
	if(isset($data['label']) && isset($_SESSION['insurance_providers']) && count($_SESSION['insurance_providers']))
	{
		if(array_search($data['label'],$_SESSION['insurance_providers']))
		{
			echo array_search($data['label'],$_SESSION['insurance_providers']);
		}
		else
		{
			echo "0";
		}
	}
	else
	{
		echo "0";	
	}
}

function getInsuranceProvidersFilter($keyword)
{

if(isset($_SESSION['insurance_providers']) && count($_SESSION['insurance_providers']))
{
		$k = 0;
		$limit = 10;
		$filter = array();
		foreach($_SESSION['insurance_providers'] as $id)
		{ 
				if(preg_match("/".$keyword."/i",$id))
				{
					if($k<=$limit)
					{
						$filter[] = $id;
						$k++;
					}
					else
					{
						break;			
					}
									
				}
		}
		echo json_encode($filter);
}

}
function getInsuranceProviders($keyword,$ajax)
{ 

	$data = array("token" 	=> getUserToken());
	$url  = "api/insurance/providers-list";
	$post = json_decode(PostData($url,$data)); 
	$insurance = array();
	if($post->success==1 && $keyword!="abc")
	{
		if(count($post->providersList))
		{
			
			foreach($post->providersList as $ids)
			{
				if(count($ids))
				{	
					$key = "";
					$i = 0;
					foreach($ids as $id)
					{
						if($i==0)
						{
							$key = $id;
							$insurance[$id] = "";$i++;
						}
						else
						{
							$i=0;
							$insurance[$key]=$id;
						}					
					
					}
				}
			}
		}
		/** This is to check if we have insurance options **/
		if(count($insurance))	
		{
			
			if($ajax)
			{			
				$k = 0;
				$limit = 10;		
			}
			else
			{
				$k = 0;
				$limit = 1000000000;
			
			}
		
			$filter = array();
			foreach($insurance as $key=>$id)
			{ 
				if(preg_match("/".$keyword."/i",$id))
				{
					if($k<=$limit)
					{
						$filter[$key] = $id;
										
						
					}
					else
					{
						break;			
					}
					$k++;				
				}
			}
                        if($ajax==1)
			{
				
		        	echo json_encode($filter);
					 
			}
			else
			{
				 return $filter;		
			}
		}	
		/** end **/
	}
}
/** add promo code  to visit request **/
function addPromoCode($data)
{
	if(isset($data['promocode']))
	{
		$post = array("token" 	=> getUserToken(),'promocode'=>$data['promocode']);
		$url  = "api/request/get-promo-info-client";
		$apply = json_decode(PostData($url,$post)); 	
		if($apply->success=="1")
		{	
			$_SESSION['promo_code'] = $data['promocode'];
			echo "<div><div class='success'>Promo Code is Applied</div></div>";		
		}
 		else
		{	
		
			unset($_SESSION['promo_code']);
			if(isset($apply->errors->messageTitle))
			{
			       
				echo "<div><div class='warning'>".$apply->errors->messageTitle."</div></div>";
			}
			else
			{
				echo "<div><div class='warning'>".$apply->errors->message."</div></div>";
			}		
		}
	}

} 

/** unset promo code  to visit request **/
function removePromoCode($data)
{
	if(isset($data['status']))
	{
		$post = array("token" 	=> getUserToken());
		$url  = "api/user/reset-promotion";
		$apply = json_decode(PostData($url,$post)); 	
		if($apply->success=="1")
		{	
			unset($_SESSION['promo_code']); 
			echo "<div><div class='success'>Promo Code is Removed</div></div>";		
		}
 		else
		{
			unset($_SESSION['promo_code']);
			echo "<div><div class='warning'>".$apply->errors->message."</div></div>";
		}
	}

} 

/** add insurance ajax **/
function addInsurancetoRequest($data)
{
	if(isset($data['id']))
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

		$post = array(
				"token"	 		=> getUserToken(),
				"groupNumber" 		=> $data['group'],
				"holderFirstName"	=> $firstname,
				"holderLastName"	=> $lastname,
				"memberId"		=> $data['memberid'],
				"providerId"		=> $data['id'],
				"frontSideScan" 	=> "images/blank.png",
				"backSideScan" 		=> "images/blank.png",
			);
		$url  = "api/insurance/set-by-parent";
		$addinsurance = json_decode(PostData($url,$post)); 
		
		 
	 	if($addinsurance->success=="1")
		{
			$_SESSION['request_insurance_id'] = $addinsurance->insurance->id;	
			$_SESSION['request_provider_id']  = $addinsurance->insurance->provider->id;
			$_SESSION['request_provider_name']= $addinsurance->insurance->provider->name;
			echo "<br><div><div class='success'>Insurance Added to Request</div></div>";					
		}
		else
		{
			foreach($addinsurance->errors as $error)
			{
				if(is_array($error) && count($error))
				{
					foreach($error as $bugs)
					{
				        	echo "<div><div class='warning'>".$bugs."</div></div>";		
					}				
				}
				else
				{
				        echo "<div><div class='warning'>".$addinsurance->errors->message."</div></div>";	
				}			
			}		
		}
	}

}
/** end **/

function IsServiceAvailable()
{
	
	$url  = "api/user/self-status";
	$post = array("token"=> getUserToken());
	$service = json_decode(PostData($url,$post));
	if($service->success=="1")
	{	
		if($service->status=="1" or $service->status=="2")
		{
			return 1;	
		}
		else
		{
			switch ($service->status) 
			{
	   		 	 
				case "3":
					return "Request Status is Not available";
				break;
				case "4":
					return "Request Status is Blocked";
				break;
		    		case "5":
					return "Request Status is Deleted";
				break;
		    		default:
				return "Request Status is unavailable";
			}
		}
	}
	
}

/** delete Insurance **/
function deleteInsurance($data)
{
	if(isset($data['status']))
	{
		$url  = "api/insurance/reset";
		$post = array("token"=> getUserToken());
		$reset = json_decode(PostData($url,$post));
		if($reset->success=="1")
		{
			unset($_SESSION['request_insurance_id']);	
			unset($_SESSION['request_provider_id']);
			unset($_SESSION['request_provider_name']);
			echo "<div><div class='success'>Insurance Deleted Successfully</div></div>";		
		}
		else
		{
			echo "<div><div class='warning'>".$reset->errors->message."</div></div>";	
		} 	
	}
}
/** end **/


function getInsurance($data)
{
  if(getUserToken()!=0)
  {
      	$url = "api/insurance/get";
	$post = array("token"=> getUserToken());
	$getinsurance = json_decode(PostData($url,$post)); 
	
		
	if($getinsurance->success=="1")
	{
	 	echo "<div><div class='success'>";
		echo "<div class='id'>".$getinsurance->insurance->provider->id."</div>";
		echo "<div class='provider_name'>".$getinsurance->insurance->provider->name."</div>";
		echo "<div class='memberid'>".$getinsurance->insurance->memberId."</div>";
		echo "<div class='groupNumber'>".$getinsurance->insurance->groupNumber."</div>";
		echo "<div class='name'>".$getinsurance->insurance->holderFirstName." ".$getinsurance->insurance->holderLastName."</div>";
		echo "</div></div>";	
	}
	else
	{
		echo "<div><div class='warning'>".$getinsurance->errors->message."</div></div>";
	}	
  }
}


function WorkingHours()
{
	$url = "api/request/is-service-available";
	$post = array("token"=> getUserToken());
	$working = json_decode(PostData($url,$post));
 	
	if($working->success=="1")
	{
		if($working->result->isAvailable=="1")
		{
			
			$error = "<div>";
			$week = $working->result->weekends;
			$weekdays = $working->result->weekdays;

			if(!$week->closed)
			{
			  $start_time = date("H",strtotime((int)$week->timeFrom));
			  $end_time   = date("H",strtotime((int)$week->timeTo));
				
			  if((int)$start_time>12)
			  {
	   			$start_time_final = (int)$start_time."pm";
			  }
			  else
			  {
			   	$start_time_final = (int)$start_time."am";
  			  }

			  if((int)$end_time>12)
			  {
	   			$end_time_final = (int)$end_time."pm";
			  }
			  else
			  {
			   	$end_time_final = (int)$end_time."am";
  			  }
			
					$error.='<br><div class="col-md-12 fee"><b>Weekends</b><a data-toggle="tooltip" title="On national holidays, there is an additional fee of $50" data-placement="top"><span class="glyphicon glyphicon-question-sign span-q"></span></a>
<a class="pull-right col-md-3 fee-cost">'.$start_time_final.'-'.$end_time_final.'</a>
</div>'; 				
			}
 
			if(!$weekdays->closed)
			{
			 
			  $start_time = date("H",strtotime((int)$weekdays->timeFrom));
			  $end_time   = date("H",strtotime((int)$weekdays->timeTo));
				
			  
			  if((int)$start_time>12)
			  {
	   			$start_time_final = (int)$start_time."pm";
			  }
			  else
			  {
			   	$start_time_final = (int)$start_time."am";
  			  }

			  if((int)$end_time>12)
			  {
	   			$end_time_final = (int)$end_time."pm";
			  }
			  else
			  {
			   	$end_time_final = (int)$end_time."am";
  			  }
 
					$error.='<div class="col-md-12 fee"><b>Weekdays</b><a data-toggle="tooltip" title="On national holidays, there is an additional fee of $50" data-placement="top"><span class="glyphicon glyphicon-question-sign span-q"></span></a>
<a class="pull-right col-md-3 fee-cost">'.$start_time_final.'-'.$end_time_final.'</a>
</div>'; 								
			}			
			
			$error = $error."</div>";
			return $error;	
		}
		else
		{
			return 0;			
		}	
	}  

}

function ComfirmRequest($data)
{

 	if(isset($data['status']))
	{
		$flag  = 0;
		$error = "<div><div class='warning'>";
		if(!isset($_SESSION['request_child']))	
		{
			$error = $error."<p>Please Add Patient to Request</p>";	
			$flag++;
		}
		if(!isset($_SESSION['request_address']))	
		{
			$error = $error."<p>Please Visit Location</p>";	
			$flag++;
		}

		if(IsServiceAvailable()!=1)// check if service is available or not
		{
			$error = $error."<p>".IsServiceAvailable()."</p>";	
			$flag++;		
		}
 
		if(WorkingHours()!=0)// check if service is available or not
		{
			$error = $error."<p id='working_hour_error'>oops we are not working currently</p>";	
			$error = $error."<div class='working'>".WorkingHours()."</div>";
			$flag++;		
		}
		

		$error = $error."</div></div>";
		
		if($flag)
		{
			echo $error;
		}
		else
		{
			echo "1";	
		}	
	}

}
function getCurrentRequestStatus()
{
	if(getUserToken())
	{
		$data = array("token" 	=> getUserToken());
		$url = "api/request/current";
		$post = json_decode(PostData($url,$data)); 		
		return $post;		
	}
		

}
function CheckStatusofRequest($data)
{

	if(isset($_SESSION['request_id']))
	{ 

		$waitlist = getCurrentRequestStatus(); 
		if($waitlist->success=="1" && $waitlist->request->status!=5 && $waitlist->request->nurseId=="")
		{
			echo "1";
		}
		elseif($waitlist->success=="1" && $waitlist->request->status!=5 && $waitlist->request->nurseId!="")
		{
			echo "2";
		}
		else
		{
			echo "0";					
		}
	}



}


function CancelRequest($data)
{

	if(isset($data['status']))
	{
		if(isset($_SESSION['request_child'])) // unset request child
		{
			unset($_SESSION['request_child']);		
		}
		if(isset($_SESSION['request_address'])) // unset request address
		{
			unset($_SESSION['request_address']);		
		}
		if(isset($_SESSION['payment_token'])) // unset request payment token
		{
			unset($_SESSION['payment_token']);		
		}
		if(isset($_SESSION['actual_payment_token'])) // unset request nounce
		{
			unset($_SESSION['actual_payment_token']);		
		}
		if(isset($_SESSION['wait_time']))  // unset request wait time after submit
		{
			unset($_SESSION['wait_time']);		
		}
		if(isset($_SESSION['wait_state'])) // unset request wait state if any
		{
			unset($_SESSION['wait_state']);		
		}
		if(isset($_SESSION['request_id']))
		{
			$data = array("status"=> 5,"id"=>$_SESSION['request_id'],"token" => getUserToken());
			$url = "api/request/change-status";
			$cancel = json_decode(PostData($url,$data)); 				
			unset($_SESSION['request_id']);		
		}
				
		echo "<div><div class='success'>Done</div></div>";
			
	}
	else
	{
		echo "<div><div class='warning'>Error </div></div>";	
	}

}

function Notifywhenavailable($data)
{
	if(isset($_POST['status']))
	{
		$url  = "api/request/subscribe-on-nurse-available";
		$post = array("token"=> getUserToken(),"addressId"=>$_SESSION['request_address']);
		$notify = json_decode(PostData($url,$post)); 
				
		if($notify->success=="1")
		{
			$error = "<div class='success'>";
			$error = $error."<div class='header'>".$notify->messageTitle."</div>";	
			$error = $error."<div class='body'>".$notify->message."</div></div>";	
		}
		else
		{
			$error = "<div class='error'>";
			$error = $error."<div class='body'>".$notify->message."</div></div>";			
		}

		echo $error;	
	}
}

function checkValidRequest()
{

if(!isset($_SESSION['request_child']))
{
	
	redirect("dashboard.php");
}

$getcurrent = getCurrentRequestStatus();
if($getcurrent->request->status=="5")
{
	ChangeRequeststatus(1);
}


}

function checkwaittime($data)
{

	if(isset($_SESSION['request_id']))
	{
		$url  = "api/request/position-in-wait-list";
		$post = array("token"=> getUserToken(),"requestId"=>$_SESSION['request_id']);
		$waitlist = json_decode(PostData($url,$post)); 
		if($waitlist->success=="1")
		{
			return $waitlist;
		}
		else
		{
			if(isset($data['status']))
			{
				echo "<div><div class='container warning'>".$waitlist->errors->message."</div></div>";
			}
			else
			{
				return 0;			
			}		
		}
	}

}

function waitstate()
{
	if(!isset($_SESSION['request_id']))
	{
		redirect("request_summary.php");
	}
	else
	{
		$getnurse = getAvailabeleNurse();
		$gettime  = checkwaittime("data");
		$getcurrent = getCurrentRequestStatus();
	
		/** check the accept status  **/
		if($getnurse->success=="1" && $gettime->success=="1" && $getcurrent->request->status!=5 && $getcurrent->request->nurseId!=0)
		{
			$_SESSION['request_nurse_id'] = $currentstatus->request->nurseId;
			redirect("accept_request.php");
		}
		/** redirect if accepted **/	
	}

}

function getAvailabeleNurse()
{
	$url  = "api/parent/get-available-nurses";
	$getaddress =  getAddressbyId($_SESSION['request_address']);
	$post = array("token"=> getUserToken(),"longitude"=>$getaddress->longitude,"latitude"=>$getaddress->latitude);
	$nurse = json_decode(PostData($url,$post)); 
	return $nurse;
}


function getNurseProfile($nurse_id)
{
	$url = "api/parent/get-nurse";
	$post = array("token"=> getUserToken(),"nurseId"=>$nurse_id);
	$nurse_profile = json_decode(PostData($url,$post));
	return $nurse_profile;		
}
function acceptrequest()
{
 	$url  = "api/request/is-service-available";
	$post = array("token"=> getUserToken());
	$working = json_decode(PostData($url,$post));
	$currentstatus = getCurrentRequestStatus();

	if($currentstatus->success=="1" && $currentstatus->request->status!="7")
	{
		
		if($working->success=="1")//is service available also remember to keep a check on current status from admin
		{	
			if($working->result->isAvailable=="1")
			{
				$nurses = getAvailabeleNurse(); // if nurse is available
				if($currentstatus->request->nurseId!="")//hardcoded should be count $nurses!=0
				{
					echo "1";
					$_SESSION['request_nurse_id'] = $currentstatus->request->nurseId;// alloting the nurse to Request	
				}
				else
				{
					echo "<div><div class='warning nurse-error'>NP not available, Please wait we are still checking.</div>";
				}
			}

		}
		else
		{
			echo "<div><div class='warning operation-error'>Operations Hours are over. Please Try later</div>";		
		}
	}
	else
	{
		echo "<div><div class='warning admin-error'>Admin has Cancelled the request</div>";	
	}
}

function ChangeRequeststatus($status)
{

	if($status!="")
	{
		$data = array("status"=> $status,"id"=>$_SESSION['request_id'],"token" => getUserToken());
		$url  = "api/request/change-status";
		$cancel = json_decode(PostData($url,$data)); 
		if($cancel->success=="1")
		{
			return 1;		
		}
		else
		{
			return $cancel;
		}		
	}

}

function addComments($post)
{
	if(isset($_SESSION['request_id']))
	{
		$data = array(
				"rating"   	=> $post['counter'],
				"comment"  	=> $post['comments'],
				"requestId"	=> $_SESSION['request_id'],
				"nurseId"	=> $_SESSION['request_nurse_id'],
				"token" 	=> getUserToken()
		);

		$url  = "api/parent/add-comment";
		$addcomment = json_decode(PostData($url,$data)); 	
		if($addcomment->success=="1")
		{	
			echo "1";		
		}
		else
		{
			echo "<div><div class='warning'>Error while adding Review</div></div>";
		}	
	}
}


function getNurseLocation($nurseId)
{
	$data = array(
			"nurseId" => $nurseId,
			"token"   => getUserToken()
		);
	$url  = "api/parent/get-nurse-location";
	$getLocation = json_decode(PostData($url,$data)); 
	if($getLocation->success=="1")
	{
		return $getLocation;
        }
	else
	{
		return 0;	
	}	

}



function getParentInsurance()
{
  if(getUserToken()!=0)
  {
      	$url = "api/insurance/get";
	$post = array("token"=> getUserToken());
	$getinsurance = json_decode(PostData($url,$post)); 
	
		
	if($getinsurance->success=="1")
	{
	 	 return $getinsurance;
	}
	else
	{
		return 0;
	}	
  }
}


function AjaxRequestStatus($data)
{
	if(isset($data['status']))
	{
		$currentstatus = getCurrentRequestStatus();
		$status        = $currentstatus->request->status;
		$flag 	       = 0;
		/** check if visit status is finished then complete the request automatic **/
		if($status=="9")
		{
			ChangeRequeststatus(10);
			$flag++;		
		}
		/** end **/

		if($flag==0)
		{
			echo $status;
		}
		else
		{
			echo "10";		
		}
	}
}



function AddCommentVerification()
{
	$currentstatus = getCurrentRequestStatus();
	$status        = $currentstatus->request->status;

	/** if the status is cancelled **/	
	if($status=="5" || $status=="6" || $status=="7")
	{
		redirect("cancel_request.php");	
	}
	/** end **/
	
	/** if the status is anything else than finish Visit **/
	if($status!=10 && $current->request->nurseId!="")
	{
		redirect("accept_request.php");
	}
	/** end **/

	/** if the status is anything else than finish Visit **/
	if($status!=10 && $current->request->nurseId=="")
	{
		//redirect("waiting.php");
	}
	/** end **/
	ChangeRequeststatus(10);
	$_SESSION['amount'] = ($currentstatus->request->amount/100);
	date_default_timezone_set('America/Chicago');
	$_SESSION['end_time'] = date('H:i', $currentstatus->request->finishedTime);


}
 
?>
