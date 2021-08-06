<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["employee_add"])){
	extract($_POST);
	$err="";
	if(empty($name))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO employee (designation_id, name, father_name, contact_number, cnic_number, gender, date_of_appointment, address, salary, bank_account_number) VALUES ('".slash($designation_id)."', '".slash($name)."', '".slash($father_name)."', '".slash($contact_number)."', '".slash($cnic_number)."', '".slash($gender)."', '".slash(date_dbconvert($date_of_appointment))."', '".slash($address)."', '".slash($salary)."', '".slash($bank_account_number)."')";
		doquery($sql,$dblink);
		$id=inserted_id();
		if( isset( $project_ids ) && count( $project_ids ) > 0 ) {
			foreach( $project_ids as $project_id ) {
				doquery( "insert into employee_2_project (employee_id, project_id) values( '".$id."', '".$project_id."' )", $dblink );
			}
		}
		unset($_SESSION["employee_manage"]["add"]);
		header('Location: employee_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["employee_manage"]["add"][$key]=$value;
		header('Location: employee_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}