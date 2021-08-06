<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["client_payment_add"])){
	extract($_POST);
	$err="";
	if(empty($client_id))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO client_payment (client_id, datetime, amount, account_id, details) VALUES ('".slash($client_id)."','".slash(datetime_dbconvert($datetime))."','".slash($amount)."','".slash($account_id)."','".slash($details)."')";
		doquery($sql,$dblink);
		unset($_SESSION["client_payment_manage"]["add"]);
		header('Location: client_payment_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["client_payment_manage"]["add"][$key]=$value;
		header('Location: client_payment_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}