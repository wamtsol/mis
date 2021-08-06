<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["employee_edit"])){
	extract($_POST);
	$err="";
	if(empty($name))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="Update employee set `designation_id`='".slash($designation_id)."',`name`='".slash($name)."',`father_name`='".slash($father_name)."',`contact_number`='".slash($contact_number)."',`cnic_number`='".slash($cnic_number)."',`gender`='".slash($gender)."',`date_of_appointment`='".slash(date_dbconvert($date_of_appointment))."',`address`='".slash($address)."',`salary`='".slash($salary)."',`bank_account_number`='".slash($bank_account_number)."'"." where id='".$id."'";
		doquery($sql,$dblink);
		doquery("delete from employee_2_project where employee_id='".$id."'", $dblink);
		if( isset( $project_ids ) && count( $project_ids ) > 0 ) {
			foreach( $project_ids as $project_id ) {
				doquery( "insert into employee_2_project(employee_id, project_id) values( '".$id."', '".$project_id."' )", $dblink );
			}
		}
		unset($_SESSION["employee_manage"]["edit"]);
		header('Location: employee_manage.php?tab=list&msg='.url_encode("Sucessfully Updated"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["employee_manage"]["edit"][$key]=$value;
		header("Location: employee_manage.php?tab=edit&err=".url_encode($err)."&id=$id");
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from employee where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
			$date_of_appointment=date_convert($date_of_appointment);
			$project_ids = array();
			$sql="select * from employee_2_project where employee_id='".$id."'";
			$rs1 = doquery( $sql, $dblink );
			if( numrows( $rs1 ) > 0 ) {
				while( $r1 = dofetch( $rs1 ) ) {
					$project_ids[] = $r1[ "project_id" ];
				}
			}
		if(isset($_SESSION["employee_manage"]["edit"]))
			extract($_SESSION["employee_manage"]["edit"]);
	}
	else{
		header("Location: employee_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: employee_manage.php?tab=list");
	die;
}