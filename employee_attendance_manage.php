<?php
include("include/db.php");
include("include/utility.php");
include("include/session.php");
include("include/paging.php");
define("APP_START", 1);
$filename = 'employee_attendance_manage.php';
include("include/admin_type_access.php");
$tab_array=array("list", "edit", "add", "delete", "employee_list");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="list";
}

switch($tab){
	case 'add':
		include("modules/employee_attendance/add_do.php");
	break;
	case 'edit':
		include("modules/employee_attendance/edit_do.php");
	break;
	case 'delete':
		include("modules/employee_attendance/delete_do.php");
	break;
	case "employee_list":
		$id = slash( $_REQUEST[ "id" ] );
		$date = slash( $_REQUEST[ "date" ] );
		//$current_academic_year = current_academic_year();
		$employee_list = array();
		$employee_attendance = doquery( "select * from employee_daily_attendance a inner join admin b on a.taken_by = b.id where date='".date_dbconvert( $date )."'", $dblink );
		$employees = doquery( "select * from employee where status=1 order by name", $dblink );
		if( numrows( $employees ) > 0 ) {
			while( $employee = dofetch( $employees ) ){
				if( numrows($employee_attendance) == 0 || numrows( doquery( "select * from employee_attendance where employee_id='".$employee[ "id" ]."' and date='".date_dbconvert( $date )."'", $dblink ) ) > 0 ) {
					$status = true;
				}
				else {
					$status = false;
				}
				$employee_list[] = array(
					"id" => $employee[ "id" ],
					"name" => unslash( $employee[ "name" ] ),
					"email" => unslash( $employee[ "email" ] ),
					"status" => $status
				);
			}
		}
		echo json_encode($employee_list);
		die;
	break;
}
?>
<?php include("include/header.php");?>
	<div class="container-widget row">
    	<div class="col-md-12">
		  <?php
            switch($tab){
                case 'list':
                    include("modules/employee_attendance/list.php");
                break;
				case 'add':
                    include("modules/employee_attendance/add.php");
                break;
                case 'edit':
                    include("modules/employee_attendance/edit.php");
                break;
            }
          ?>
    	</div>
  	</div>
</div>
<?php include("include/footer.php");?>
