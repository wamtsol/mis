<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["salary_add"])){
	extract($_POST);
	$err="";
	if(empty($datetime_added) || empty($amount) || empty($payment))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO salary (employee_id, category_id, datetime_added, details, amount, payment, added_by) VALUES ('".slash($employee_id)."','".slash($category_id)."', '".slash(datetime_dbconvert($datetime_added))."', '".slash($details)."','".slash($amount)."','".slash($payment)."','".$_SESSION["logged_in_admin"]["id"]."')";
		doquery($sql,$dblink);
		unset($_SESSION["salary_manage"]["add"]);
		header('Location: salary_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["salary_manage"]["add"][$key]=$value;
		header('Location: salary_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}