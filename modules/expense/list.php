<?php

if(!defined("APP_START")) die("No Direct Access");

?>

<div class="page-header">

	<h1 class="title">Manage Expense</h1>

  	<ol class="breadcrumb">

    	<li class="active">

        	<?php

            if( !isset( $_SESSION["expense"]["list"]["project_id"] ) || $_SESSION["expense"]["list"]["project_id"] == "" ) {

				echo "All Expenses";

			}

			else if( $_SESSION["expense"]["list"]["project_id"] == "0" ) {

				echo "Administrative Expenses";

			}

			else {

				echo "Project: ".get_field( $_SESSION["expense"]["list"]["project_id"], "project" );

			}

			?>

        </li>

  	</ol>

  	<div class="right">

    	<div class="btn-group" role="group" aria-label="..."> 

        	<a href="expense_manage.php?tab=add" class="btn btn-light editproject">Add New Expense</a> 

            <a id="topstats" class="btn btn-light" href="#"><i class="fa fa-search"></i></a> 

            <a class="btn print-btn" href="expense_manage.php?tab=print"><i class="fa fa-print" aria-hidden="true"></i></a>
            <a class="btn print-btn" href="expense_manage.php?tab=csv_report">CSV</a>
    	</div> 

    </div> 

</div>

<ul class="topstats clearfix search_filter"<?php if($is_search) echo ' style="display: block"';?>>

    <li class="col-xs-12 col-lg-12 col-sm-12">

    	<div>

        	<form class="form-horizontal" action="" method="get">

                <div class="col-sm-2 margin-btm-5">

                	<select name="project_id" id="project_id" class="custom_select">
                        <?php if($_SESSION["logged_in_admin"]["admin_type_id"]==1){?>
                        <option value=""<?php echo ($project_id=="")? " selected":"";?>>All Expenses</option>

                        <option value="0"<?php echo ($project_id=="0")? " selected":"";?>>Administrative Expenses</option>
                        <?php }?>
                        <?php

                            $res=doquery("select a.* from project a left join admin_2_project b on a.id = b.project_id where status=1 ".$adminId." order by title", $dblink);

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

                <div class="col-sm-2 ">

                	<select name="expense_category_id" id="expense_category_id" class="custom_select">

                        <option value=""<?php echo ($expense_category_id=="")? " selected":"";?>>Select Expense Category</option>

                        <?php

                            $res=doquery("select * from expense_category order by title",$dblink);

                            if(numrows($res)>=0){

                                while($rec=dofetch($res)){

                                ?>

                                <option value="<?php echo $rec["id"]?>"<?php echo($expense_category_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["title"])?></option>

                            	<?php

                                }

                            }	

                        ?>

                    </select>

                </div>

                <div class="col-sm-2 ">

                	<select name="account_id" id="account_id" class="custom_select">

                        <option value=""<?php echo ($account_id=="")? " selected":"";?>>Select Account</option>

                        <?php

                            $res=doquery("select * from account order by title",$dblink);

                            if(numrows($res)>=0){

                                while($rec=dofetch($res)){

                                ?>

                                <option value="<?php echo $rec["id"]?>" <?php echo($account_id==$rec["id"])?"selected":"";?>><?php echo unslash($rec["title"])?></option>

                            	<?php

                                }

                            }	

                        ?>

                    </select>

                </div>

                <div class="col-sm-2 margin-btm-5">

                  <input type="text" title="Enter Date From" value="<?php echo $date_from;?>" placeholder="Date From" name="date_from" id="date_from" class="form-control date-picker" autocomplete="off">  

                </div>

                <div class="col-sm-2 margin-btm-5">

                  <input type="text" title="Enter Date To" value="<?php echo $date_to;?>" placeholder="Date To" name="date_to" id="date_to" class="form-control date-picker" autocomplete="off">  

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

                <th width="12%">Date/Time</th>

                <th width="10%">Project</th>

                <th width="13%">Expense Category</th>

                <th width="12%">Payment Account</th>

                <th width="20%">Details</th>

                <th width="8%">Amount</th>

                <th width="10%">Cheque Number</th>

                <th width="10%">Added By</th>

                <th width="5%" class="text-center">Status</th>

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

                        <td><?php echo datetime_convert($r["datetime_added"]); ?></td>

                        <td><?php echo get_field( unslash($r["project_id"]), "project", "title" ); ?></td>

                        <td><?php echo get_field( unslash($r["expense_category_id"]), "expense_category", "title" ); ?></td>

                        <td><?php echo get_field( unslash($r["account_id"]), "account", "title" ); ?></td>

                        <td><?php echo unslash($r["details"]); ?></td>

                        <td><?php echo curr_format(unslash($r["amount"])); ?></td>

                        <td><?php echo unslash($r["cheque_number"]); ?></td>

                        <td><?php echo get_field( unslash($r["added_by"]), "admin", "username" ); ?></td>

                        <td class="text-center">

                            <a href="expense_manage.php?id=<?php echo $r['id'];?>&tab=status&s=<?php echo ($r["status"]==0)?1:0;?>">

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

                            	<a href="expense_manage.php?tab=edit&id=<?php echo $r['id'];?>"><img title="Edit Record" alt="Edit" src="images/edit.png"></a>&nbsp;&nbsp;

                                <a href="expense_manage.php?tab=voucher&id=<?php echo $r['id'];?>"><img title="Edit Record" alt="Edit" src="images/view.png"></a>&nbsp;&nbsp;

                            	<a onclick="return confirm('Are you sure you want to delete')" href="expense_manage.php?id=<?php echo $r['id'];?>&amp;tab=delete"><img title="Delete Record" alt="Delete" src="images/delete.png"></a>

                        </td>

                    </tr>  

                    <?php 

                    $sn++;

                }

                ?>

                <tr>

                    <th colspan="7" class="text-right">Total:</th>

                    <th><?php echo curr_format($total_amount);?></th>

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

                    <td colspan="6" class="paging" title="Paging" align="right"><?php echo pages_list($rows, "expense", $sql, $pageNum)?></td>

                </tr>

                <?php	

            }

            else{	

                ?>

                <tr>

                    <td colspan="12"  class="no-record">No Result Found</td>

                </tr>

                <?php

            }

            ?>

        </tbody>

     </table>

</div>