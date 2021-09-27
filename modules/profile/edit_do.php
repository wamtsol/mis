<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["profile_edit"])){
	extract($_POST);
	$err="";
	if(empty($name) || empty($username) || empty($email))
		$err="Fields with (*) are Mandatory.<br />";
	if(!empty($email) && !emailok($email))
		$err.="E-mail is not valid.<br />";
	if(numrows(doquery("select id from admin where username='".slash($username)."' and id<>'".$id."'", $dblink))>0)
		$err.='Username already exists.<br />';
	if(numrows(doquery("select id from admin where email='".slash($email)."' and id<>'".$id."'", $dblink))>0)
		$err.='Email address already exists.<br />';
	if($err==""){
		$sql="Update admin set `username`='".slash($username)."',`name`='".slash($name)."', `email`='".slash($email)."',`monthly_salary`='".slash($monthly_salary)."'".(!empty($password)? ", `password`='".slash($password)."'":"")." where id='".$id."'";
		doquery($sql,$dblink);
		unset($_SESSION["profile"]["edit"]);
		header('Location: profile.php?tab=edit&id=$id&msg='.url_encode("Successfully Updated"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["profile"]["edit"][$key]=$value;
		header("Location: profile.php?tab=edit&err=".url_encode($err)."&id=$id");
		die;
	}
}
/*----------------------------------------------------------------------------------*/
if(isset($_SESSION["logged_in_admin"]["id"]) && $_SESSION["logged_in_admin"]["id"]!=""){
	$rs=doquery("select * from admin where id='".$_SESSION["logged_in_admin"]["id"]."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
		if(isset($_SESSION["profile"]["edit"]))
			extract($_SESSION["profile"]["edit"]);
	}
	else{
		header("Location: profile.php?tab=edit");
		die;
	}
}
else{
	header("Location: admin_manage.php?tab=edit");
	die;
}