<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["project_payment_edit"])){
	extract($_POST);
	$err="";
	if(empty($project_id))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="Update project_payment set `project_id`='".slash($project_id)."',`datetime_added`='".slash(datetime_dbconvert(unslash($datetime_added)))."', `amount`='".slash($amount)."',`sales_tax`='".slash($sales_tax)."',`gst_withheld`='".slash($gst_withheld)."',`invoice_amount`='".slash($invoice_amount)."',`account_id`='".slash($account_id)."',`details`='".slash($details)."',`exempt_tax`='".slash($exempt_tax)."',`total_applications`='".slash($total_applications)."',`noc_delivery`='".slash($noc_delivery)."',`disabled`='".slash($disabled)."',`discount`='".slash($discount)."',`credit`='".slash($credit)."',`correction`='".slash($correction)."',`correction_returned`='".slash($correction_returned)."',`home_delivery`='".slash($home_delivery)."'"." where id='".$id."'";
		doquery($sql,$dblink);
		unset($_SESSION["project_payment_manage"]["edit"]);
		header('Location: project_payment_manage.php?tab=list&msg='.url_encode("Sucessfully Updated"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["project_payment_manage"]["edit"][$key]=$value;
		header("Location: project_payment_manage.php?tab=edit&err=".url_encode($err)."&id=$id");
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from project_payment where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
			$datetime_added = datetime_convert($datetime_added);
		if(isset($_SESSION["project_payment_manage"]["edit"]))
			extract($_SESSION["project_payment_manage"]["edit"]);
	}
	else{
		header("Location: project_payment_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: project_payment_manage.php?tab=list");
	die;
}