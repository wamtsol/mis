<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["project_add"])){
	extract($_POST);
	$err="";
	if(empty($title) || empty($client_id))
		$err="Fields with (*) are Mandatory.<br />";
	if($err==""){
		$sql="INSERT INTO project (title, client_id, start_date, end_date, details, expected_revenue) VALUES ('".slash($title)."','".slash($client_id)."','".slash(date_dbconvert($start_date))."', '".slash(date_dbconvert($end_date))."', '".slash($details)."','".slash($expected_revenue)."')";
		doquery($sql,$dblink);
		$id=inserted_id();
		if( isset( $account_ids ) && count( $account_ids ) > 0 ) {
			foreach( $account_ids as $account_id ) {
				doquery( "insert into project_2_account(project_id, account_id) values( '".$id."', '".$account_id."' )", $dblink );
			}
		}
		if( isset( $expense_category_ids ) && count( $expense_category_ids ) > 0 ) {
			foreach( $expense_category_ids as $expense_category_id ) {
				doquery( "insert into project_2_expense_category(project_id, expense_category_id) values( '".$id."', '".$expense_category_id."' )", $dblink );
			}
		}
		unset($_SESSION["project_manage"]["add"]);
		header('Location: project_manage.php?tab=list&msg='.url_encode("Sucessfully Added"));
		die;
	}
	else{
		foreach($_POST as $key=>$value)
			$_SESSION["project_manage"]["add"][$key]=$value;
		header('Location: project_manage.php?tab=add&err='.url_encode($err));
		die;
	}
}