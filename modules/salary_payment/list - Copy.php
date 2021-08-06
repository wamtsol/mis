<?php
if(!defined("APP_START")) die("No Direct Access");
$q="";
$extra='';
$is_search=false;
if(isset($_GET["category_id"])){
	$category_id=slash($_GET["category_id"]);
	$_SESSION["salary"]["list"]["category_id"]=$category_id;
}
if(isset($_SESSION["salary"]["list"]["category_id"]))
	$category_id=$_SESSION["salary"]["list"]["category_id"];
else
	$category_id="";
if($category_id!=""){
	$extra.=" and category_id='".$category_id."'";
	$is_search=true;
}
if( isset($_GET["date"]) ){
	$_SESSION["salary"]["list"]["date"] = $_GET["date"];
}
if(isset($_SESSION["salary"]["list"]["date"]) && !empty($_SESSION["salary"]["list"]["date"])){
	$date = $_SESSION["salary"]["list"]["date"];
}
else{
	$date = "";
}
if( !empty($date) ){
	$extra=" and datetime_added>='".date("Y/m/d H:i:s", strtotime(date_dbconvert($date)))."' and datetime_added<'".date("Y/m/d H:i:s", strtotime(date_dbconvert($date))+3600*24)."'";
	$is_search=true;
}
?>
<div class="page-header">
	<h1 class="title">Manage Salary</h1>
  	<ol class="breadcrumb">
    	<li class="active">Employee Salary</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> 
        	<a href="salary_manage.php?tab=add" class="btn btn-light editproject">Add New Record</a> 
            <a id="topstats" class="btn btn-light" href="#"><i class="fa fa-search"></i></a> 
    	</div> 
    </div> 
</div>
<ul class="topstats clearfix search_filter"<?php if($is_search) echo ' style="display: block"';?>>
    <li class="col-xs-12 col-lg-12 col-sm-12">
    	<div>
        	<form class="form-horizontal" action="" method="get">
            	<div class="col-sm-4 margin-btm-5">
                	<select name="category_id" id="category_id" class="custom_select">
                        <option value=""<?php echo ($category_id=="")? " selected":"";?>>Select Category</option>
                        <?php
							$res=doquery("select * from category order by title",$dblink);
							if(numrows($res)>=0){
								while($rec=dofetch($res)){
								?>
								<option value="<?php echo $rec["id"]?>" <?php echo($category_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["title"])?></option>
								<?php
								}
							}	
                        ?>
                    </select>
                </div>
                <div class="col-sm-3 margin-btm-5">
                  <input class="form-control search-query date-picker" value="<?php echo $date;?>" name="date" id="search" type="text">
                </div>
                <div class="col-sm-3 text-left margin-btm-5">
                    <input type="button" class="btn btn-danger btn-l reset_search" value="Reset" alt="Reset Record" title="Reset Record" />
                    <input type="submit" class="btn btn-default btn-l" value="Search" alt="Search Record" title="Search Record" />
                </div>
            </form>
        </div>
  	</li>
</ul>
<div class="panel-body table-responsive">
	<table class="table table-hover list">
    	<thead>
            <tr>
                <th width="5%">S.No</th>
                <th class="text-center" width="5%"><div class="checkbox checkbox-primary">
                    <input type="checkbox" id="select_all" value="0" title="Select All Records">
                    <label for="select_all"></label></div></th>
                <th width="20%">Category</th>
                <th width="20%">Date/Time</th>
                <th width="10%">Amount</th>
                <th width="10%">Payment</th>
                <th width="10%">Added By</th>
                <th width="10%" class="text-center">Status</th>
                <th width="10%" class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $sql="select * from expense where 1 $extra";
            $rs=show_page($rows, $pageNum, $sql);
            if(numrows($rs)>0){
                $sn=1;
                while($r=dofetch($rs)){             
                    ?>
                    <tr>
                        <td><?php echo $sn;?></td>
                        <td class="text-center"><div class="checkbox margin-t-0 checkbox-primary">
                            <input type="checkbox" name="id[]" id="<?php echo "rec_".$sn?>"  value="<?php echo $r["id"]?>" title="Select Record" />
                            <label for="<?php echo "rec_".$sn?>"></label></div>
                        </td>
                        <td><?php if($r["category_id"]==0) echo "Default"; else echo get_field($r["category_id"], "category","title");?></td>
                        <td><?php echo datetime_convert($r["datetime_added"]); ?></td>
                        <td><?php echo curr_format(unslash($r["amount"])); ?></td>
                        <td><?php echo curr_format(unslash($r["payment"])); ?></td>
                        <td><?php echo get_field( unslash($r["added_by"]), "admin", "username" ); ?></td>
                        <td class="text-center">
                            <a href="salary_manage.php?id=<?php echo $r['id'];?>&tab=status&s=<?php echo ($r["status"]==0)?1:0;?>">
                                <?php
                                if($r["status"]==0){
                                    ?>
                                    <img src="images/offstatus.png" alt="Off" title="Set Status On">
                                    <?php
                                }
                                else{
                                    ?>
                                    <img src="images/onstatus.png" alt="On" title="Set Status Off">
                                    <?php
                                }
                                ?>
                            </a>
                        </td>
                        <td class="text-center">
                            	<a href="salary_manage.php?tab=edit&id=<?php echo $r['id'];?>"><img title="Edit Record" alt="Edit" src="images/edit.png"></a>&nbsp;&nbsp;
                            	<a onclick="return confirm('Are you sure you want to delete')" href="salary_manage.php?id=<?php echo $r['id'];?>&amp;tab=delete"><img title="Delete Record" alt="Delete" src="images/delete.png"></a>
                        </td>
                    </tr>  
                    <?php 
                    $sn++;
                }
                ?>
                <tr>
                    <td colspan="5" class="actions">
                        <select name="bulk_action" class="" id="bulk_action" title="Choose Action">
                            <option value="null">Bulk Action</option>
                            <option value="delete">Delete</option>
                            <option value="statuson">Set Status On</option>
                            <option value="statusof">Set Status Off</option>
                        </select>
                        <input type="button" name="apply" value="Apply" id="apply_bulk_action" class="btn btn-light" title="Apply Action"  />
                    </td>
                    <td colspan="4" class="paging" title="Paging" align="right"><?php echo pages_list($rows, "salary", $sql, $pageNum)?></td>
                </tr>
                <?php	
            }
            else{	
                ?>
                <tr>
                    <td colspan="9"  class="no-record">No Result Found</td>
                </tr>
                <?php
            }
            ?>
        </tbody>
     </table>
</div>