<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["designation_add"])){
	extract($_POST);
	$err="";
	if(empty($title))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO designation (title) VALUES ('".slash($title)."')";
		doquery($sql,$dblink);
		unset($_SESSION["designation_manage"]["add"]);
		header('Location: designation_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["designation_manage"]["add"][$key]=$value;
		header('Location: designation_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}