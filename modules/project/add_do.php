<?php

if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["project_add"])){
	extract($_POST);
	$err="";
	if(empty($title) || empty($client_id))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO project (title, client_id, start_date, end_date, details, expected_revenue) VALUES ('".slash($title)."','".slash($client_id)."','".slash(date_dbconvert($start_date))."', '".slash(date_dbconvert($end_date))."', '".slash($details)."','".slash($expected_revenue)."')";
		doquery($sql,$dblink);
		$id=inserted_id();
		unset($_SESSION["project_manage"]["add"]);
		header('Location: project_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["project_manage"]["add"][$key]=$value;
		header('Location: project_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}