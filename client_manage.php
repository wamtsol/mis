<?php
include("include/db.php");
include("include/utility.php");
include("include/session.php");
include("include/paging.php");
define("APP_START", 1);
$tab_array=array("list", "add", "edit", "status", "delete", "bulk_action", "report", "print");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="list";
}

switch($tab){
	case 'add':
		include("modules/client/add_do.php");
	break;
	case 'edit':
		include("modules/client/edit_do.php");
	break;
	case 'delete':
		include("modules/client/delete_do.php");
	break;
	case 'status':
		include("modules/client/status_do.php");
	break;
	case 'bulk_action':
		include("modules/client/bulkactions.php");
	break;
	case 'print':
		include("modules/client/print_do.php");
	break;
}
?>
<?php include("include/header.php");?>
  <div class="container-widget row">
    <div class="col-md-12">
      <?php
		switch($tab){
			case 'list':
				include("modules/client/list.php");
			break;
			case 'add':
				include("modules/client/add.php");
			break;
			case 'edit':
				include("modules/client/edit.php");
			break;
			case 'report':
				include("modules/client/report.php");
			break;
		}
      ?>
    </div>
  </div>
</div>
<?php include("include/footer.php");?>