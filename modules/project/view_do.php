<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$rs=doquery("select * from project where id='".slash($_GET["id"])."'",$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value) {
			$$key=htmlspecialchars(unslash($value));
		}
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