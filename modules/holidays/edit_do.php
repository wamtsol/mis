<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["holidays_edit"])){
	extract($_POST);
	$err="";
	if(empty($date))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="Update holidays set `date`='".slash(date_dbconvert($date))."',`is_working_day`='".slash($is_working_day)."'"." where id='".$id."'";
		doquery($sql,$dblink);
		unset($_SESSION["holidays_manage"]["edit"]);
		header('Location: holidays_manage.php?tab=list&msg='.url_encode("Sucessfully Updated"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["holidays_manage"]["edit"][$key]=$value;
		header("Location: holidays_manage.php?tab=edit&err=".url_encode($err)."&id=$id");
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from holidays where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
			$date = date_convert($date);
		if(isset($_SESSION["holidays_manage"]["edit"]))
			extract($_SESSION["holidays_manage"]["edit"]);
	}
	else{
		header("Location: holidays_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: holidays_manage.php?tab=list");
	die;
}