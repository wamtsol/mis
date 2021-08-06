<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["holidays_add"])){
	extract($_POST);
	$err="";
	if(empty($date))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO holidays (date, is_working_day) VALUES ('".slash(date_dbconvert($date))."', '".slash($is_working_day)."')";
		doquery($sql,$dblink);
		unset($_SESSION["holidays_manage"]["add"]);
		header('Location: holidays_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["holidays_manage"]["add"][$key]=$value;
		header('Location: holidays_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}