<?php
if(!defined("APP_START")) die("No Direct Access");
$extra='';
$is_search=false;
if(isset($_GET["id"])){
	$id=slash($_GET["id"]);
}
else{
	$id= '';
}
if(isset($_GET["date_from"])){
	$date_from=slash($_GET["date_from"]);
	$_SESSION["supplier"]["report"]["date_from"]=$date_from;
}
if(isset($_SESSION["supplier"]["report"]["date_from"]))
	$date_from=$_SESSION["supplier"]["report"]["date_from"];
else
	$date_from=date( "01/m/Y h:i A" );
	$is_search=true;
if(isset($_GET["date_to"])){
	$date_to=slash($_GET["date_to"]);
	$_SESSION["supplier"]["report"]["date_to"]=$date_to;
}
if(isset($_SESSION["supplier"]["report"]["date_to"]))
	$date_to=$_SESSION["supplier"]["report"]["date_to"];
else
	$date_to=date( "d/m/Y h:i A" );
	$is_search=true;
if($id){
	$extra.=" and id='".$id."'";
	$suppliers=doquery("select * from supplier where 1 $extra",$dblink);
	if(numrows($suppliers)>0){
		$supplier=dofetch($suppliers);
	}
	else {
		return;
	}
}
?>
<style>
h1, h2, h3, p {
    margin: 0 0 10px;
}

body {
    margin:  0;
    font-family:  Arial;
    font-size:  11px;
}
.head th, .head td{ border:0;}
th, td {
    border: solid 1px #000;
    padding: 5px 5px;
    font-size: 11px;
	vertical-align:top;
}
table table th, table table td{
	padding:3px;
}
table {
    border-collapse:collapse;
	max-width:1200px;
	margin:0 auto;
}
</style>
<table width="100%" cellspacing="0" cellpadding="0">
<tr class="head">
	<th colspan="9">
    	<?php echo get_config( 'fees_chalan_header' )?>
    	<h2>Supplier Ledger</h2>
        <p>
        	<?php
			echo "List of";
            if( !empty( $id ) ){
				echo " Supplier: ".get_field($id, "supplier","supplier_name");
			}
			?>
        </p>
    </th>
</tr>
<tr>
    <th width="5%" align="center">S.no</th>
    <th>Date</th>
    <th>Transaction</th>                
    <th align="right">Amount</th>
    <th align="right">Balance</th>
</tr>
<?php 
if( !empty( $id ) ){
	$balance = get_supplier_balance( $supplier[ "id" ], datetime_dbconvert( $date_to ) );
	$sn=1;
	?>
	<tr>
		<td align="center"><?php echo $sn++;?></td>
		<td><?php echo $date_to; ?></td>
		<td>Closing Balance</td>
		<td align="right">--</td>
		<td align="right"><?php echo curr_format($balance); ?></td>
	</tr>
	<?php
	$sql="select concat( 'Purchase #', id) as transaction, date, net_price as amount from purchase where supplier_id = '".$supplier[ "id" ]."' and date >='".datetime_dbconvert( $date_from )."' and date <='".datetime_dbconvert( $date_to )."' union select concat( 'Purchase Return #', id) as transaction, date, -net_price as amount from purchase_return where supplier_id = '".$supplier["id"]."' and date >='".datetime_dbconvert( $date_from )."' and date <='".datetime_dbconvert( $date_to )."' union select 'Payment', datetime as date, -amount from supplier_payment where supplier_id = '".$supplier[ "id" ]."' and datetime >='".datetime_dbconvert( $date_from )."' and datetime <='".datetime_dbconvert( $date_to )."' order by date desc";
	$rs=doquery($sql,$dblink);
	if(numrows($rs)>0){
		while($r=dofetch($rs)){
			?>
			<tr>
				<td align="center"><?php echo $sn;?></td>
				<td><?php echo datetime_convert($r["date"]); ?></td>
				<td><?php echo unslash($r["transaction"]); ?></td>
				<td align="right"><?php echo curr_format($r["amount"]); ?></td>
				<td align="right"><?php echo curr_format($balance); ?></td>
			</tr>
			<?php 
			$sn++;
			$balance = $balance - $r["amount"];
		}
		?>
		<tr>
			<td align="center"><?php echo $sn++;?></td>
			<td><?php echo $date_from; ?></td>
			<td>Opening Balance</td>
			<td align="right">--</td>
			<td align="right"><?php echo curr_format($balance); ?></td>
		</tr>
		<?php
	}
	else{	
		?>
		<tr>
			<td colspan="5"  class="no-record">No Result Found</td>
		</tr>
		<?php
	}
}
else {
	?>
	<tr>
		<td colspan="5"  class="no-record">Select Supplier from above dropdown</td>
	</tr>
	<?php
}
?>
</table>
<?php
die;