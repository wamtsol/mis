<?php
include("include/db.php");
include("include/utility.php");
include("include/session.php");
include("include/paging.php");
define("APP_START", 1);
$filename = 'employee_salary_payment_manage.php';
include("include/admin_type_access.php");
$tab_array=array("list", "add", "edit", "status", "delete", "bulk_action", "csv_report");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="list";
}
$q="";
$extra='';
$is_search=false;
if(isset($_GET["employee_id"])){
	$employee_id=slash($_GET["employee_id"]);
	$_SESSION["employee_salary_payment"]["list"]["employee_id"]=$employee_id;
}
if(isset($_SESSION["employee_salary_payment"]["list"]["employee_id"]))
	$employee_id=$_SESSION["employee_salary_payment"]["list"]["employee_id"];
else
	$employee_id="";
if($employee_id!=""){
	$extra.=" and employee_id='".$employee_id."'";
	$is_search=true;
}
$sql="select * from employee_salary_payment where 1 ".$extra." order by datetime_added desc";
switch($tab){
	case 'add':
		include("modules/employee_salary_payment/add_do.php");
	break;
	case 'edit':
		include("modules/employee_salary_payment/edit_do.php");
	break;
	case 'delete':
		include("modules/employee_salary_payment/delete_do.php");
	break;
	case 'status':
		include("modules/employee_salary_payment/status_do.php");
	break;
	case 'bulk_action':
		include("modules/employee_salary_payment/bulkactions.php");
	break;
	case 'csv_report':
		include("modules/employee_salary_payment/report_csv.php");
	break;
}
?>
<?php include("include/header.php");?>
	<div class="container-widget row">
    	<div class="col-md-12">
		  <?php
            switch($tab){
                case 'list':
                    include("modules/employee_salary_payment/list.php");
                break;
                case 'add':
                    include("modules/employee_salary_payment/add.php");
                break;
                case 'edit':
                    include("modules/employee_salary_payment/edit.php");
                break;
            }
          ?>
    	</div>
  	</div>
</div>
<?php include("include/footer.php");?>