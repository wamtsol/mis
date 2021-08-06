<?php
if(!defined("APP_START")) die("No Direct Access");
$extra='';
$is_search=false;
if(isset($_GET["date"])){
	$date=slash($_GET["date"]);
	$_SESSION["employee_attendance_manage"]["date"]=$date;
}
if(isset($_SESSION["employee_attendance_manage"]["date"]))
	$date=$_SESSION["employee_attendance_manage"]["date"];
else
	$date=date("d/m/Y");
if(!empty($date)){
	$extra=" and date>='".date("Y/m/d H:i:s", strtotime(date_dbconvert($date)))."' and date<'".date("Y/m/d H:i:s", strtotime(date_dbconvert($date))+3600*24)."'";
	$is_search=true;
}
?>
<div class="page-header">
	<h1 class="title">Employee Attendance</h1>
  	<ol class="breadcrumb">
    	<li class="active">Employee's Attendance</li>
  	</ol>
    <div class="right">
    	<div class="btn-group" role="group" aria-label="..."> 
        	<a href="employee_attendance_manage.php?tab=add" class="btn btn-light editproject">Add New Employee Attendance</a> 
        	<a id="topstats" class="btn btn-light" href="#"><i class="fa fa-search"></i></a>
        </div>
  	</div>
</div>
<ul class="topstats clearfix search_filter"<?php if($is_search) echo ' style="display: block"';?>>
	<li class="col-xs-12 col-lg-12 col-sm-12">
        <div>
        	<form class="form-horizontal" action="" method="get">
                <div class="col-sm-4">
                  <input type="text" title="Enter String" value="<?php echo $date;?>" name="date" id="search" class="date-picker form-control" >
                </div>
                <div class="col-sm-4 text-left">
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
                <th width="5%" class="text-center">S.No</th>
                <th class="text-center" width="5%"><div class="checkbox checkbox-primary">
                    <input type="checkbox" id="select_all" value="0" title="Select All Records">
                    <label for="select_all"></label></div></th>
                <th width="15%">Date</th>
                <th width="35%">Present Employee</th>
                <th width="35%">Absent Employee</th>
                <th width="5%" class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $sql="select * from employee_daily_attendance where 1 $extra";
            $rs=show_page($rows, $pageNum, $sql);
            if(numrows($rs)>0){
                $sn=1;
                while($r=dofetch($rs)){
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $sn;?></td>
                        <td class="text-center"><div class="checkbox margin-t-0 checkbox-primary">
                            <input type="checkbox" name="id[]" id="<?php echo "rec_".$sn?>"  value="<?php echo $r["id"]?>" title="Select Record" />
                            <label for="<?php echo "rec_".$sn?>"></label></div>
                        </td>
                        <td><?php echo date_convert($r["date"]);?> </td>
                        <td><?php
                        	$att=dofetch(doquery( "select group_concat( name SEPARATOR '<br>') as present from employee_attendance a left join admin b on a.employee_id = b.id where date='".$r[ "date" ]."'", $dblink ));
							echo '<i class="fa fa-check" style="color: #0f0"></i>' .unslash($att["present"]);
						?></td>
                        <td><?php
                        	$att=dofetch(doquery( "select group_concat( name SEPARATOR '<br>') as absent from admin where id not in( select employee_id from employee_attendance where date='".$r[ "date" ]."' ) and status=1", $dblink ));
							echo '<i class="fa fa-close" style="color: #f00"></i>' .unslash($att["absent"]);
						?></td>
                        <td class="text-center">
                            <a href="employee_attendance_manage.php?tab=edit&id=<?php echo $r['id'];?>&date=<?php echo date_convert($r["date"])?>"><img title="Edit Record" alt="Edit" src="images/edit.png"></a>&nbsp;&nbsp;
                            <a onclick="return confirm('Are you sure you want to delete')" href="employee_attendance_manage.php?id=<?php echo $r['id'];?>&amp;tab=delete&date=<?php echo date_convert($r["date"])?>"><img title="Delete Record" alt="Delete" src="images/delete.png"></a>
                        </td>
                    </tr>  
                    <?php 
                    $sn++;
                }
                ?>
                <tr>
                    <td colspan="4" class="actions">
                        <select name="bulk_action" id="bulk_action" title="Choose Action">
                            <option value="null">Bulk Action</option>
                            <option value="delete">Delete</option>
                            <option value="statuson">Set Status On</option>
                            <option value="statusof">Set Status Off</option>
                        </select>
                        <input type="button" name="apply" value="Apply" id="apply_bulk_action" class="btn btn-light" title="Apply Action"  />
                    </td>
                    <td colspan="2" class="paging" title="Paging" align="right"><?php echo pages_list($rows, "employee_daily_attendance", $sql, $pageNum)?></td>
                </tr>
                <?php	
            }
            else{	
                ?>
                <tr>
                    <td colspan="6"  class="no-record">No Result Found</td>
                </tr>
                <?php
            }
            ?>
        </tbody>
    </table>
</div>