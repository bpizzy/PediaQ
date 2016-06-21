<?php

/******************************************************************************************************************************/
/*****************************************************************************************************************************/
/****************************************************************************************************************************/
/***************************************************************************************************************************/
/*****************		       PediaQ Web Application                         		         ******************/
/*****************	Developed by 	: Indiwork Software Solutions, Mohali India   		         *****************/
/*****************	Developer Name 	: Ashpreet Singh, ashpreet@indiwork.com   	                 ****************/
/*****************		    This File consists all functions which are related with profile      ***************/
/**********************************************************************************************************************/
/*********************************************************************************************************************/
/********************************************************************************************************************/
/*******************************************************************************************************************/
 
function getProfile($key)
{
	$user_token = getUserToken();
	$data = array("token"=>$user_token);
	$url = "api/user/getprofile";
	$getprofile = json_decode(PostData($url,$data));// call getchild api
	if($getprofile->success==1)
	{
		if($key!="all")
		{
			return $getprofile->$key;		
		}
		else
		{
			return $getprofile;
		}
			
	}
	else
	{
		 return $getprofile->errors->message;	
	}

}

function getDefaultAddress()
{
	$getalladdress = getProfile("address");
	if(count($getalladdress))// if user has address check default address
	{
		foreach($getalladdress as $address)
		{
			if($address->default==1)
			{
				$_SESSION['request_address'] = $address->id;
				return getAddressbyId($address->id);
			}		
		}	
		return 0;	
	}
	else
	{
		return 0;	
	}
} 



function getDefaultAddressAjax($post)
{
	$getalladdress = getProfile("address");
	if(count($getalladdress))// if user has address check default address
	{
		foreach($getalladdress as $address)
		{
			if($address->default==1)
			{
				$_SESSION['request_address'] = $address->id;
				$address =  getAddressbyId($address->id);
?>
				<div class="form-group address pointer" id="<?php echo $address->id;?>">
				<?php echo $address->street;?><a class="delete-address pull-right" address_id="<?php echo $address->id;?>" name="<?php echo $address->street;?>">X</a>
				</div>
				<div class="clear"> </div>
				
<?php			}		
		}	
		 
	}
	else
	{
		echo 0;	
	}
} 


