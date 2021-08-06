<?php
if(!defined("APP_START")) die("No Direct Access");
$rs = doquery( $sql, $dblink );
if(numrows($rs)>0){
    header('Content-Type: text/csv; charset=utf-8');
    header("Content-Disposition: attachment; filename=project_payment.csv");
    $fh = fopen( 'php://output', 'w' );
    if( !empty( $project_id ) ){
        $project = get_field($project_id, "project", "title");
        fputcsv($fh,array('Project:', $project));
    }
    fputcsv($fh,array('S.no','ID','Details','Date','Amount','Sales Tax','Total'));
    $sn=1;
    $total = $amount = $tax = 0;
    while($r=dofetch($rs)){
        $amount += $r["amount"]-$r["sales_tax"];
        $tax += $r["sales_tax"];
        $total += $r["amount"];
        fputcsv($fh,array(
            $sn++,
            $r["id"],
            unslash( $r[ "title" ] ),
            date_convert($r["datetime_added"]),
            curr_format(unslash($r["amount"]-$r["sales_tax"])),
            curr_format(unslash($r["sales_tax"])),
            curr_format(unslash($r["amount"]))
        ));
    }
    fputcsv($fh,array('','','','Total:',curr_format($amount),curr_format($tax),curr_format($total)));
    fclose($fh);
}
die;
?>
