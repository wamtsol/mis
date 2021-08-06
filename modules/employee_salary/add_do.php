<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["employee_salary_add"])){
	extract($_POST);
	$err="";
	if(empty($employee_id))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO employee_salary (employee_id, project_id, month, year, datetime_added, amount) VALUES ('".slash($employee_id)."', '".slash($project_id)."', '".slash($month)."', '".slash($year)."', '".slash(datetime_dbconvert($datetime_added))."', '".slash($amount)."')";
		doquery($sql,$dblink);
		unset($_SESSION["employee_salary_manage"]["add"]);
		header('Location: employee_salary_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["employee_salary_manage"]["add"][$key]=$value;
		header('Location: employee_salary_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}