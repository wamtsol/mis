<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["client_edit"])){
	extract($_POST);
	$err="";
	if(empty($client_name))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="Update client set `client_name`='".slash($client_name)."',`representative_name`='".slash($representative_name)."',`phone`='".slash($phone)."', `address`='".slash($address)."',`balance`='".slash($balance)."'"." where id='".$id."'";
		doquery($sql,$dblink);
		unset($_SESSION["client_manage"]["edit"]);
		header('Location: client_manage.php?tab=list&msg='.url_encode("Sucessfully Updated"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["client_manage"]["edit"][$key]=$value;
		header("Location: client_manage.php?tab=edit&err=".url_encode($err)."&id=$id");
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from client where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
		if(isset($_SESSION["client_manage"]["edit"]))
			extract($_SESSION["client_manage"]["edit"]);
	}
	else{
		header("Location: client_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: client_manage.php?tab=list");
	die;
}