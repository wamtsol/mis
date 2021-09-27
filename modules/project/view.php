<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-header">
	<h1 class="title"><?php echo $title;?></h1>
  	<ol class="breadcrumb">
    	<li class="active"><?php echo get_field( $client_id, "client", "client_name" );?></li>
  	</ol>
  	<div class="right">
    	<div class="btn-group" role="group" aria-label="..."> <a href="project_manage.php" class="btn btn-light editproject">Back to List</a> </div>
  	</div>
</div>        	
<div class="row clearfix">
    <div class="col-md-3">
        <table class="table table-hover list">
            <tr>
                <th colspan="2">Summary</th>
            </tr>
            <tr>
                <td>Estimated Revenue:</td>
                <th class="text-right"><?php echo curr_format( $expected_revenue )?></th>
            </tr>
            <tr>
                <td>Payment Received:</td>
                <th class="text-right"><?php
                    $sum = dofetch( doquery( "select sum(amount) as sum from project_payment where project_id = '".$id."' and status = 1", $dblink ) );
                    $project_balance = $sum[ "sum" ];
                    echo curr_format( $sum[ "sum" ] );
				?></th>
            </tr>
            <tr>
                <th>Expenses</th>
            	<th class="text-right"><?php
                	$sum = dofetch( doquery( "select sum(amount) as sum from expense where project_id = '".$id."' and status = 1", $dblink ) );
                    $sum_salary = dofetch( doquery( "select sum(amount) as sum from salary where project_id = '".$id."' and status = 1", $dblink ) );
                    $project_balance -= $sum[ "sum" ]+$sum_salary[ "sum" ];
					echo curr_format( $sum[ "sum" ]+$sum_salary[ "sum" ] );
				?></th>
            </tr>
            <?php
            $rs = doquery( "select sum(amount) as sum, ifnull( title, 'Unknown' ) as expense from expense a left join expense_category b on a.expense_category_id = b.id where project_id = '".$id."' and a.status = 1 group by expense_category_id", $dblink );
			if( numrows( $rs ) > 0 ) {
				while( $r = dofetch( $rs ) ) {
					?>
					<tr>
                        <td><?php echo unslash( $r[ "expense" ] )?>:</td>
                        <th class="text-right"><?php echo curr_format( $r[ "sum" ] )?></th>
                    </tr>
					<?php
				}
			}
			?>
            <?php
            $rs = doquery( "select sum(amount) as sum, ifnull( name, 'Unknown' ) as employee from employee_salary a left join employee b on a.employee_id = b.id where project_id = '".$id."' and a.status = 1 group by employee_id", $dblink );
            if( numrows( $rs ) > 0 ) {
                while( $r = dofetch( $rs ) ) {
                    ?>
                    <tr>
                        <td><?php echo unslash( $r[ "employee" ] )?> (Salary):</td>
                        <th class="text-right"><?php echo curr_format( $r[ "sum" ] )?></th>
                    </tr>
                    <?php
                }
            }
            ?>
            <tr>
                <th colspan="2">Accounts</th>
            </tr>
            <?php
            $total_balance = 0;
			foreach( $account_ids as $account_id ) {
			    $balance = get_account_balance( $account_id, '', $id );
			    $total_balance += $balance;
				?>
				<tr>
					<td><?php echo get_field( $account_id, 'account' )?>:</td>
					<th class="text-right"><?php echo curr_format( $balance )?></th>
				</tr>
				<?php
			}
			?>
            <tr>
                <th>Account's Balance:</th>
                <th class="text-right"><?php echo curr_format( $total_balance )?></th>
            </tr>
            <tr>
                <th>Project Balance:</th>
                <th class="text-right"><?php echo curr_format( $project_balance )?></th>
            </tr>
            <tr>
                <th>Difference:</th>
                <th class="text-right"><?php echo curr_format( $project_balance-$total_balance )?></th>
            </tr>
        </table>
    </div>
    <div class="col-md-9">
        <div id="total-sale">
        	<h2 class="total-heading" style="margin-top:0">Recent Expenses</h2>
            <div class="clearfix">
                <div class="col-md-12">
                    <h2 class="total-heading" style="margin-top:0"></h2>
                    <div class="panel-body">
                        <table width="100%" class="table table-hover list">
                            <tr>
                                <th width="3%">SN</th>
                                <th width="22%">Date/Time</th>
                                <th width="25%">Category</th>
                                <th width="30%">Detail</th>
                                <th width="15%">Paid By</th>
                                <th width="10%" class="text-right">Amount</th>
                            </tr>
                            <?php
                            $rs = doquery( "select a.*, b.title as category, c.title as account from expense a left join expense_category b on a.expense_category_id = b.id left join account c on a.account_id = c.id where project_id = '".$id."' and a.status order by datetime_added desc limit 0, 10", $dblink );
							if( numrows( $rs ) > 0 ) {
								$sn = 1;
								while( $r = dofetch( $rs ) ) {
									?>
									<tr>
                                        <td><?php echo $sn++?></td>
                                        <td><?php echo datetime_convert( $r[ "datetime_added" ] )?></td>
                                        <td><?php echo unslash( $r[ "category" ] )?></td>
                                        <td><?php echo unslash( $r[ "details" ] )?></td>
                                        <td><?php echo unslash( $r[ "account" ] )?></td>
                                        <td class="text-right"><?php echo curr_format( $r[ "amount" ] )?></td>
                                    </tr>
									<?php
								}
							}
 							?>
                        </table>
                        <div class="fancybox-btn">
				            <a href="expense_manage.php?project_id=<?php echo $id?>" class="btn f-iframe btn-default btn-l">View All Expenses</a>
                            <a href="expense_manage.php?project_id=<?php echo $id?>&tab=add" class="btn f-iframe btn-danger btn-l">Add Expense</a>
                       	</div>
                    </div>
                </div>
            </div>
            <h2 class="total-heading" style="margin-top:0">Recent Transactions</h2>
            <div class="clearfix">
                <div class="col-md-12">
                    <h2 class="total-heading" style="margin-top:0"></h2>
                    <div class="panel-body">
                        <table width="100%" class="table table-hover list">
                            <tr>
                                <th width="3%">SN</th>
                                <th width="22%">Date/Time</th>
                                <th width="20%">Account To</th>
                                <th width="15%">Account From</th>
                                <th width="30%">Detail</th>
                                <th width="10%" class="text-right">Amount</th>
                            </tr>
                            <?php
                            $rs = doquery( "select a.*, b.title as account_from, c.title as account_to from transaction a left join account b on a.reference_id = b.id left join account c on a.account_id = c.id where project_id = '".$id."' and a.status order by datetime_added desc limit 0, 10", $dblink );
							if( numrows( $rs ) > 0 ) {
								$sn = 1;
								while( $r = dofetch( $rs ) ) {
									?>
									<tr>
                                        <td><?php echo $sn++?></td>
                                        <td><?php echo datetime_convert( $r[ "datetime_added" ] )?></td>
                                        <td><?php echo unslash( $r[ "account_to" ] )?></td>
                                        <td><?php echo unslash( $r[ "account_from" ] )?></td>
                                        <td><?php echo unslash( $r[ "details" ] )?></td>
                                        <td class="text-right"><?php echo curr_format( $r[ "amount" ] )?></td>
                                    </tr>
									<?php
								}
							}
 							?>
                        </table>
                        <div class="fancybox-btn">
				            <a href="transaction_manage.php?project_id=<?php echo $id?>" class="btn f-iframe btn-default btn-l">View All Transactions</a>
                            <a href="transaction_manage.php?project_id=<?php echo $id?>&tab=add" class="btn f-iframe btn-danger btn-l">Add Transaction</a>
                       	</div>
                    </div>
                </div>
            </div>
            <h2 class="total-heading" style="margin-top:0">Invoices</h2>
            <div class="clearfix">
                <div class="col-md-12">
                    <h2 class="total-heading" style="margin-top:0"></h2>
                    <div class="panel-body">
                        <table width="100%" class="table table-hover list">
                            <tr>
                                <th width="3%">SN</th>
                                <th width="12%">Invoice #</th>
                                <th width="15%">Invoice Date</th>
                                <th width="15%">Due Date</th>
                                <th width="15%" class="text-right">Invoice Total</th>
                                <th width="20%">Paid To</th>
                                <th width="15%" class="text-right">Amount</th>
                            </tr>
                            <?php
                            $rs = doquery( "select a.*, ifnull( b.amount, 0) as amount, ifnull(c.title, '--') as account from invoice a left join project_payment b on a.project_payment_id = b.id left join account c on b.account_id = c.id where a.project_id = '".$id."' and a.status = 1 order by due_date desc", $dblink );
							if( numrows( $rs ) > 0 ) {
								$sn = 1;
								while( $r = dofetch( $rs ) ) {
									?>
									<tr>
                                        <td><?php echo $sn++?></td>
                                        <td><?php echo "#".$r[ "id" ]?></td>
                                        <td><?php echo date_convert( $r[ "invoice_date" ] )?></td>
                                        <td><?php echo date_convert( $r[ "due_date" ] )?></td>
                                        <td class="text-right"><?php echo curr_format( $r[ "net_amount" ] )?></td>
                                        <td><?php echo unslash( $r[ "account" ] )?></td>
                                        <td class="text-right"><?php echo curr_format( $r[ "amount" ] )?></td>
                                    </tr>
									<?php
								}
							}
							else {
								?>
								<tr>
                                	<td class="danger" colspan="7">No Invoice has been sent yet.</td>
                                </tr>
								<?php
							}
 							?>
                        </table>
                        <div class="fancybox-btn">
				            <a href="invoice_manage.php?project_id=<?php echo $id?>&tab=addedit" class="btn f-iframe btn-danger btn-l">Add New Invoice</a>
                       	</div>
                    </div>
                </div>
            </div>
            <h2 class="total-heading" style="margin-top:0">Project Payments</h2>
            <div class="clearfix">
                <div class="col-md-12">
                    <h2 class="total-heading" style="margin-top:0"></h2>
                    <div class="panel-body">
                        <table width="100%" class="table table-hover list">
                            <tr>
                                <th width="3%">SN</th>
                                <th width="15%">Date/Time</th>
                                <th>Details</th>
                                <th width="20%">Paid To</th>
                                <th width="15%" class="text-right">Amount</th>
                            </tr>
                            <?php
                            $rs = doquery( "select a.*, ifnull(b.title, '--') as account from project_payment a left join account b on a.account_id = b.id where a.project_id = '".$id."' and a.status = 1 order by datetime_added desc limit 0,10", $dblink );
							if( numrows( $rs ) > 0 ) {
								$sn = 1;
								while( $r = dofetch( $rs ) ) {
									?>
									<tr>
                                        <td><?php echo $sn++?></td>
                                        <td><?php echo date_convert( $r[ "datetime_added" ] )?></td>
                                        <td><?php echo unslash( $r[ "details" ] )?></td>
                                        <td><?php echo unslash( $r[ "account" ] )?></td>
                                        <td class="text-right"><?php echo curr_format( $r[ "amount" ] )?></td>
                                    </tr>
									<?php
								}
							}
							else {
								?>
								<tr>
                                	<td class="danger" colspan="7">No Payment has been made yet.</td>
                                </tr>
								<?php
							}
 							?>
                        </table>
                        <div class="fancybox-btn">
				            <a href="project_payment_manage.php?project_id=<?php echo $id?>&tab=add" class="btn f-iframe btn-danger btn-l">Add New Payment</a>
                       	</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
