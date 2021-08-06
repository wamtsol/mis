<?php
if(!defined("APP_START")) die("No Direct Access");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Invoice</title>
<link rel="stylesheet" type="text/css" href="css/invoice.css">
</head>
<body>
<?php 
	$invoice = doquery( "select * from invoice where id='".slash($_GET["id"])."'", $dblink );
	$invoice=dofetch($invoice);
?>
<div class="wrapper">
    <div class="header">
        <div class="header_inn clear">
            <div class="logo_area">
                <div class="logo"><h1><?php echo $site_title;?></h1></div>
                <div class="address">
                	<p>Address Here</p>
                    <p>Phone: 023 345534</p>
                    <p>Mobile: +92 453 4332 323</p>
                </div>
            </div>
            <div class="right_details">
                <div class="date_area">
                    <div class="invoice">Invoice</div>
                    <div class="invoice_details">
                    	<p>INVOICE # ENTERPRISE - <?php echo $invoice["id"]?></p>
                        <p>DATE: <?php echo date_convert($invoice["invoice_date"])?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="content">
        <div class="project_details">
            <p><strong>TO:</strong></p>
            <p><?php echo get_field( get_field( $invoice["project_id"], "project", "client_id" ), "client", "client_name" );?></p>
            <p><?php echo get_field( unslash($invoice["project_id"]), "project", "title" ); ?></p>
        </div>
        <div>
        	<p><strong>FOR:</strong></p>
        </div>
        <table width="100%" cellpadding="0" cellspacing="0">
        	<thead>
                <tr>
                    <th colspan="10">Details</th>
                </tr>
                <tr>
                    <th width="5%" class="text-center">S.No</th>
                    <th width="65%">Description</th>
                    <th width="10%" class="text-center">Rate</th>
                    <th width="10%" class="text-center">Quantity</th>
                    <th width="15%" class="text-center">Amount</th>
                </tr>
            </thead>
            <tbody>
				<?php
               $rs = doquery( "select * from invoice_item where invoice_id='".$invoice["id"]."' order by id", $dblink );
                if(numrows($rs)>0){
                    $sn=1;
                    while($r=dofetch($rs)){
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $sn++;?></td>
                        <td><?php echo unslash($r["details"]); ?></td>
                        <td class="text-right"><?php echo curr_format(unslash($r["rate"])); ?></td>
                        <td class="text-right"><?php echo curr_format(unslash($r["quantity"])); ?></td>
                        <td class="text-right"><?php echo curr_format(unslash($r["amount"])); ?></td>
                    </tr>
                    <?php
                    }
                }
                ?>
                
                <tr>
                    <td colspan="3" class="no-border"></td>
                    <td class="no-border text-right"><strong>TOTAL</strong></td>
                    <td class="text-right"><?php echo curr_format(unslash($invoice["total_amount"])); ?></td>
                </tr>
                <tr>
                    <td colspan="3" class="no-border"></td>
                    <td class="no-border text-right"><strong>DISCOUNT</strong></td>
                    <td class="text-right"><?php echo curr_format(unslash($invoice["discount"])); ?></td>
                </tr>
                <tr>
                    <td colspan="3" class="no-border"></td>
                    <td class="no-border text-right"><strong>NET TOTAL</strong></td>
                    <td class="text-right"><?php echo curr_format(unslash($invoice["net_amount"])); ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <p style="text-align:center; text-transform:uppercase;"><strong>Thank You For Your Business!</strong></p>
</div>
</body>
</html>
<?php
die;
//}