function getAddressbyId($address_id)
{
	$getalladdress = getProfile("address");
	if(count($getalladdress))// if user has address check default address
	{
		foreach($getalladdress as $address)
		{
			if($address->id==$address_id)
			{
				return $address;
			}		
		}	
		return 0;	
	}
	else
	{
		return 0;	
	}
} 
function addAddress($data) // add address
{
	if(getUserToken())
	{

		$post = array(
				'token'	        => getUserToken(),
				'street' 	=> $data['address'],
				'street_number' => $data['snumber'],
				'default'	=> 0,
				'zip'		=> $data['zip'],
				'apt_suite'	=> $data['apt'],
				
			);
	 

		if(isset($data['mode']) && $data['mode']=="edit")// if the mode is edit than edit address else update address
		{
			
			$post_data = array(
					'token'	        => getUserToken(),
					'street' 	=> $data['address'],
					'street_number' => $data['snumber'],
					'zip'		=> $data['zip'],
					'apt_suite'	=> $data['apt'],
					'id'		=> $data['address_id']
			);
			
			$url = "api/parent/updateaddress";
			$updateaddress = json_decode(PostData($url,$post_data));// call getchild api
			if($updateaddress->success==1)
			{
				echo "<div><div class='success'>Address has been Updated Successfully</div></div>";
			}
			else
			{
				echo "<div><div class='warning'>".$updateaddress->errors->message."</div></div>";
			}
		}
		else
		{
			$url = "api/parent/addaddress";
		
			$addAddress = json_decode(PostData($url,$post));// call getchild api	
			if($addAddress->success==1)
			{
				echo "<div><div class='success'>Address has been added Successfully</div></div>";
			}
			else
			{
				echo "<div><div class='warning'>".$addAddress->errors->message."</div></div>";
			}
		}	
	}
}
function getAddressesajax($post)
{
	
	$user_token = getUserToken();
	$data = array("token"=>$user_token);
	$url = "api/user/getprofile";
	$getprofile = json_decode(PostData($url,$data));// call getchild api
	if($getprofile->success==1)
	{ 
		if(count($getprofile->address))
		{

			//pr($getprofile->address);
			$flag = 0;
			foreach($getprofile->address as $address)
			{	
				if($address->default==0){?>
					<div class="form-group ajax-address-container address">
			<span class="ajax-address pointer"  id="<?php echo $address->id;?>"><?php echo $address->street;?></span><a data-dismiss="modal" class="edit-address pull-right pointer" address_id="<?php echo $address->id;?>">Edit</a>
		</div>
					
				<?php $flag++;}}
				if($flag==0)
				{
					echo "No Addresses Found";
				}
		}
		else
		{
			echo "No Addresses Found";
		}		
	}
	else
	{
		echo $getprofile->errors->message;	
	}

}
function updatedefaultaddress($data)
{
	if(isset($data['address_id']))
	{
		$data = array(
		'token' 	=> getUserToken(),
		'id'    	=> $data['address_id'],
		'default'	=> 1
		);
		$url = "api/parent/updateaddress";
		$updateaddress = json_decode(PostData($url,$data));// call getchild api
		if($updateaddress->success==1)
		{
			$_SESSION['request_address'] = $updateaddress->address->id;
		?>			
			<div class="form-group address pointer" id="<?php echo $updateaddress->address->id;?>">
			<?php echo $updateaddress->address->street;?><a class="delete-address pull-right" address_id="<?php echo $updateaddress->address->id;?>" name="<?php echo $updateaddress->address->street;?>">X</a>
</div>
<div class="clear"> </div>
				
		<?php }
		else
		{
			echo "<div class='warning'>".$updateaddress->errors->message."</div>";		
		}
	}
}





function deleteAddressfromRequest($data)
{
	if(isset($data['address_id']))
	{
		$data_post = array(
			'token'		=> getUserToken(),
			'id'		=> $data['address_id']			
		);	
		$url = "api/parent/removeaddress";
		$deleteaddress = json_decode(PostData($url,$data_post));// call getchild api

		if($deleteaddress->success==1)
		{
			$addresscount = count(getProfile("address"));
			if($addresscount)
			{
				echo "1";			
			}
			else
			{
				echo "0";
			}	
		}
		else
		{
			echo "<div class='response'><div class='warning'>".$deleteaddress->errors->message."</div></div>";		
		}
	
	}
}



function deleteAddress($data)
{
	if(isset($data['address_id']))
	{
		$data_post = array(
			'token'		=> getUserToken(),
			'id'		=> $data['address_id']			
		);	
		$url = "api/parent/removeaddress";
		$deleteaddress = json_decode(PostData($url,$data_post));// call getchild api

		 
		if($deleteaddress->success==1)
		{
			$_SESSION['request_address'] = 0;
			$address= getDefaultAddress();
			if($address->id!="")
			{?>
				<div class="form-group address pointer" id="<?php echo $address->id;?>">
<?php echo $address->street;?><a class="delete-address pull-right" address_id="<?php echo $address->id;?>" name="<?php echo $address->street;?>">X</a>
</div>
<div class="clear"> </div>
			<?php }
			else
			{
			echo "<div class='warning'>Please Add a Address for Visit</div>";
			}	
		}
		else
		{
			echo "<div class='response'><div class='warning'>".$deleteaddress->errors->message."</div></div>";		
		}
	
	}
}
function getEditAddressForm($data)
{
	if(isset($data['address_id']))
	{	
		 $address =  getAddressbyId($data['address_id']);
			 if(isset($address->id))
			 { ?>	
				<div class="col-md-12">
					<div class="street"><?php echo $address->street;?></div>	
					<div class="street_number"><?php echo $address->street_number;?></div>	
				 	<div class="zip"><?php echo $address->zip;?></div>	
					<div class="apt_suite"><?php echo $address->apt_suite;?></div>
					<div class="address_id"><?php echo $address->id;?></div>		
				</div>
			 
			 <?php }
			 else
			 {
				echo "<div class='warning'>Address Id Doesn't Exists</div>";
			 }
	}

}

