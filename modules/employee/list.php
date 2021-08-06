<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-header">
	<h1 class="title">Manage Employee</h1>
  	<ol class="breadcrumb">
    	<li class="active">
        	All Employees
        </li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> 
        	<a href="employee_manage.php?tab=add" class="btn btn-light editproject">Add New employee</a> 
            <a id="topstats" class="btn btn-light" href="#"><i class="fa fa-search"></i></a> 
    	</div> 
    </div> 
</div>
<ul class="topstats clearfix search_filter"<?php if($is_search) echo ' style="display: block"';?>>
    <li class="col-xs-12 col-lg-12 col-sm-12">
    	<div>
        	<form class="form-horizontal" action="" method="get">
                <div class="col-sm-2 margin-btm-5">
                	<select name="project_id" id="project_id" class="custom_select">
                        <option value=""<?php echo ($project_id=="")? " selected":"";?>>All employees</option>
                        <option value="0"<?php echo ($project_id=="0")? " selected":"";?>>Administrative employees</option>
                        <?php
                            $res=doquery("select * from project order by start_date desc",$dblink);
                            if(numrows($res)>=0){
                                while($rec=dofetch($res)){
                                ?>
                                <option value="<?php echo $rec["id"]?>" <?php echo($project_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["title"])?></option>
                            	<?php
                                }
                            }	
                        ?>
                    </select>
                </div>
                <div class="col-sm-3">
                  <input type="text" title="Enter String" value="<?php echo $q;?>" name="q" id="search" class="form-control" >  
                </div>
                <div class="col-sm-2 col-xs-2 text-left">
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
                <th width="3%" class="text-center">S.No</th>
                <th class="text-center" width="2%"><div class="checkbox checkbox-primary">
                    <input type="checkbox" id="select_all" value="0" title="Select All Records">
                    <label for="select_all"></label></div></th>
                <th width="15%">Project</th>
                <th width="10%">Designation</th>
                <th width="15%">Name</th>
                <th width="15%">Father Name</th>
                <th width="8%">Contact</th>
                <th width="10%">CNIC</th>
                <th width="8%">Salary</th>
                <th width="5%" class="text-center">Status</th>
                <th width="15%" class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $rs=show_page($rows, $pageNum, $sql);
            if(numrows($rs)>0){
                $sn=1;
                $total = 0;
                while($r=dofetch($rs)){             
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $sn;?></td>
                        <td class="text-center"><div class="checkbox margin-t-0 checkbox-primary">
                            <input type="checkbox" name="id[]" id="<?php echo "rec_".$sn?>"  value="<?php echo $r["id"]?>" title="Select Record" />
                            <label for="<?php echo "rec_".$sn?>"></label></div>
                        </td>
                        <td>
                            <?php
                            $project = array();
                            $rs2 =doquery("select title from employee_2_project a inner join project b on a.project_id=b.id where employee_id='".$r["id"]."'", $dblink);
                            if( numrows( $rs2 ) > 0 ) {
                                while( $r2 = dofetch( $rs2 ) ) {
                                    $project[] = $r2[ "title" ];
                                }
                            }
                            echo implode( ", ", $project );
                            ?>
                        </td>
                        <td><?php echo get_field( unslash($r["designation_id"]), "designation", "title" ); ?></td>
                        <td><?php echo unslash($r["name"]); ?></td>
                        <td><?php echo unslash($r["father_name"]); ?></td>
                        <td><?php echo unslash($r["contact_number"]); ?></td>
                        <td><?php echo unslash($r["cnic_number"]); ?></td>
                        <th><a href="employee_manage.php?tab=salary&id=<?php echo $r["id"]?>"><?php
                                $balance = get_user_balance( $r[ "id" ] );
                                $total += $balance;
                                echo curr_format( $balance );
                                ?></a></th>
                        <td class="text-center">
                            <a href="employee_manage.php?id=<?php echo $r['id'];?>&tab=status&s=<?php echo ($r["status"]==0)?1:0;?>">
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
                                <a style="font-size: 16px;" href="employee_manage.php?tab=letter&id=<?php echo $r['id'];?>"><i class="fa fa-print" aria-hidden="true"></i></a>
                            	<a href="employee_manage.php?tab=edit&id=<?php echo $r['id'];?>"><img title="Edit Record" alt="Edit" src="images/edit.png"></a>&nbsp;&nbsp;
                            	<a onclick="return confirm('Are you sure you want to delete')" href="employee_manage.php?id=<?php echo $r['id'];?>&amp;tab=delete"><img title="Delete Record" alt="Delete" src="images/delete.png"></a>
                        </td>
                    </tr>  
                    <?php 
                    $sn++;
                }
                ?>
                <tr>
                    <th colspan="8" class="text-right">Total</th>
                    <th><?php echo curr_format($total)?></th>
                    <th colspan="2">&nbsp;</th>
                </tr>
                <tr>
                    <td colspan="6" class="actions">
                        <select name="bulk_action" class="" id="bulk_action" title="Choose Action">
                            <option value="null">Bulk Action</option>
                            <option value="delete">Delete</option>
                            <option value="statuson">Set Status On</option>
                            <option value="statusof">Set Status Off</option>
                        </select>
                        <input type="button" name="apply" value="Apply" id="apply_bulk_action" class="btn btn-light" title="Apply Action"  />
                    </td>
                    <td colspan="5" class="paging" title="Paging" align="right"><?php echo pages_list($rows, "employee", $sql, $pageNum)?></td>
                </tr>
                <?php	
            }
            else{	
                ?>
                <tr>
                    <td colspan="11"  class="no-record">No Result Found</td>
                </tr>
                <?php
            }
            ?>
        </tbody>
     </table>
</div>
