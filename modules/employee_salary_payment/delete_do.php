<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && !empty($_GET["id"])){
	doquery("delete from employee_salary_payment where id='".slash($_GET["id"])."'",$dblink);
	header("Location: employee_salary_payment_manage.php");
	die;
}