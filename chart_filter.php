<?php
    $page_identifier = "./";
    $title = "Home Page";
    require 'modules/app.php';
    require 'modules/db.php';
$start_date = $_POST["range1"];
$end_date = $_POST["range2"];
$chart_heading = $_POST["chart_heading"];
$chart_type = $_POST["chart_type"];
$mac = $_POST["mac"];
if($chart_type=="vol_ph_1"){
    $column_name = "Vlt1";
}
if($chart_type=="vol_ph_2"){
    $column_name = "Vlt2";
}
if($chart_type=="vol_ph_3"){
    $column_name = "Vlt3";
}
if($chart_type=="tmp_1"){
    $column_name = "Tmp1";
}
if($chart_type=="tmp_2"){
    $column_name = "Tmp2";
}
if($chart_type=="tmp_3"){
    $column_name = "Tmp3";
}
if($chart_type=="pwr_1"){
    $column_name = "pow1";
}
if($chart_type=="pwr_2"){
    $column_name = "pow2";
}
if($chart_type=="pwr_3"){
    $column_name = "pow3";
}
?>
<!doctype html>
<html lang="en">
<head>
    <?php require 'modules/head.php'; ?>
</head>
    <body>
        <?php require 'modules/navbar.php'; ?>

        <div class="graph_plot pl-4 pr-4 mt-5">
            <div class="container mb-4 timing_headings d-flex">
                <p class="font-family-josefin font-size-large mr-5">
                    <a href="<?php echo $dashboard_link; ?>" class="text-white">
                        <i class="fas fa-chevron-left mr-2"></i>Back To Dashboard
                    </a>
                </p>
                <p class="heading font-family-josefin font-size-large mr-2">Start Date: </p>
                <p class="date text-muted"><?php echo date("d M,Y", strtotime($start_date)); ?></p>
                <p class="heading font-family-josefin font-size-large mr-2 ml-5">End Date: </p>
                <p class="date text-muted"><?php echo date("d M,Y", strtotime($end_date)); ?></p>
                <form class="ml-auto" action="modules/download.php" method="post">
                    <input type="hidden" name="range1" value="<?php echo $start_date; ?>">
                    <input type="hidden" name="range2" value="<?php echo $end_date; ?>">
                    <input type="hidden" name="chart_heading" value="<?php echo $chart_heading; ?>">
                    <input type="hidden" name="chart_type" value="<?php echo $chart_type; ?>">
                    <button name="download" type="submit" class="btn btn-info">Download Data</button>
                </form>
            </div>
            <div id="graph_1"></div>
        </div>


        <div class="spacer1">
            <?php require 'modules/footer.php'; ?>
        </div>
    </body>

</html>


<script>
    window.onload = function () {



        <?php
        $sql = "SELECT * FROM api_data WHERE (date_now BETWEEN '$start_date' AND '$end_date' AND machine_mac='$mac')";
//        echo $sql;
        $res = mysqli_query($con, $sql);
        ?>
//tHREE chARTS

        var limit =  <?php echo mysqli_num_rows($res); ?>;
        var y = 100;
        var data = [];
        var dataSeries = { type: "spline" };
        var    dataPoints= [
            <?php
            while($row=mysqli_fetch_assoc($res)){
//                echo "{ label: '".$row["date_now"]." (".$row['time_now'].")', y: ".$row['current_values']." },";
                $time_24 = explode(':', date("H:i", strtotime($row["time_now"])));
//                        echo $time_24[0].'__'.$time_24[1];
                $a = str_replace("-",",",$row["date_now"]);
                /*
                echo $a;*/
                echo "{ label: '".$row["date_now"]." (".$row['time_now'].")', y: ".$row[$column_name].",  color: '#d048b6' },";
//                echo "{ x: new Date(".$a.",".$time_24[0].",".$time_24[1].",0), y: ".$row[$column_name].",  color: '#d048b6' },";
            }
            ?>
        ];


        dataSeries.dataPoints = dataPoints;
        dataSeries.lineColor = "#d048b6";
        data.push(dataSeries);

//Better to construct options first and then pass it as a parameter
        var options = {
            backgroundColor: "#27293d",
            zoomEnabled: true,
            animationEnabled: true,
            theme: "light2",
            title: {
                text: "<?php echo $chart_heading; ?>",
                fontColor: "#d2d2c9",
                fontWeight: "normal"
            },
            axisY: {
                includeZero: false,
                lineThickness: 1,
                labelFontColor: "#d2d2c9",
                gridColor: "#ffffff1f"
            },
            axisX: {
                labelFontColor: "#d2d2c9",
                labelAngle: -90/90,
                labelWrap: true
            },
            data: data  // random data
        };



        var chart = new CanvasJS.Chart("graph_1", options);
        chart.render();




        function toggleDataSeries(e){
            if (typeof(e.dataSeries.visible) === "undefined" || e.dataSeries.visible) {
                e.dataSeries.visible = false;
            }
            else{
                e.dataSeries.visible = true;
            }
            chart.render();
        }
    }
</script>