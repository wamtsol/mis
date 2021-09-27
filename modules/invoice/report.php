<?php
if(!defined("APP_START")) die("No Direct Access");
$rs = doquery( $sql, $dblink );
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Invoice List</title>
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
    border-collapse:  collapse;
	max-width:1200px;
	margin:0 auto;
	text-align:left
}
</style>
</head>
<body>
<table width="100%" cellspacing="0" cellpadding="0">
	<tr class="head">
        <th colspan="7" align="center">
            <?php echo get_config( 'fees_chalan_header' )?>
            <h2>Invoice List</h2>
            <p>
                <?php
                if( !empty( $project_id ) ){
                    echo " Project: ".get_field($project_id, "project","title");
                }
                ?>
            </p>
        </th>
    </tr>
    <tr>
        <th width="5%" style="text-align:center">S#</th>
        <th width="20%">Project</th>
        <th width="15%">Invoice Date</th>
        <th width="15%">Due Date</th>                
        <th width="15%">Invoice#</th>
        <th align="right" width="15%">Total Amount</th>
        <th align="right" width="10%">Payment Amount</th>
    </tr>
    <?php
	$net_amount = $payment_amount = 0;
    if(numrows($rs)>0){
        $sn=1;
        while($r=dofetch($rs)){
			$net_amount += $r["net_amount"];
			$payment_amount += $r["payment_amount"];
            ?>
            <tr>
                <td style="text-align:center"><?php echo $sn++?></td>
                <td><?php echo unslash($r["title"]); ?></td>
                <td><?php echo date_convert($r["invoice_date"]); ?></td>
                <td><?php echo date_convert($r["due_date"]); ?></td>                        
                <td><?php echo $r["id"]?></td>
                <td align="right"><?php echo curr_format(unslash($r["net_amount"])); ?></td>
                <td align="right"><?php echo curr_format(unslash($r["payment_amount"])); ?></td>
            </tr>
            <?php
        }
    }
    ?>
    <tr>
        <th colspan="5" style="text-align:right;">Total</th>
        <th style="text-align:right;"><?php echo $net_amount;?></th>
        <th style="text-align:right;"><?php echo $payment_amount;?></th>
    </tr>
</table>
</body>
</html>
<?php
die;
//}