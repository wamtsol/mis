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

        </div>
        <div class="content">
            <div class="sale_tax">
                <h1>
                    <?php
                    if($invoice["type"]==1){
                        echo "Commercial Invoice";
                    }
                    else {
                        echo "Sales Tax Invoice";
                    }
                    ?>
                </h1>
            </div>
            <div class="invoice_date"><p>DATE: <?php echo date_convert($invoice["invoice_date"]);?></p></div>
            <div class="number clear">
                <div class="invoice_number"><p>Invoice # DES/A/<?php echo $invoice["id"]. '/' .date('m/Y');?></p></div>
                <div class="order_number"><p>Work Order # <?php echo unslash($invoice["work_order_number"])?></p></div>
            </div>
            <div class="client_detail clear">
                <!-- <div class="detail_box">
            	<h3>Supplier</h3>
                <div class="detail_box_inner">
                	<p><?php echo $site_title;?></p>
                	<p> <?php echo get_config('supplier_detail');?></p>
                </div>
            </div>
            <div class="detail_box">
            	<h3>Buyer</h3>
                <div class="detail_box_inner">
                    <p><?php echo get_field( get_field( $invoice["project_id"], "project", "client_id" ), "client", "client_name" );?></p>
                    <p><?php echo get_field( get_field( $invoice["project_id"], "project", "client_id" ), "client", "address" );?></p>
                </div>
            </div> -->
            </div>
            <h3>Client</h3>
            <p style="margin-top: 10px;">Attention: <?php echo get_field( get_field( $invoice["project_id"], "project", "client_id" ), "client", "client_name" );?></p>
            <p><?php echo get_field( get_field( $invoice["project_id"], "project", "client_id" ), "client", "address" );?></p>
            <p>Dear Sir,<br> We are submitting our invoice against <?php echo get_field( $invoice["project_id"], "project", "title" );?><br> Invoice Summary is as Under:</p>
            <table width="100%" cellpadding="0" cellspacing="0">
                <thead>
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
                    <td colspan="2" class="no-border"></td>
                    <td class="no-border text-right" colspan="2"><strong>SRB Sales Tax 13%</strong></td>
                    <td class="text-right"><?php echo curr_format(unslash($invoice["sales_tax"])); ?></td>
                </tr>
                <tr>
                    <td colspan="3" class="no-border"></td>
                    <td class="no-border text-right"><strong>NET TOTAL</strong></td>
                    <td class="text-right"><?php echo curr_format(unslash($invoice["net_amount"])); ?></td>
                </tr>
                </tbody>
            </table>
            <p><strong>(<?php echo ucfirst(convert_number_to_words(round($invoice["net_amount"])));?> rupees only.)</strong></p>
            <p>Your quick response for verification and release of amount will be appreciated.</p>
            <p>We thank you and assure you of our best service all the time.</p>
            <p>With Best Regards</p>
            <p>WAMTSOL (Private) Limited</p>
        </div>
    </div>
    </body>
    </html>
<?php
die;
//}