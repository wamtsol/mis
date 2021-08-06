<?php
if(!defined("APP_START")) die("No Direct Access");
$rs = doquery( $sql, $dblink );
$total_items = $total_price = $discount = $net_price = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Purchase List</title>
<style>
body{
	font-family:Arial, Helvetica, sans-serif;
	font-size:12px;
}
.print-list{
}
.print-list h3{
	font-size:20px;
	text-transform:uppercase;
	text-align:center;
	margin: 10px 0 0;
}
.print-list p{
	font-size:16px;
	text-align:center;
	margin: 10px 0 10px;
}
.print-list table{
	width:100%;
	border-collapse: collapse;
	text-align:left;
}
.print-list table th,.print-list table td{
	border:1px solid #000;
	padding: 5px;
	font-size: 14px;
}
</style>
</head>
<body>
<div class="print-list">
	<h3>Purchase List</h3>
    <p>
        <?php
        if( !empty( $date_from ) || !empty( $date_to ) ){
            echo "<br />Date";
        }
        if( !empty( $date_from ) ){
            echo " from ".$date_from;
        }
        if( !empty( $date_to ) ){
            echo " to ".$date_to;
        }
        if( !empty( $q ) ){
            echo " Supplier: ".$q;
        }
        ?>
    </p>
	<table class="table table-hover list">
            <tr>
                <th width="5%" style="text-align:center">S#</th>
                <th width="15%">Date</th>
                <th width="20%">Cash/Supplier Name</th>
                <th width="15%" style="text-align:right;">Total Items</th>
                <th width="15%" style="text-align:right;">Total Price</th>
                <th width="10%" style="text-align:right;">Discount</th>
                <th width="10%" style="text-align:right;">Net Price</th>
            </tr>
            <?php
            if(numrows($rs)>0){
                $sn=1;
                while($r=dofetch($rs)){
					$total_items += $r["total_items"];
					$total_price += $r["total_price"];
					$discount += $r["discount"];
					$net_price += $r["net_price"];
                    ?>
                    <tr>
                    	<td style="text-align:center"><?php echo $sn++?></td>
                        <td style="text-align:left;"><?php echo datetime_convert($r["datetime_added"]); ?></td>
                        <td style="text-align:left;"><?php echo empty($r["supplier_id"])?"Cash": get_field($r["supplier_id"], "supplier","supplier_name"); ?></td>
                        <td style="text-align:right;"><?php echo unslash($r["total_items"]); ?></td>
                        <td style="text-align:right;"><?php echo curr_format(unslash($r["total_price"])); ?></td>
                        <td style="text-align:right;"><?php echo curr_format(unslash($r["discount"])); ?></td>
                        <td style="text-align:right;"><?php echo curr_format(unslash($r["net_price"])); ?></td>
                    </tr>
                    <?php
                }
            }
            ?>
            <tr>
            	<td colspan="3" style="text-align:right;">Total</td>
                <td style="text-align:right;"><?php echo $total_items;?></td>
                <td style="text-align:right;"><?php echo $total_price;?></td>
                <td style="text-align:right;"><?php echo $discount;?></td>
                <td style="text-align:right;"><?php echo $net_price;?></td>
            </tr>
        </table>
</div>
</body>
</html>
<?php
die;
//}