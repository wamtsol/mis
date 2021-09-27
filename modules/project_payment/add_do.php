<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["project_payment_add"])){
	extract($_POST);
	$err="";
	if(empty($project_id))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO project_payment (project_id, datetime_added, amount, sales_tax, gst_withheld, invoice_amount, account_id, details, exempt_tax, total_applications, noc_delivery, disabled, discount, credit, correction, correction_returned, home_delivery) VALUES ('".slash($project_id)."','".slash(datetime_dbconvert($datetime_added))."','".slash($amount)."','".slash($sales_tax)."','".slash($gst_withheld)."','".slash($invoice_amount)."','".slash($account_id)."','".slash($details)."','".slash($exempt_tax)."','".slash($total_applications)."','".slash($noc_delivery)."','".slash($disabled)."','".slash($discount)."','".slash($credit)."','".slash($correction)."','".slash($correction_returned)."','".slash($home_delivery)."')";
		doquery($sql,$dblink);
		unset($_SESSION["project_payment_manage"]["add"]);
		header('Location: project_payment_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["project_payment_manage"]["add"][$key]=$value;
		header('Location: project_payment_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}