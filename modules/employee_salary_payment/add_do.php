<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["employee_salary_payment_add"])){
	extract($_POST);
	$err="";
	if(empty($employee_id))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO employee_salary_payment (employee_id, project_id, datetime_added, amount, account_id, details, cheque_number) VALUES ('".slash($employee_id)."', '".slash($project_id)."', '".slash(datetime_dbconvert($datetime_added))."', '".slash($amount)."', '".slash($account_id)."', '".slash($details)."', '".slash($cheque_number)."')";
		doquery($sql,$dblink);
		unset($_SESSION["employee_salary_payment_manage"]["add"]);
		header('Location: employee_salary_payment_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["employee_salary_payment_manage"]["add"][$key]=$value;
		header('Location: employee_salary_payment_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}