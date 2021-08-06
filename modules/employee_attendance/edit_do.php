<?php
if(!defined("APP_START")) die("No Direct Access");
if(isset($_POST["employee_attendance_save"])){
	$id = slash( $_POST[ "id" ] );
	$date = date_dbconvert( $_POST[ "date" ] );
	$employees = json_decode($_POST["employees"]);
	foreach( $employees as $employee ) {
		if( $employee->status ) {
			if( numrows( doquery( "select * from employee_attendance where employee_id='".$employee->id."' and date='".$date."'", $dblink ) ) == 0 ) {
				doquery( "insert into employee_attendance(employee_id, date, checked_in) values( '".$employee->id."', '".$date."', NOW())", $dblink );
			}
		}
		else {
			$rs = doquery( "select * from employee_attendance where employee_id='".$employee->id."' and date='".$date."'", $dblink );
			if( numrows( $rs ) > 0 ) {
				$r = dofetch( $rs );
				doquery( "delete from employee_attendance where id='".$r[ "id" ]."'", $dblink );
			}
		}
	}
	$employee_attendance = doquery( "select * from employee_daily_attendance a inner join admin b on a.taken_by = b.id where date='". $date."'", $dblink );
	$status = 1;
	if( numrows( $employee_attendance ) > 0 ) {
		$employee_attendance = dofetch( $employee_attendance );
		doquery( "update employee_daily_attendance set status='".$status."' where id='".$employee_attendance["id"]."'", $dblink );
	}
	else {
		doquery( "insert into employee_daily_attendance(date, taken_by, status) values('".$date."', '".$_SESSION[ "logged_in_admin" ][ "id" ]."', '".$status."')", $dblink );
	}
	header("Location: employee_attendance_manage.php?tab=list&msg=".url_encode( "Attendance has been taken successfully." ));
	die;
}
/*----------------------------------------------------------------------------------*/
if(isset($_GET["id"]) && $_GET["id"]!=""){
	$sql="select * from employee_daily_attendance where id = '".slash($_GET["id"])."'";
	$rs=doquery($sql,$dblink);
	if(numrows($rs)>0){
		$r=dofetch($rs);
		foreach($r as $key=>$value)
			$$key=htmlspecialchars(unslash($value));
		$date = slash( $_GET[ "date" ] );
		//$current_academic_year = current_academic_year();
	}
	else{
		header("Location: employee_attendance_manage.php?tab=list");
		die;
	}
}
else{
	header("Location: employee_attendance_manage.php?tab=list");
	die;
}