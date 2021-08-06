<?php 
include("include/db.php");
include("include/utility.php");
include("include/session.php");
$page="index";
$extra='';
$order_by = "date";
$order = "asc";
$orderby = $order_by." ".$order;
?>
<?php include("include/header.php");?>		
   <div class="page-header">
        <h1 class="title">Dashboard</h1>
        <ol class="breadcrumb">
            <li class="active">Welcome to <?php echo $site_title?> Dashboard.</li>
        </ol>
    </div>
    <div class="container-widget row">
    	
        <div class="col-md-12">
            <h2 class="title">Active Projects</h2>
			<?php
            $projects=doquery("Select * from project where status = 1 order by start_date desc",$dblink);
            if( numrows( $projects ) > 0 ){
                ?>
                <ul class="menu-boxes clearfix">
                    <?php
                    while( $project = dofetch( $projects ) ){
                        ?>
                        <li class="col-xs-6 col-md-2 col-sm-3">
                            <a href="project_manage.php?tab=view&id=<?php echo $project[ "id" ] ?>">
                                <span class="project-icon"><img width="40px" height="40px" alt="Menu Icon" src="images/project-icon.png"></span>
                                <span><?php echo unslash( $project[ "title" ] );?></span>
                            </a>
                        </li>
                        <?php
                    }
                    ?>
                </ul>
                <?php
            }
            ?>
            <div class="row">
                <div class="col-12">
                    <h2 class="title">Recent Transactions</h2>
                    <div class="table-responsive">
                        <table class="table table-stripped">
                            <thead>
                                <tr>
                                    <th width="2%">Sn</th>
                                    <th width="10%">Date</th>
                                    <th width="10%">Type</th>
                                    <th width="10%">Account</th>
                                    <th>Details</th>
                                    <th width="10%">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $rs = doquery("select * from (select 'Project Payment' as type, datetime_added, c.title as account, amount, concat('Project Payment - ', b.title) as details from project_payment a left join project b on a.project_id = b.id left join account c on a.account_id = c.id union select 'Expense' as type, datetime_added, d.title as account, amount, concat(b.title, ' - ', a.details, if(c.title!='', concat(' (', c.title, ')'),'')) as details from expense a left join expense_category b on a.expense_category_id=b.id left join project c on a.project_id = c.id left join account d on a.account_id = d.id union select 'Transaction' as type, datetime_added, b.title as account, amount, concat('From ', c.title, ' to ', b.title, ' - ', a.details, if(d.title!='', concat(' (', d.title, ')'),'')) from transaction a left join account b on a.account_id = b.id left join account c on a.reference_id=c.id left join project d on a.project_id = d.id union select 'Salary Payment' as type, datetime_added, c.title as account, amount, concat('Salary Payment - ', b.name) as details from employee_salary_payment a left join employee b on a.employee_id = b.id left join account c on a.account_id = c.id) as combined order by datetime_added desc limit 0,100", $dblink);
                                if(numrows($rs)>0){
                                    $sn = 1;
                                    while ($r = dofetch($rs)){
                                        ?>
                                        <tr style="background-color: <?php echo $r["type"]=='Project Payment'?'#50ea1d':($r["type"]=='Transaction'?'#bcbeff':($r["type"]=='Expense'?'#fdb694':'#feffbc'))?>">
                                            <td><?php echo $sn++?></td>
                                            <td><?php echo datetime_convert($r["datetime_added"])?></td>
                                            <td><?php echo $r["type"]?></td>
                                            <td><?php echo unslash($r["account"])?></td>
                                            <td><?php echo unslash($r["details"])?></td>
                                            <td class="text-right"><?php echo curr_format($r["amount"])?></td>
                                        </tr>
                                        <?php
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php
                $expense_charts = [];
                $rs = doquery("select * from project where client_id = 1 order by start_date", $dblink);
                if(numrows($rs) > 0){
                    while($r = dofetch($rs)){
                        ?>
                        <div class="col-md-4" style="padding: 15px;">
                            <div id="expense-chart-<?php echo $r["id"]?>" style="width: 100%; height: 300px;"></div>
                        </div>
                        <?php
                        $expense_charts[$r["id"]] = [
                            "title" => unslash($r["title"]),
                            "data" => []
                        ];
                        $expenses = doquery("select * from (select b.title as category, sum(amount) as total from expense a left join expense_category b on a.expense_category_id = b.id where project_id = '".$r["id"]."' group by expense_category_id union select 'Salary' as category, sum(amount) from employee_salary where project_id='".$r["id"]."') as expenses order by total desc", $dblink);
                        if(numrows($expenses) > 0){
                            while($expense = dofetch($expenses)){
                                $expense_charts[$r["id"]]["data"][] = [
                                        "y" => $expense["total"],
                                    "label" => unslash($expense["category"])
                                ];
                            }
                        }
                    }
                }
                ?>
            </div>
            <div id="chartContainer1" style="height: 600px; width: 100%;"></div>
            <hr>
            <div id="chartContainer2" style="height: 300px; width: 100%;"></div>
            <div id="chartContainer3" style="height: 300px; width: 100%;"></div>
        </div>
    </div>
</div>
<?php include("include/footer.php");?>
<?php
$revenue = '[';
$expense = '[';
$rs = doquery("select month, sum(revenue) as revenue, sum(expense) as expense from (select EXTRACT(YEAR_MONTH from datetime_added) as month, sum(amount) as revenue, 0 as expense FROM project_payment group by month union select EXTRACT(YEAR_MONTH from datetime_added) as month, 0 as revenue, sum(amount) as expense FROM expense group by month union select concat(year, LPAD(month+1, 2, '0')) as monthyear, 0 as revenue, sum(amount) as expense FROM employee_salary group by monthyear) as combined group by month order by month", $dblink);
if(numrows($rs) > 0){
    while($r = dofetch($rs)){
        $year = substr($r["month"], 0, 4);
        $month = substr($r["month"], 4, 2)-1;
        $revenue .= '{ x: new Date('.$year.', '.$month.', 1),  y: '.$r["revenue"].' },';
        $expense .= '{ x: new Date('.$year.', '.$month.', 1),  y: '.$r["expense"].' },';
    }
}
$revenue .= ']';
$expense .= ']';
$project_payments = [];
$rs = doquery("select CAST(datetime_added as DATE) as date, b.title, sum(amount) as revenue FROM project_payment a inner join project b on a.project_id = b.id where project_id in (select id from project where client_id = 1) and datetime_added BETWEEN CURDATE() - INTERVAL 30 DAY AND CURDATE() group by date, project_id order by start_date, date", $dblink);
if(numrows($rs) > 0){
    while($r = dofetch($rs)){
        $title = unslash($r["title"]);
        if(!isset($project_payments[$title])){
            $project_payments[$title] = '';
        }
        $date = explode("-", $r["date"]);
        $project_payments[$title] .= '{ x: new Date('.$date[0].', '.($date[1]-1).', '.$date[2].'), y: '.$r["revenue"].' },';
    }
}
$data = '[';
foreach($project_payments as $title => $dataPoints){
    $data .= '{
                type: "stackedBar",
                name: "'.$title.'",
                showInLegend: "true",
                xValueFormatString: "DD, MMM",
                yValueFormatString: "#,##0",
                dataPoints: [
                    '.$dataPoints.'
                ]
            },';
}
$data .= ']';
?>
<script>
    window.onload = function () {
        var chart = new CanvasJS.Chart("chartContainer1", {
            animationEnabled: true,
            title:{
                text: "Daily Sale DomiSys"
            },
            axisX: {
                valueFormatString: "DDD"
            },
            axisY: {
                prefix: ""
            },
            toolTip: {
                shared: true
            },
            legend:{
                cursor: "pointer",
                itemclick: toggleDataSeries
            },
            data: <?php echo $data?>
        });
        chart.render();
        var options = {
            exportEnabled: true,
            animationEnabled: true,
            title:{
                text: "Monthly Revenue VS Expense"
            },
            subtitles: [{
                text: "Click Legend to Hide or Unhide Data Series"
            }],
            axisX: {
                title: "Months"
            },
            axisY: {
                title: "Revenue",
                titleFontColor: "#4F81BC",
                lineColor: "#4F81BC",
                labelFontColor: "#4F81BC",
                tickColor: "#4F81BC"
            },
            axisY2: {
                title: "Expense",
                titleFontColor: "#C0504E",
                lineColor: "#C0504E",
                labelFontColor: "#C0504E",
                tickColor: "#C0504E"
            },
            toolTip: {
                shared: true
            },
            legend: {
                cursor: "pointer",
                itemclick: toggleDataSeries
            },
            data: [{
                type: "spline",
                name: "Revenue",
                showInLegend: true,
                xValueFormatString: "MMM YYYY",
                yValueFormatString: "#,##0",
                dataPoints: <?php echo $revenue?>
            },
                {
                    type: "spline",
                    name: "Expense",
                    axisYType: "secondary",
                    showInLegend: true,
                    xValueFormatString: "MMM YYYY",
                    yValueFormatString: "#,##0.#",
                    dataPoints: <?php
                        echo $expense
                        ?>
                }]
        };
        $("#chartContainer2").CanvasJSChart(options);

        <?php
        foreach($expense_charts as $project_id => $chart){
            ?>
            var chart<?php echo $project_id?> = new CanvasJS.Chart("expense-chart-<?php echo $project_id?>", {
                theme: "light2", // "light1", "light2", "dark1", "dark2"
                exportEnabled: true,
                animationEnabled: true,
                title: {
                    text: '<?php echo $chart["title"]?>'
                },
                data: [{
                    type: "pie",
                    startAngle: 25,
                    toolTipContent: "<b>{label}</b>: {y}",
                    showInLegend: "true",
                    legendText: "{label}",
                    indexLabelFontSize: 16,
                    indexLabel: "{label} - {y}",
                    dataPoints: JSON.parse('<?php echo json_encode($chart["data"])?>')
                }]
            });
            chart<?php echo $project_id?>.render();
            <?php
        }
        ?>

        var chart = new CanvasJS.Chart("chartContainer3", {
            theme: "light1", // "light1", "ligh2", "dark1", "dark2"
            animationEnabled: true,
            title: {
                text: "Company Finance"
            },
            axisY: {
                title: "Amount (in USD)",
                prefix: "$",
                suffix: "k",
                lineThickness: 0,
                includeZero: true
            },
            data: [{
                type: "waterfall",
                indexLabel: "{y}",
                indexLabelFontColor: "#EEEEEE",
                indexLabelPlacement: "inside",
                yValueFormatString: "#,##0k",
                dataPoints: [
                    { label: "DomiSys Sales",  y: 1273 },
                    { label: "Other Sales", y: 623 },
                    { label: "Total Revenue", isIntermediateSum: true},
                    { label: "Research", y: -150 },
                    { label: "Marketing",  y: -226 },
                    { label: "Salaries", y: -632 },
                    { label: "Operating Income", isCumulativeSum: true },
                    { label: "Taxes",  y: -264 },
                    { label: "Net Income",  isCumulativeSum: true }
                ]
            }]
        });
        chart.render();

        function toggleDataSeries(e) {
            if (typeof (e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                e.dataSeries.visible = false;
            } else {
                e.dataSeries.visible = true;
            }
            e.chart.render();
        }

    }
</script>
<script src="https://canvasjs.com/assets/script/jquery-1.11.1.min.js"></script>
<script src="https://canvasjs.com/assets/script/jquery.canvasjs.min.js"></script>
