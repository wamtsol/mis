<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["employee_salary_payment_edit"])){
	extract($_POST);
	$err="";
	if(empty($employee_id))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="Update employee_salary_payment set `employee_id`='".slash($employee_id)."', `project_id`='".slash($project_id)."', `datetime_added`='".slash(datetime_dbconvert(unslash($datetime_added)))."', `amount`='".slash($amount)."', `account_id`='".slash($account_id)."', `details`='".slash($details)."', `cheque_number`='".slash($cheque_number)."' where id='".$id."'";
		doquery($sql,$dblink);
		unset($_SESSION["employee_salary_payment_manage"]["edit"]);
		header('Location: employee_salary_payment_manage.php?tab=list&msg='.url_encode("Sucessfully Updated"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["employee_salary_payment_manage"]["edit"][$key]=$value;
		header("Location: employee_salary_payment_manage.php?tab=edit&err=".url_encode($err)."&id='".$id."'");
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from employee_salary_payment where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
			$datetime_added=datetime_convert($datetime_added);
		if(isset($_SESSION["employee_salary_payment_manage"]["edit"]))
			extract($_SESSION["employee_salary_payment_manage"]["edit"]);
	}
	else{
		header("Location: employee_salary_payment_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: employee_salary_payment_manage.php?tab=list");
	die;
}