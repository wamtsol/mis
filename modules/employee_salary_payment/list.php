<?php
if(!defined("APP_START")) die("No Direct Access");

?>
<div class="page-header">
	<h1 class="title">Manage Salary Payment</h1>
  	<ol class="breadcrumb">
    	<li class="active">Employee Salary Payment</li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> 
        	<a href="employee_salary_payment_manage.php?tab=add" class="btn btn-light editproject">Add New Salary Payment</a> 
            <a id="topstats" class="btn btn-light" href="#"><i class="fa fa-search"></i></a> 
            <a class="btn print-btn" href="employee_salary_payment_manage.php?tab=csv_report">CSV</a>
    	</div> 
    </div> 
</div>
<ul class="topstats clearfix search_filter"<?php if($is_search) echo ' style="display: block"';?>>
    <li class="col-xs-12 col-lg-12 col-sm-12">
    	<div>
        	<form class="form-horizontal" action="" method="get">
            	<div class="col-sm-4 margin-btm-5">
                	<select name="employee_id" id="employee_id" class="custom_select">
                        <option value=""<?php echo ($employee_id=="")? " selected":"";?>>Select Employee</option>
                        <?php
							$res=doquery("select * from employee where status = 1 order by name",$dblink);
							if(numrows($res)>=0){
								while($rec=dofetch($res)){
								?>
								<option value="<?php echo $rec["id"]?>" <?php echo($employee_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["name"])?></option>
								<?php
								}
							}	
                        ?>
                    </select>
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
                <th width="5%" class="text-center">S.No</th>
                <th class="text-center" width="5%"><div class="checkbox checkbox-primary">
                    <input type="checkbox" id="select_all" value="0" title="Select All Records">
                    <label for="select_all"></label></div></th>
                <th width="25%">Employee</th>
                <th width="10%">Project</th>
                <th width="15%">Datetime</th>
                <th width="15%" class="text-right">Amount</th>
                <th width="15%">Cheque Number</th>
                <th width="15%">Paid By</th>
                <th width="10%" class="text-center">Status</th>
                <th width="10%" class="text-center">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $total_amount = 0;
            
            $rs=show_page($rows, $pageNum, $sql);
            if(numrows($rs)>0){
                $sn=1;
                while($r=dofetch($rs)){
                    $total_amount += $r["amount"];          
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $sn;?></td>
                        <td class="text-center"><div class="checkbox margin-t-0 checkbox-primary">
                            <input type="checkbox" name="id[]" id="<?php echo "rec_".$sn?>"  value="<?php echo $r["id"]?>" title="Select Record" />
                            <label for="<?php echo "rec_".$sn?>"></label></div>
                        </td>
                        <td><?php if($r["employee_id"]==0) echo "Default"; else echo get_field($r["employee_id"], "employee","name");?></td>
                        <td><?php echo get_field($r["project_id"], "project","title");?></td>
                        <td><?php echo datetime_convert($r["datetime_added"]); ?></td>
                        <td class="text-right"><?php echo curr_format(unslash($r["amount"])); ?></td>
                        <td><?php echo unslash($r["cheque_number"]); ?></td>
                        <td><?php if($r["account_id"]==0) echo "Default"; else echo get_field($r["account_id"], "account","title");?></td>
                        <td class="text-center">
                            <a href="employee_salary_payment_manage.php?id=<?php echo $r['id'];?>&tab=status&s=<?php echo ($r["status"]==0)?1:0;?>">
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
                            	<a href="employee_salary_payment_manage.php?tab=edit&id=<?php echo $r['id'];?>"><img title="Edit Record" alt="Edit" src="images/edit.png"></a>&nbsp;&nbsp;
                            	<a onclick="return confirm('Are you sure you want to delete')" href="employee_salary_payment_manage.php?id=<?php echo $r['id'];?>&amp;tab=delete"><img title="Delete Record" alt="Delete" src="images/delete.png"></a>
                        </td>
                    </tr>  
                    <?php 
                    $sn++;
                }
                ?>
                <tr>
                    <th class="text-right" colspan="5">Total</th>
                    <th class="text-right"><?php echo curr_format($total_amount);?></th>
                    <th colspan="4"></th>
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
                    <td colspan="4" class="paging" title="Paging" align="right"><?php echo pages_list($rows, "employee_salary_payment", $sql, $pageNum)?></td>
                </tr>
                <?php	
            }
            else{	
                ?>
                <tr>
                    <td colspan="10"  class="no-record">No Result Found</td>
                </tr>
                <?php
            }
            ?>
        </tbody>
     </table>
</div>
