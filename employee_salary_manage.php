<?php
include("include/db.php");
include("include/utility.php");
include("include/session.php");
include("include/paging.php");
define("APP_START", 1);
$filename = 'employee_salary_manage.php';
include("include/admin_type_access.php");
$tab_array=array("list", "add", "edit", "status", "delete", "bulk_action", "employee_salary", "print","report","report_csv");
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
    $_SESSION["employee_salary"]["list"]["employee_id"]=$employee_id;
}
if(isset($_SESSION["employee_salary"]["list"]["employee_id"]))
    $employee_id=$_SESSION["employee_salary"]["list"]["employee_id"];
else
    $employee_id="";
if($employee_id!=""){
    $extra.=" and employee_id='".$employee_id."'";
    $is_search=true;
}
if(isset($_GET["date_from"])){
    $from=slash($_GET["date_from"]);
    $_SESSION["employee_salary"]["list"]["date_from"]=$from;
}
if(isset($_SESSION["employee_salary"]["list"]["date_from"]))
    $from=$_SESSION["employee_salary"]["list"]["date_from"];
else
    $from="";
if($from!=""){
    $ts = strtotime(date_dbconvert($from));
    $month = date("n", $ts)-1;
    $year = date("Y", $ts);
    $extra.=" and (month>='".$month."' and year='".$year."' or year>'".$year."')";
    $is_search=true;
}
if(isset($_GET["date_to"])){
    $to=slash($_GET["date_to"]);
    $_SESSION["employee_salary"]["list"]["date_to"]=$to;
}
if(isset($_SESSION["employee_salary"]["list"]["date_to"]))
    $to=$_SESSION["employee_salary"]["list"]["date_to"];
else
    $to="";
if($to!=""){
    $ts = strtotime(date_dbconvert($to));
    $month = date("n", $ts)-1;
    $year = date("Y", $ts);
    $extra.=" and (month<='".$month."' and year='".$year."' or year<'".$year."')";
    $is_search=true;
}
$sql="select * from employee_salary where 1 ".$extra." order by year desc, month desc";

switch($tab){
	case 'add':
		include("modules/employee_salary/add_do.php");
	break;
	case 'edit':
		include("modules/employee_salary/edit_do.php");
	break;
	case 'delete':
		include("modules/employee_salary/delete_do.php");
	break;
	case 'status':
		include("modules/employee_salary/status_do.php");
	break;
	case 'bulk_action':
		include("modules/employee_salary/bulkactions.php");
	break;
	case 'employee_salary':
		include("modules/employee_salary/employee_salary_do.php");
	break;
	case 'print':
		include("modules/employee_salary/print.php");
	break;
    case 'report':
        include("modules/employee_salary/report.php");
    break;
    case 'report_csv':
        include("modules/employee_salary/report_csv.php");
    break;
	
}
?>
<?php include("include/header.php");?>
	<div class="container-widget row">
    	<div class="col-md-12">
		  <?php
            switch($tab){
                case 'list':
                    include("modules/employee_salary/list.php");
                break;
                case 'add':
                    include("modules/employee_salary/add.php");
                break;
                case 'edit':
                    include("modules/employee_salary/edit.php");
                break;
				case 'employee_salary':
                    include("modules/employee_salary/employee_salary.php");
                break;
            }
          ?>
    	</div>
  	</div>
</div>
<?php include("include/footer.php");?>
