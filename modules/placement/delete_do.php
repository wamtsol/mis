<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	$id=slash($_GET["id"]);
	
	doquery("delete from placement_item where placement_id='".$id."'",$dblink);
	doquery("delete from placement where id='".$id."'",$dblink);
	header("Location: placement_manage.php");
	die;
}