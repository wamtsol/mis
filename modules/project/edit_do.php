<?php

if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["project_edit"])){
	extract($_POST);
	$err="";
	if(empty($title) || empty($client_id))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="Update project set `title`='".slash($title)."', `client_id`='".slash($client_id)."', `start_date`='".slash(date_dbconvert($start_date))."', `end_date`='".slash(date_dbconvert($end_date))."', `details`='".slash($details)."',`expected_revenue`='".slash($expected_revenue)."'"." where id='".$id."'";
		doquery($sql,$dblink);
		unset($_SESSION["project_manage"]["edit"]);
		header('Location: project_manage.php?tab=list&msg='.url_encode("Sucessfully Updated"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["project_manage"]["edit"][$key]=$value;
		header("Location: project_manage.php?tab=edit&err=".url_encode($err)."&id=$id");
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from project where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
			$start_date=date_convert($start_date);
			$end_date=date_convert($end_date);
		if(isset($_SESSION["project_manage"]["edit"]))
			extract($_SESSION["project_manage"]["edit"]);
	}
	else{
		header("Location: project_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: project_manage.php?tab=list");
	die;
}