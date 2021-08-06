<?php
include("include/db.php");
include("include/utility.php");
include("include/session.php");
include("include/paging.php");
define("APP_START", 1);
$filename = 'employee_manage.php';
include("include/admin_type_access.php");
$tab_array=array("list", "add", "edit", "status", "delete", "bulk_action", "letter", "salary");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="list";
}
$extra='';
$is_search=false;
if(isset($_GET["q"])){
	$q=slash($_GET["q"]);
	$_SESSION["employee"]["list"]["q"]=$q;
}
if(isset($_SESSION["employee"]["list"]["q"]))
	$q=$_SESSION["employee"]["list"]["q"];
else
	$q="";
if(!empty($q)){
	$extra.=" and name like '%".$q."%'";
	$is_search=true;
}
if(isset($_GET["project_id"])){
	$_SESSION["employee"]["list"]["project_id"]=$_GET["project_id"];
}
if(isset($_SESSION["employee"]["list"]["project_id"]))
	$project_id=$_SESSION["employee"]["list"]["project_id"];
else
	$project_id="";
if( $project_id!="" ){
	$extra.=" and b.project_id='".$project_id."'";
	$is_search=true;
}

$sql="select a.* from employee a left join employee_2_project b on b.employee_id = a.id where 1 $extra order by name";
switch($tab){
	case 'add':
		include("modules/employee/add_do.php");
	break;
	case 'edit':
		include("modules/employee/edit_do.php");
	break;
	case 'delete':
		include("modules/employee/delete_do.php");
	break;
	case 'status':
		include("modules/employee/status_do.php");
	break;
	case 'bulk_action':
		include("modules/employee/bulkactions.php");
	break;
	case 'letter':
		include("modules/employee/letter.php");
	break;
    case 'salary':
        include("modules/employee/salary.php");
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
                    include("modules/employee/list.php");
                break;
                case 'add':
                    include("modules/employee/add.php");
                break;
                case 'edit':
                    include("modules/employee/edit.php");
                break;
            }
          ?>
    	</div>
  	</div>
</div>
<?php include("include/footer.php");?>
