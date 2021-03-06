<?php
include("include/db.php");
include("include/utility.php");
include("include/session.php");
include("include/paging.php");
define("APP_START", 1);
$tab_array=array("list", "add", "edit", "status", "delete", "bulk_action", "print");
if(isset($_REQUEST["tab"]) && in_array($_REQUEST["tab"], $tab_array)){
	$tab=$_REQUEST["tab"];
}
else{
	$tab="list";
}
$q="";
$extra='';
$is_search=false;
if(isset($_GET["supplier_id"])){
	$supplier_id=slash($_GET["supplier_id"]);
	$_SESSION["supplier_payment"]["list"]["supplier_id"]=$supplier_id;
}
if(isset($_SESSION["supplier_payment"]["list"]["supplier_id"]))
	$supplier_id=$_SESSION["supplier_payment"]["list"]["supplier_id"];
else
	$supplier_id="";
if($supplier_id!=""){
	$extra.=" and supplier_id='".$supplier_id."'";
	$is_search=true;
}
if(isset($_GET["q"])){
	$q=slash($_GET["q"]);
	$_SESSION["supplier_payment"]["list"]["q"]=$q;
}
if(isset($_SESSION["supplier_payment"]["list"]["q"]))
	$q=$_SESSION["supplier_payment"]["list"]["q"];
else
	$q="";
if(!empty($q)){
	$extra.=" and b.supplier_name like '%".$q."%'";
	$is_search=true;
}
$sql="select a.*, b.supplier_name from supplier_payment a inner join supplier b on a.supplier_id = b.id where 1 ".$extra." order by datetime desc";
switch($tab){
	case 'add':
		include("modules/supplier_payment/add_do.php");
	break;
	case 'edit':
		include("modules/supplier_payment/edit_do.php");
	break;
	case 'delete':
		include("modules/supplier_payment/delete_do.php");
	break;
	case 'status':
		include("modules/supplier_payment/status_do.php");
	break;
	case 'bulk_action':
		include("modules/supplier_payment/bulkactions.php");
	break;
	case 'print':
		include("modules/supplier_payment/print_do.php");
	break;
}
?>
<?php include("include/header.php");?>
  <div class="container-widget row">
    <div class="col-md-12">
      <?php
		switch($tab){
			case 'list':
				include("modules/supplier_payment/list.php");
			break;
			case 'add':
				include("modules/supplier_payment/add.php");
			break;
			case 'edit':
				include("modules/supplier_payment/edit.php");
			break;
		}
      ?>
    </div>
  </div>
</div>
<?php include("include/footer.php");?>