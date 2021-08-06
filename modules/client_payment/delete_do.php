<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	doquery("delete from client_payment where id='".slash($_GET["id"])."'",$dblink);
	header("Location: client_payment_manage.php");
	die;
}