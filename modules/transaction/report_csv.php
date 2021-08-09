<?php
if(!defined("APP_START")) die("No Direct Access");
$rs = doquery( $sql, $dblink );
if(numrows($rs)>0){
    header('Content-Type: text/csv; charset=utf-8');
    header("Content-Disposition: attachment; filename=transaction.csv");
    $fh = fopen( 'php://output', 'w' );
    if( !empty( $date_from ) ){
        fputcsv($fh,array('From:', $date_from, '', 'To:', $date_to));
    }
    if( !empty( $project_id ) ){
        $project = get_field($project_id, "project", "title");
        fputcsv($fh,array('Project:', $project));
    }
    fputcsv($fh,array('S.no','Date/Time','Account To','Account From','Cheque Number','Details','Amount'));
    $sn=1;
    $total = 0;
    while($r=dofetch($rs)){
        $total += $r["amount"];
        fputcsv($fh,array(
            $sn++,
            datetime_convert($r["datetime_added"]),
            get_field($r["account_id"], "account","title"),
            get_field($r["reference_id"], "account","title"),
            unslash($r["cheque_number"]),
            slash($r["details"]),
            curr_format($r["amount"])
        ));
    }
    fputcsv($fh,array('','','','','','Total:',curr_format($total)));
    fclose($fh);
}
die;
?>
