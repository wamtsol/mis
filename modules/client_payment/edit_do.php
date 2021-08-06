<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["client_payment_edit"])){
	extract($_POST);
	$err="";
	if(empty($client_id))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="Update client_payment set `client_id`='".slash($client_id)."',`datetime`='".slash(datetime_dbconvert(unslash($datetime)))."', `amount`='".slash($amount)."',`account_id`='".slash($account_id)."',`details`='".slash($details)."'"." where id='".$id."'";
		doquery($sql,$dblink);
		unset($_SESSION["client_payment_manage"]["edit"]);
		header('Location: client_payment_manage.php?tab=list&msg='.url_encode("Sucessfully Updated"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["client_payment_manage"]["edit"][$key]=$value;
		header("Location: client_payment_manage.php?tab=edit&err=".url_encode($err)."&id=$id");
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from client_payment where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
			$datetime=datetime_convert($datetime);
		if(isset($_SESSION["client_payment_manage"]["edit"]))
			extract($_SESSION["client_payment_manage"]["edit"]);
	}
	else{
		header("Location: client_payment_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: client_payment_manage.php?tab=list");
	die;
}