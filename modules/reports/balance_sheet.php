<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<div class="page-header">
	<h1 class="title">Balance Sheet</h1>
  	<ol class="breadcrumb">
    	<li class="active">Balance Sheet</li>
  	</ol>
  	
</div>
<div class="panel-body table-responsive">
	<table class="table table-hover list">
    	<thead>
            <tr>
                <th width="50%">Assets</th>
                <th>Liabilities</th>
            </tr>
    	</thead>
    	<tbody>
        	<tr>
				<td>
					<table  class="table table-hover list">
						<thead>
                            <tr>
                                <th colspan="2">Current Assets</th>
                            </tr>
                        </thead>
						<?php 
						$total = 0;
                        $total_salary = 0;
						$account_payable = array();
                        $capitals = array();
                        $fixed_assets = array();
                        $salary_balance = 0;
                        $opening_balance = dofetch(doquery("select sum(balance) as total from account", $dblink));
                        $opening_balance = $opening_balance["total"];
                        if($opening_balance<0){
                            $total += -$opening_balance;
                            ?>
                            <tr>
                                <td>Account Opening Balance</td>
                                <td class="text-right"><?php echo curr_format( $opening_balance ) ?></td>
                            </tr>
                            <?php
                        }
						$sql="select * from account order by title";
						//select b.title, account_id, sum(amount) from (SELECT account_id, 1 as type, sum(amount) as amount FROM `transaction` group by account_id union SELECT reference_id as account_id, 0 as type, -sum(amount) as amount FROM `transaction` group by reference_id union select account_id, 2 as type, -sum(amount) from expense group by account_id union select account_id, 3 as type, -sum(amount) from employee_salary_payment group by account_id union select account_id, 4 as type, sum(amount) from project_payment group by account_id) as t left join account b on t.account_id = b.id group by account_id order by title
						$rs=doquery($sql, $dblink);
						if( numrows($rs) > 0){
							$sn=1;
							while($r=dofetch($rs)){             
								$balance = get_account_balance( $r[ "id" ] );
								if($balance!=0){
									if( $balance >= 0 ) {
										if($r["type"]==0){
                                            $total += $balance;
                                            ?>
                                            <tr>
                                                <td><?php echo unslash($r["title"] ); ?></td>
                                                <td class="text-right"><?php echo curr_format( $balance ) ?></td>
                                            </tr>
                                            <?php
                                        }
										else{
                                            $fixed_assets[] = array(
                                                "name" => unslash($r["title"] ),
                                                "balance" => $balance
                                            );
                                        }
										$sn++;
									}
									else {
                                        if($r["type"]==2){
                                            $capitals[] = array(
                                                "name" => unslash($r["title"] ),
                                                "balance" => $balance
                                            );
                                        }
										else{
										    $account_payable[] = array(
                                                "name" => unslash($r["title"] ),
                                                "balance" => $balance
                                            );
                                        }
									}
								}
							}
						}
						$sql="select * from employee order by name";
						$rs=doquery($sql, $dblink);
						if( numrows($rs) > 0){
							$sn=1;
							?>
                            <thead>
                            <tr>
                                <th colspan="2">Salary Account</th>
                            </tr>
                            </thead>
                            <?php
							while($r=dofetch($rs)){             
								$salary = get_user_salary_total( $r[ "id" ] );
								$balance = $salary["balance"];
								$total_salary += $salary["total_debit"];
                                $salary_balance += $balance;
								if($balance!=0){
									if( $balance >= 0 ) {
										$total += $balance;
										?>
										<tr>
											<td><?php echo unslash($r["name"]); ?></td>
											<td class="text-right"><?php echo curr_format( $balance ) ?></td>
										</tr>
										<?php 
										$sn++;
									}
									else {
										$account_payable[] = array(
											"name" => "Salary Account - ".unslash($r["name"] ),
											"balance" => $balance
										);
									}
								}
							}
						}
						if($salary_balance<0){
                            $total += -$salary_balance;
						    ?>
                            <tr>
                                <td>Salary Paybale</td>
                                <td class="text-right"><?php echo curr_format( -$salary_balance ) ?></td>
                            </tr>
                            <?php
                        }
						if( count($fixed_assets) > 0){
                            ?>
                            <thead>
                            <tr>
                                <th colspan="2">Fixed Assets</th>
                            </tr>
                            </thead>
                            <?php
                            $sn=1;
                            foreach( $fixed_assets as $account ){
                                $total += $account[ "balance" ];
                                ?>
                                <tr>
                                    <td><?php echo $account["name"]; ?></td>
                                    <td class="text-right"><?php echo curr_format( $account[ "balance" ] ) ?></td>
                                </tr>
                                <?php
                                $sn++;
                            }
                        }
                        $rs = doquery( "select title, sum(amount) as total from expense a left join expense_category b on a.expense_category_id = b.id where a.status=1 group by expense_category_id order by title", $dblink );
                        if( numrows( $rs ) > 0 ) {
                            $sn=1;
                            ?>
                            <thead>
                            <tr>
                                <th colspan="2">Expenses</th>
                            </tr>
                            </thead>
                            <?php
                            while($r=dofetch($rs)){
                                $total += $r["total"];
                                ?>
                                <tr>
                                    <td><?php echo unslash($r["title"]); ?></td>
                                    <td class="text-right"><?php echo curr_format( $r["total"] ) ?></td>
                                </tr>
                                <?php
                                $sn++;
                            }
                        }
                        $total += $total_salary;
                        ?>
                        <tr>
                            <td>Salary</td>
                            <td class="text-right"><?php echo curr_format( $total_salary )?></td>
                        </tr>
                        <tr>
                            <th>Total</th>
                            <th class="text-right"><?php echo curr_format( $total )?></th>
                        </tr>
                  	</table>
              	</td>
                <td>
					<table class="table table-hover list">
                        <?php
                        $total = 0;
                        ?>
                        <thead>
                        <tr>
                            <th colspan="2">Revenue</th>
                        </tr>
                        </thead>
                        <tr>
                            <td>Gross Income</td>
                            <td class="text-right"><?php
                                $revenue = dofetch(doquery("select -sum(amount) as total from project_payment where status = 1", $dblink));
                                $total += $revenue["total"];
                                echo curr_format($revenue["total"]);
                                ?></td>
                        </tr>
                        <?php
						if( count($account_payable) > 0){
							?>
							<thead>
                                <tr>
                                    <th colspan="2">Accounts</th>
                                </tr>
                            </thead>
							<?php
                            if($opening_balance>0){
                                $total += -$opening_balance;
                                ?>
                                <tr>
                                    <td>Account Opening Balance</td>
                                    <td class="text-right"><?php echo curr_format( -$opening_balance ) ?></td>
                                </tr>
                                <?php
                            }
							$sn=1;
							foreach( $account_payable as $account ){
								$total += $account[ "balance" ];
								?>
								<tr>
									<td><?php echo $account["name"]; ?></td>
									<td class="text-right"><?php echo curr_format( $account[ "balance" ] ) ?></td>
								</tr>
								<?php 
								$sn++;
							}
							?>
							<?php	
						}
                        if($salary_balance>0){
                            $total -= $salary_balance;
                            ?>
                            <tr>
                                <td>Advance Salary</td>
                                <td class="text-right"><?php echo curr_format( -$salary_balance ) ?></td>
                            </tr>
                            <?php
                        }
                        if( count($capitals) > 0){
                            ?>
                            <thead>
                            <tr>
                                <th colspan="2">Capital</th>
                            </tr>
                            </thead>
                            <?php
                            $sn=1;
                            foreach( $capitals as $account ){
                                $total += $account[ "balance" ];
                                ?>
                                <tr>
                                    <td><?php echo $account["name"]; ?></td>
                                    <td class="text-right"><?php echo curr_format( $account[ "balance" ] ) ?></td>
                                </tr>
                                <?php
                                $sn++;
                            }
                            ?>
                            <?php
                        }
						?>
                        <tr>
                            <th>Total</th>
                            <th class="text-right"><?php echo curr_format( $total )?></th>
                        </tr>
                  	</table>
              	</td>
           	</tr>
    	</tbody>
  	</table>
</div>
