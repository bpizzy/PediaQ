<?php
 

/** check ajax Request and forward it POST Request **/
if(isset($_POST['function_name']))
{
	$function_name = $_POST['function_name'];
	unset($_POST['function_name']);
	 
	switch ($function_name) {
	    	case "addChildajax":
			addChildajax($_POST); // add child ajax request
		break;
		case "editChild":
			editChild($_POST); // edit child
		break;
		case "addchildtosession":
			addchildtosession($_POST);  // add selected child to session
		break;
		case "unsetchildsession":
			unsetchildsession($_POST); // unselect a child from request session
		break;
		case "getExcludedajaxChildIds":
			getExcludedajaxChildIds(getRequestChilds()); // get all childs which are not selected for Request
		break;
		case "addchildtoexistingsession":
			addchildtoexistingsession($_POST,1);
		break;
		case "deleteChild":
			deleteChild($_POST['childs']);
		break;
		case "editChildajaxform":
			editChildajaxform($_POST);
		break;
		case "editchildajax":
			editChildajax($_POST);
		break;
		case "getAddressesajax":
			getAddressesajax($_POST);
		break;
		case "addAddress":
			addAddress($_POST);
		break;
		case "updatedefaultaddress":
			updatedefaultaddress($_POST);
		break;
		case "deleteAddress":
			deleteAddress($_POST);
		break;
		case "getDefaultAddressAjax":
			getDefaultAddressAjax($_POST);
		break;
		case "getEditAddressForm":
			getEditAddressForm($_POST);
		break;
		case "getEditProfile":
			getEditProfile($_POST);
		break;
		case "addbulkchildajax":
			addbulkchildajax($_POST);
		break;
		case "getInsuranceAjax":
			getInsuranceProviders($_POST['keyword']);
		break;
		case "getEditAddress":
			getEditAddress($_POST);
		break;	
		case "addPromoCode":
			addPromoCode($_POST);
		break;
		case "removePromoCode":
			removePromoCode($_POST);
		break;
		case "getInsuranceProviders":
			getInsuranceProviders($_POST['keyword'],1);
		break;
		case "getInsuranceIDbyLAbel":
			getInsuranceIDbyLAbel($_POST);
		break;
		case "addInsurancetoRequest":
			addInsurancetoRequest($_POST);
		break;
		case "deleteInsurance":
			deleteInsurance($_POST);
		break;
		case "getInsurance":
			getInsurance($_POST);
		break;
		case "ComfirmRequest":
			ComfirmRequest($_POST);
		break;
		case "CancelRequest":
			CancelRequest($_POST);
		break;
		case "Notifywhenavailable":
			Notifywhenavailable($_POST);
		break;
		case "checkwaittime":
			checkwaittime($_POST);
		break;
		case "acceptrequest":
			acceptrequest($_POST);
		break;
		case "addComments":
			addComments($_POST);
		break;
		case "CheckStatusofRequest":
			CheckStatusofRequest($_POST);
		break;
		case "AjaxRequestStatus":
			AjaxRequestStatus($_POST);
		break;
		case "deleteAddressfromRequest":
			deleteAddressfromRequest($_POST);
		break;
		case "deleteChildfromRequest":
			deleteChildfromRequest($_POST['childs']);
		break;
	}

}
/** end of ajax request check **/

 

/** check ajax Request and forward it's for Get Request**/
if(isset($_GET['function_name']))
{
	$function_name = $_GET['function_name'];
	unset($_GET['function_name']);
	 
	switch ($function_name) {
	        case "getInsuranceProvidersFilter":
			getInsuranceProvidersFilter($_GET['term']);
		break;
		
	}

}
/** end of ajax request check **/
?>