function getEditProfile($data)
{
	if(isset($data['submit']))
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
			'token' 	=> getUserToken(),	
			'first_name'	=> $firstname,
			'last_name'	=> $lastname,
			'email'		=> $data['email']
		);	
		
		$url = "api/user/updateprofile";
		$update = json_decode(PostData($url,$post));// call update profile api
		if($update->success==1)
		{
			echo "1";		
		}
		else
		{
			$string = "";			
			foreach($update->errors as $error)
			{
				$string.= $error[0];
			}
			echo "<div class='warning'>".$string."</div>";	
		}
	}
}

function addbulkchildajax($data)
{
	if(isset($data['submit']))
	{
		$i = 0;	
		$errors = "";
		$child_added = 0;
		foreach($data['names'] as $name)
		{
			$names = explode(" ",$name);
	
 
			/** extracting firstname and lastname from name **/
			if(count($names)>1)
			{
				$firstname = $names[0];
				$lastname  = $names[1];			
					
			}
			elseif(count($names)==1)
			{
				$firstname = $names[0];
				$lastname  = "";
			}
			/** end **/

			

			if($data['child_id'][$i]!=0)// if child is pre-existing then edit else add
			{
				$post = array(
				        'gender'     =>  $data['genders'][$i],
					'last_name'  =>  $lastname,
					'birthday'   =>  $data['dob'][$i],
					'token'      =>  getUserToken(),
					'childId'    =>  $data['child_id'][$i],	
					'first_name' =>  $firstname
				      );

				
				$url = "api/parent/updatechild";
				$addchild = json_decode(PostData($url,$post));
		
			}
			else
			{ 
				 $post = array(
					        'gender'      =>  $data['genders'][$i],
						'last_name'  =>  $lastname,
						'birthday'   =>  $data['dob'][$i],
						'token'      =>  getUserToken(),
						'first_name' =>  $firstname
					      );

				 $url = "api/parent/addchild";
				 $addchild = json_decode(PostData($url,$post));
			}

		 
			if($addchild->success==1)
			{
				$child_added++;			
			}
			
			

			/** if error **/		
			if(!$addchild->success)
			{
				
				if(count($addchild->errors))
				{
					foreach($addchild->errors as $key=>$val)
					{
					 	if(is_array($val))
						{						
							$errors.="<p>".$val[0]."</p>"; 			
						}
						else
						{
							$errors.="<p>".$val."</p>"; 	
						}	
					}				
				}
						
			}	
			/** end error **/	
			$i++;	
			
		}
		/** end foreach **/

		if(count($data['names'])==$child_added)
		{
			echo "1";
		}
		else
		{
			echo "<div class='warning'>".$errors."</div>";
		}	
	}
}
function getEditAddress($data)
{
	if(isset($data['id']))
	{
		$post = array(
					'token'	     		=> getUserToken(),
				        'street'     		=>  $data['street'],
				        'street_number'     	=>  $data['street_number'],
				        'default'    		=>  0,
				        'zip'     		=>  $data['zip'],
				        'apt_suite'     	=>  $data['apt_suite'],
				        'id'     		=>  $data['id'],					 
			     );

				
		$url = "api/parent/updateaddress";
		$updateAddress = json_decode(PostData($url,$post));
	
		
		if($updateAddress->success==1)/** success **/
		{
			echo "<div class='response'><div class='success'>Address updated Successfully</div></div>";		
		}
		else
		{
			echo "<div class='response'><div class='warning'>".$deleteaddress->errors->message."</div></div>";
		}
	}
}
?>
