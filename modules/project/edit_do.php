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
		doquery("delete from project_2_account where project_id='".$id."'", $dblink);
		if( isset( $account_ids ) && count( $account_ids ) > 0 ) {
			foreach( $account_ids as $account_id ) {
				doquery( "insert into project_2_account(project_id, account_id) values( '".$id."', '".$account_id."' )", $dblink );
			}
		}
		doquery("delete from project_2_expense_category where project_id='".$id."'", $dblink);
		if( isset( $expense_category_ids ) && count( $expense_category_ids ) > 0 ) {
			foreach( $expense_category_ids as $expense_category_id ) {
				doquery( "insert into project_2_expense_category(project_id, expense_category_id) values( '".$id."', '".$expense_category_id."' )", $dblink );
			}
		}
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
			$account_ids = array();
			$expense_category_ids = array();
			$sql="select * from project_2_account where project_id='".$id."'";
			$rs1 = doquery( $sql, $dblink );
			if( numrows( $rs1 ) > 0 ) {
				while( $r1 = dofetch( $rs1 ) ) {
					$account_ids[] = $r1[ "account_id" ];
				}
			}
			$expense_category="select * from project_2_expense_category where project_id='".$id."'";
			$rs2 = doquery( $expense_category, $dblink );
			if( numrows( $rs2 ) > 0 ) {
				while( $r2 = dofetch( $rs2 ) ) {
					$expense_category_ids[] = $r2[ "expense_category_id" ];
				}
			}
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