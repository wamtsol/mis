<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["client_add"])){
	extract($_POST);
	$err="";
	if(empty($client_name))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO client (client_name, representative_name, phone, address, balance) VALUES ('".slash($client_name)."','".slash($representative_name)."','".slash($phone)."','".slash($address)."','".slash($balance)."')";
		doquery($sql,$dblink);
		unset($_SESSION["client_manage"]["add"]);
		header('Location: client_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["client_manage"]["add"][$key]=$value;
		header('Location: client_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}