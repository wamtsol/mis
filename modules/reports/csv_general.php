<?php
if(!defined("APP_START")) die("No Direct Access");
include("general_journal_do.php");
$rs = doquery( $sql, $dblink );
if(numrows($rs)>0){
    header('Content-Type: text/csv; charset=utf-8');
    header("Content-Disposition: attachment; filename=employee_salary.csv");
    $fh = fopen( 'php://output', 'w' );
    if( !empty( $from ) ){
        fputcsv($fh,array('From:', $from, '', 'To:', $to));
    }
    fputcsv($fh,array('S.no','Date','Details','Debit','Credit','Balance'));
    $total_debit = 0;
    $total_credit = 0;
    $sn = 1;
    fputcsv($fh,array($order == 'desc'?'Closing':'Opening','Balance',curr_format( $balance )));
    while($r=dofetch($rs)){
        $total += $r["amount"];
        if($order == 'asc'){
            $balance += ($r["debit"]-$r["credit"])*($order == 'desc'?'-1':1);
        }
        if($order == 'desc'){
            $balance += ($r["debit"]-$r["credit"])*($order == 'desc'?'-1':1);
        }
        fputcsv($fh,array(
            $sn++,
            datetime_convert($r["date"]),
            unslash($r["details"]),
            curr_format($r["debit"]),
            curr_format($r["credit"]),
            curr_format( $balance ) 
        ));
    }
    fputcsv($fh,array($order != 'desc'?'Closing':'Opening',curr_format( $balance )));
    fputcsv($fh,array('','','Total:',curr_format($total_debit),curr_format($total_credit)));
    fclose($fh);
}
die;
?>
