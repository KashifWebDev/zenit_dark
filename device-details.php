<?php
    require 'modules/app.php';
check_session();
    $page_identifier = "./";
    $title = "Dashbaord";
    if(isset($_GET["id"])) {
        $page_id = secure_parameter($_GET["id"]);
    }
?>
<!doctype html>
<html lang="en">
<head>
    <?php require 'modules/head.php'; ?>
</head>
    <?php
        require 'modules/classes/class.users.php';
        $configs = new Users();
        $device_data = $configs->getDeviceInfoById($page_id);
        $user_data = $config_data = $configs->getUserInfoById($device_data[0]["user_id"]);
        if($config_data){
            $mail_email = $device_data[0]["mailing_address"];
            $user_and_devices_id = $device_data[0]["id"];
            $cal_date = $device_data[0]["calibration_date"];
            $device_mac_address = $device_data[0]["mac"];
        }else{
            js_alert("No Such Device Found!");
            js_redirect("admin_dashboard.php");
            $mail_email = "";
            $cal_date = "";
        }
    $cal_date = date("d M,Y", strtotime($cal_date));
    ?>
    <body>
        <?php require 'modules/navbar.php'; ?>

        <div class="container mt-5">
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="row line-height-30">
                        <div class="font-family-josefin font-size-large ">
                            <div>Name:</div>
                            <div>Location:</div>
                            <div>Device Name:</div>
                            <div>Mac Address:</div>
                            <div>Status:</div>
                            <div>IP:</div>
                            <div>Email:</div>
                            <div>Cal. Date:</div>
                        </div>
                        <div class="ml-1 text-muted font-weight-bold">
                            <div><?php echo $user_data[0]["username"] ?></div>
                            <div><?php echo $device_data[0]["location"] ?></div>
                            <div><?php echo $device_data[0]["device_name"] ?></div>
                            <div><?php echo $device_data[0]["mac"] ?></div>
                            <div class="text-success">ON</div>
                            <div>192.168.0.1</div>
                            <div><?php echo $mail_email ?><span><button type="button" class="custom-btn-1 ml-1" data-toggle="modal" data-target="#edit_email">(Edit)</button></span></div>
                            <div><?php echo $cal_date ?><span><button type="button" class="custom-btn-1 ml-1" data-toggle="modal" data-target="#edit_cal_date">(Edit)</button></span></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 col-md-6 col-sm-12">
                    <?php
                    $a = "SELECT * FROM api_data WHERE machine_mac = '".$device_mac_address."' ORDER BY id DESC";
                    $b = mysqli_query($con, $a);
                    $c = mysqli_fetch_assoc($b);
                    $cmp = $c["Cmpressor"];
                    $detect = $c["Detect"];
                    $alarm = $c["Alarm"];
                    if($cmp=="Hi"){
                        $img_link = "https://icon-library.net/images/power-icon-png/power-icon-png-0.jpg";
                    }
                    if($cmp=="Lo"){
                        $img_link = "https://icon-library.net/images/power-icon-png/power-icon-png-11.jpg";
                    }
                    if($alarm=="Hi"){
                        $alarm_value = "";
                        $alarm_txt = "ON";
                        $alarm_text_clr = "danger";
                    }
                    if($alarm=="Lo"){
                        $alarm_value = "-slash";
                        $alarm_txt = "OFF";
                        $alarm_text_clr = "success";
                    }
                    if($detect=="Hi"){
                        $detect_value = "";
                        $detect_txt = "ON";
                        $detect_text_clr = "danger";
                    }
                    if($detect=="Lo"){
                        $detect_value = "-slash";
                        $detect_txt = "OFF";
                        $detect_text_clr = "success";
                    }
                    ?>
                    <div class="row">
                        <div class="col-4">
                            <h3 class="color-red text-center">Alarm</h3>
                            <div class="text-center">
                                <i class="fa fa-bell<?php echo $alarm_value; ?> text-muted font-size-150px" aria-hidden="true"></i>
                                <p class="font-weight-bold mt-2">Alarm Status: <span class="text-<?php echo $alarm_text_clr; ?>"><?php echo $alarm_txt; ?></span></p>
                            </div>
                        </div>
                        <div class="col-4">
                            <h3 class="color-red text-center">Human Detection</h3>
                            <div class="text-center">
                                <i class="fas fa-user<?php echo $detect_value; ?> text-muted font-size-150px" aria-hidden="true"></i>
                                <p class="font-weight-bold mt-2">Human Detection: <span class="text-<?php echo $detect_text_clr; ?>"><?php echo $detect_txt; ?></span></p>
                            </div>
                        </div>
                        <div class="col-4">
                            <h3 class="color-red text-center">Relay Control</h3>
                            <div class="text-center mt-5">
                                <input type="checkbox" checked data-toggle="toggle" data-size="large">
                            </div>
                            <div class="d-flex mt-5">
                                <h3 class="color-red text-center">Compressor Status: </h3>
                                <p class="ml-4"><img class="height-40px mt-3" src="<?php echo $img_link; ?>"></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    <div class="container-fluid mt-5">
        <div class="row">
            <div class="col-12">
                <div class="w-93 mx-auto">
                    <div id="three_current_phases"></div>
                </div>
            </div>
            <div class="spacer">
                &nbsp;
            </div>
            <div class="container col-12 mt-5">
                <div class="row">
                    <div class="col-4">
                        <?php
                        $heading="Voltage Phase 1";
                        $chart_type="vol_ph_1"; $sub="submit_vol_ph_1"; require 'modules/filter_form.php'; ?>
                        <div id="current_history_graph" style="height: 370px; max-width: 920px; margin: 0px auto;"></div>
                    </div>
                    <div class="col-4">
                        <?php
                        $heading="Voltage Phase 2";
                        $chart_type="vol_ph_2"; $sub="submit_vol_ph_2"; require 'modules/filter_form.php'; ?>
                        <div id="voltage_history_graph" style="height: 370px; max-width: 920px; margin: 0px auto;"></div>
                    </div>
                    <div class="col-4">
                        <?php
                        $heading="Voltage Phase 3";
                        $chart_type="vol_ph_3"; $sub="submit_vol_ph_3"; require 'modules/filter_form.php'; ?>
                        <div id="power_history_graph" style="height: 370px; max-width: 920px; margin: 0px auto;"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<!--        <div class="row mt-5">
            <div class="col-6">
                <h3 class="color-red mb-3 text-center">Alarm</h3>
                <div class="text-center">
                    <i class="fa fa-bell text-muted font-size-150px" aria-hidden="true"></i>
                    <p class="font-weight-bold mt-2">Alarm Status: <span class="text-danger">ON</span></p>
                </div>
            </div>
            <div class="col-6">
                <h3 class="color-red mb-3 text-center">Human Detection</h3>
                <div class="text-center">
                    <i class="fas fa-user-slash text-muted font-size-150px" aria-hidden="true"></i>
                    <p class="font-weight-bold mt-2">Human Detection: <span class="text-success">No</span></p>
                </div>
            </div>
        </div>-->
<!--        <h3 class="color-red my-3 text-center">Power Consumption Graphs</h3>-->


        <!-- Temperature Graphs  -->
        <div class="container-fluid row mt-5">
            <div class="col-4">
                <?php
                $heading="Temperature 1";
                $chart_type="tmp_1"; $sub="submit_tmp_1"; require 'modules/filter_form.php'; ?>
                <div id="temp_graph_1" style="height: 370px; width: 100%;"></div>
            </div>
            <div class="col-4">
                <?php
                $heading="Temperature 2";
                $chart_type="tmp_2"; $sub="submit_tmp_2"; require 'modules/filter_form.php'; ?>
                <div id="temp_graph_2" style="height: 370px; width: 100%;"></div>
            </div>
            <div class="col-4">
                <?php
                $heading="Temperature 3";
                $chart_type="tmp_3"; $sub="submit_tmp_3"; require 'modules/filter_form.php'; ?>
                <div id="temp_graph_3" style="height: 370px; width: 100%;"></div>
            </div>
        </div>


        <!-- Power Graphs  -->
        <div class="container-fluid row mt-5">
            <div class="col-4">
                <?php
                $heading="Power Phase 1";
                $chart_type="pwr_1"; $sub="submit_pwr_1"; require 'modules/filter_form.php'; ?>
                <div id="power_graph_1" style="height: 370px; width: 100%;"></div>
            </div>
            <div class="col-4">
                <?php
                $heading="Power Phase 2";
                $chart_type="pwr_2"; $sub="submit_pwr_2"; require 'modules/filter_form.php'; ?>
                <div id="power_graph_2" style="height: 370px; width: 100%;"></div>
            </div>
            <div class="col-4">
                <?php
                $heading="Power Phase 3";
                $chart_type="pwr_3"; $sub="submit_pwr_3"; require 'modules/filter_form.php'; ?>
                <div id="power_graph_3" style="height: 370px; width: 100%;"></div>
            </div>
        </div>
    </div>

        <!--
        ******   Model Codes
        -->
        <!-- Edit Eamil Modal -->
        <div class="modal" id="edit_email">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="" method="post">
                        <!-- Modal Header -->
                        <div class="modal-header bg-modal-header border-bottom-red">
                            <h4 class="modal-title">Update Mailing Address</h4>
                            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body bg-modal-body text-white">
                            <div class="input-group mb-2">
                            <span class="input-group-text text-secondary">
                                <i class="fas fa-at"></i>
                            </span>
                                <input type="hidden" name="user_devices_id" value="<?php echo $user_and_devices_id; ?>">
                                <input value="<?php echo $mail_email; ?>" type="email" name="new_email" class="form-control " placeholder="Email Address" required>
                            </div>
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer bg-modal-header text-white border-top-red">
                            <button class="w-50 btn btn-success" type="submit" name="submit-email">Save</button>
                            <button type="button" class="w-50 btn btn-danger" data-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
        if(isset($_POST["submit-email"])){
            $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $email = secure_parameter($_POST["new_email"]);
            $id = secure_parameter($_POST["user_devices_id"]);
            if($configs->updateEmailAddress($id, $email)){
                js_redirect($actual_link);
            }
            else{
                die("Error Occured in email!");
            }
        }
        ?>


        <!-- Edit Calibration Date Modal -->
        <div class="modal" id="edit_cal_date">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form action="" method="post">
                        <!-- Modal Header -->
                        <div class="modal-header bg-modal-header border-bottom-red">
                            <h4 class="modal-title">Update Calibration Date</h4>
                            <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                        </div>

                        <!-- Modal body -->
                        <div class="modal-body bg-modal-body text-white">
                            <div class="input-group mb-2">
                            <span class="input-group-text text-secondary">
                                <i class="far fa-clock"></i>
                            </span>
                                <input type="hidden" name="user_devices_id" value="<?php echo $user_and_devices_id; ?>">
                                <input type="date" name="new_date" class="form-control " placeholder="Date" required>
                            </div>
                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer bg-modal-header text-white border-top-red">
                            <button class="w-50 btn btn-success" type="submit" name="submit-date">Save</button>
                            <button type="button" class="w-50 btn btn-danger" data-dismiss="modal">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
        if(isset($_POST["submit-date"])){
            $actual_link = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $id = $_POST["user_devices_id"];
            $date = $_POST["new_date"];
            if($configs->updateDate($id, $date)){
                js_redirect($actual_link);
            }else{
                die("Error Occured in date!");
            }
        }
        ?>




        <?php require 'modules/footer.php'; ?>
    </body>

</html>


<script>
    window.onload = function () {
        // First line charts
        var chart = new CanvasJS.Chart("three_current_phases", {
            zoomEnabled: true,
            backgroundColor: "#27293d",
            animationEnabled: true,
            title:{
                text: "Currents",
                fontColor: "#d2d2c9",
                fontWeight: "normal"
            },
            axisX: {
                valueFormatString: "DD MMM,YY",
            },
            axisY: {
                title: "Current (in Amp)",
                gridColor: "lightgreen",
                includeZero: false
            },
            legend:{
                cursor: "pointer",
                fontSize: 16,
                itemclick: toggleDataSeries
            },
            toolTip:{
                shared: true
            },
            axisY: {
                includeZero: false,
                lineThickness: 1,
                labelFontColor: "#d2d2c9",
                gridColor: "#ffffff1f"
            },
            axisX: {
                labelFontColor: "#d2d2c9",
                labelAngle: -90/90
            },
            data: [{
                name: "Current 1",
                type: "spline",
                yValueFormatString: "#0.## Amp",
                showInLegend: true,
                dataPoints: [
                <?php
                require 'modules/db.php';
                $sql = "SELECT * FROM api_data WHERE machine_mac='".$device_mac_address."'";
//                echo $sql;
                $res = mysqli_query($con, $sql);
                while($row=mysqli_fetch_assoc($res)){
                    $time_24 = explode(':', date("H:i", strtotime($row["time_now"])));
                    $a = str_replace("-",",",$row["date_now"]);
//                    echo "{ x: new Date(".$a.",".$time_24[0].",".$time_24[1]."), y: ".$row['Curr1']."},";
                    echo "{ label: '".$row["date_now"]." (".$row["time_now"].")', y: ".$row['Curr1']."}, ";
                }
                ?>
                ]
            },
                {
                    name: "Current 2",
                    type: "spline",
                    yValueFormatString: "#0.## Amp",
                    showInLegend: true,
                    dataPoints: [
                        <?php
                        require 'modules/db.php';
                        $sql = "SELECT * FROM api_data WHERE machine_mac='".$device_mac_address."'";
                        //                echo $sql;
                        $res = mysqli_query($con, $sql);
                        while($row=mysqli_fetch_assoc($res)){
                            $time_24 = explode(':', date("H:i", strtotime($row["time_now"])));
                            $a = str_replace("-",",",$row["date_now"]);
//                            echo "{ x: new Date(".$a.",".$time_24[0].",".$time_24[1]."), y: ".$row['Curr2']."},";
                            echo "{ label: '".$row["date_now"]." (".$row["time_now"].")', y: ".$row['Curr2']."}, ";
                        }
                        ?>
                    ]
                },
                {
                    name: "Current 3",
                    type: "spline",
                    yValueFormatString: "#0.## Amp",
                    showInLegend: true,
                    dataPoints: [
                        <?php
                        require 'modules/db.php';
                        $sql = "SELECT * FROM api_data WHERE machine_mac='".$device_mac_address."'";
                        //                echo $sql;
                        $res = mysqli_query($con, $sql);
                        while($row=mysqli_fetch_assoc($res)){
                            $time_24 = explode(':', date("H:i", strtotime($row["time_now"])));
                            $a = str_replace("-",",",$row["date_now"]);
//                            echo "{ x: new Date(".$a.",".$time_24[0].",".$time_24[1]."), y: ".$row['Curr3']."},";
                            echo "{ label: '".$row["date_now"]." (".$row["time_now"].")', y: ".$row['Curr3']."}, ";
                        }
                        ?>
                    ]
                }]
        });




//tHREE  Voltage chARTS

        var limit = 5000;
        var y = 100;
        var data = [];
        var dataSeries = { type: "spline" };
        var    dataPoints= [
             <?php
            require 'modules/db.php';
            $sql = "SELECT * FROM api_data WHERE machine_mac='".$device_mac_address."'";
            //                echo $sql;
            $res = mysqli_query($con, $sql);
            while($row=mysqli_fetch_assoc($res)){
                $time_24 = explode(':', date("H:i", strtotime($row["time_now"])));
                $a = str_replace("-",",",$row["date_now"]);
                echo "{ label: '".$row["date_now"]." (".$row["time_now"].")', y: ".$row['Vlt1'].",  color: '#d048b6'}, ";
            }
            ?>

        ];


        dataSeries.dataPoints = dataPoints;
        dataSeries.lineColor = "#d048b6";
        data.push(dataSeries);


        var options = {
            backgroundColor: "#27293d",
            zoomEnabled: true,
            animationEnabled: true,
            theme: "light2",
            title: {
                text: "Voltage Phase 1",
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
                labelAngle: -90/90
            },
            data: data  // random data
        };

//VOLTAGE2 CHART
        var limit1 = 291;
        var y = 100;
        var data1 = [];
        var dataSeries1 = { type: "spline" };
        var    dataPoints1= [
            <?php
            require 'modules/db.php';
            $sql = "SELECT * FROM api_data WHERE machine_mac='".$device_mac_address."'";
            //                echo $sql;
            $res = mysqli_query($con, $sql);
            while($row=mysqli_fetch_assoc($res)){
                $time_24 = explode(':', date("H:i", strtotime($row["time_now"])));
                $a = str_replace("-",",",$row["date_now"]);
                echo "{ label: '".$row["date_now"]." (".$row["time_now"].")', y: ".$row['Vlt2'].",  color: '#d048b6'}, ";
            }
            ?>
            ];


        dataSeries1.dataPoints = dataPoints1;
        dataSeries1.lineColor = "#d048b6";
        data1.push(dataSeries1);

        var options1 = {
            lineColor:"#d048b6",
            backgroundColor: "#27293d",
            zoomEnabled: true,
            animationEnabled: true,
            theme: "light2",
            title: {
                text: "Voltage Phase 2",
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
                labelAngle: -90/90
            },
            data: data1  // random data
        };

//FOR Voltage3 CHART
        var limit2 = 291;
        var y = 100;
        var data2 = [];
        var dataSeries2 = { type: "spline" };
        var    dataPoints2= [
            <?php
            require 'modules/db.php';
            $sql = "SELECT * FROM api_data WHERE machine_mac='".$device_mac_address."'";
            //                echo $sql;
            $res = mysqli_query($con, $sql);
            while($row=mysqli_fetch_assoc($res)){
                $time_24 = explode(':', date("H:i", strtotime($row["time_now"])));
                $a = str_replace("-",",",$row["date_now"]);
                echo "{ label: '".$row["date_now"]." (".$row["time_now"].")', y: ".$row['Vlt3'].",  color: '#d048b6'}, ";
            }
            ?>
            ];


        dataSeries2.dataPoints = dataPoints2;
        dataSeries2.lineColor = "#d048b6";
        data2.push(dataSeries1);

        var options2 = {
            lineColor:"#d048b6",
            backgroundColor: "#27293d",
            zoomEnabled: true,
            animationEnabled: true,
            theme: "light2",
            title: {
                text: "Voltage Phase 3",
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
                labelAngle: -90/90
            },
            data: data2  // random data
        };


        chart.render();

        var chart0 = new CanvasJS.Chart("current_history_graph", options);
        chart0.render();

        var chart1 = new CanvasJS.Chart("voltage_history_graph", options1);
        chart1.render();

        var chart2 = new CanvasJS.Chart("power_history_graph", options2);
        chart2.render();

        //END OF THREE CHARTS




        //Three graphs of temperature

        var temp_graph_1 = new CanvasJS.Chart("temp_graph_1", {
            zoomEnabled: true,
            backgroundColor: "#27293d",
            animationEnabled: true,
            theme: "light2",
            title: {
                text: "Temperature  1",
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
                labelAngle: -90/90
            },
            data: [{
                lineColor : "#c0504e",
                type: "spline",
                dataPoints: [
                    <?php
                    require 'modules/db.php';
                    $sql = "SELECT * FROM api_data WHERE machine_mac='".$device_mac_address."'";
                    //                echo $sql;
                    $res = mysqli_query($con, $sql);
                    while($row=mysqli_fetch_assoc($res)){
                        $time_24 = explode(':', date("H:i", strtotime($row["time_now"])));
                        $a = str_replace("-",",",$row["date_now"]);
                        echo "{ label: '".$row["date_now"]." (".$row["time_now"].")', y: ".$row['Tmp1'].",  color: '#c0504e'}, ";
                    }
                    ?>
                ]
            }]
        });
        var temp_graph_2 = new CanvasJS.Chart("temp_graph_2", {
            zoomEnabled: true,
            backgroundColor: "#27293d",
            animationEnabled: true,
            theme: "light2",
            title: {
                text: "Temperature  2",
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
                labelAngle: -90/90
            },
            data: [{
                lineColor : "#4f81bc",
                type: "spline",
                dataPoints: [
                    <?php
                    require 'modules/db.php';
                    $sql = "SELECT * FROM api_data WHERE machine_mac='".$device_mac_address."'";
                    //                echo $sql;
                    $res = mysqli_query($con, $sql);
                    while($row=mysqli_fetch_assoc($res)){
                        $time_24 = explode(':', date("H:i", strtotime($row["time_now"])));
                        $a = str_replace("-",",",$row["date_now"]);
                        echo "{ label: '".$row["date_now"]." (".$row["time_now"].")', y: ".$row['Tmp2'].",  color: '#4f81bc'}, ";
                    }
                    ?>
                ]
            }]
        });
        var temp_graph_3 = new CanvasJS.Chart("temp_graph_3", {
            zoomEnabled: true,
            backgroundColor: "#27293d",
            animationEnabled: true,
            theme: "light2",
            title: {
                text: "Temperature  3",
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
                labelAngle: -90/90
            },
            data: [{
                lineColor : "#9bbb58",
                type: "spline",
                dataPoints: [
                    <?php
                    require 'modules/db.php';
                    $sql = "SELECT * FROM api_data WHERE machine_mac='".$device_mac_address."'";
                    //                echo $sql;
                    $res = mysqli_query($con, $sql);
                    while($row=mysqli_fetch_assoc($res)){
                        $time_24 = explode(':', date("H:i", strtotime($row["time_now"])));
                        $a = str_replace("-",",",$row["date_now"]);
                        echo "{ label: '".$row["date_now"]." (".$row["time_now"].")', y: ".$row['Tmp3'].",  color: '#9bbb58'}, ";
                    }
                    ?>
                ]
            }]
        });
        temp_graph_1.render();
        temp_graph_2.render();
        temp_graph_3.render();
/////////////////////////////End Of Temp Graphs/////////////////////////////////////////////////////



        //THree power charts at the end

        var chart_power_1 = new CanvasJS.Chart("power_graph_1", {
            zoomEnabled: true,
            backgroundColor: "#27293d",
            animationEnabled: true,
            theme: "light2",
            title: {
                text: "Power Phase 1",
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
                labelAngle: -90/90
            },
            data: [{
                lineColor : "#9bbb58",
                type: "spline",
                dataPoints: [
                    <?php
                    require 'modules/db.php';
                    $sql = "SELECT * FROM api_data WHERE machine_mac='".$device_mac_address."'";
                    //                echo $sql;
                    $res = mysqli_query($con, $sql);
                    while($row=mysqli_fetch_assoc($res)){
                        $time_24 = explode(':', date("H:i", strtotime($row["time_now"])));
                        $a = str_replace("-",",",$row["date_now"]);
                        echo "{ label: '".$row["date_now"]." (".$row["time_now"].")', y: ".$row['pow1'].",  color: '#9bbb58'}, ";
                    }
                    ?>
                ]
            }]
        });
        var chart_power_2 = new CanvasJS.Chart("power_graph_2", {
            zoomEnabled: true,
            backgroundColor: "#27293d",
            animationEnabled: true,
            theme: "light2",
            title: {
                text: "Power Phase 2",
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
                labelAngle: -90/90
            },
            data: [{
                lineColor : "#c0504e",
                type: "spline",
                dataPoints: [
                    <?php
                    require 'modules/db.php';
                    $sql = "SELECT * FROM api_data WHERE machine_mac='".$device_mac_address."'";
                    //                echo $sql;
                    $res = mysqli_query($con, $sql);
                    while($row=mysqli_fetch_assoc($res)){
                        $time_24 = explode(':', date("H:i", strtotime($row["time_now"])));
                        $a = str_replace("-",",",$row["date_now"]);
                        echo "{ label: '".$row["date_now"]." (".$row["time_now"].")', y: ".$row['pow2'].",  color: '#c0504e'}, ";
                    }
                    ?>
                ]
            }]
        });
        <?php
            require 'modules/db.php';
        $sql = "SELECT * FROM recorded_values1# Where mac='3C:71:BF:8C:08:74'";
        //echo $sql;
        $res = mysqli_query($con, $sql);
        ?>
        var chart_power_3 = new CanvasJS.Chart("power_graph_3", {
            zoomEnabled: true,
            backgroundColor: "#27293d",
            animationEnabled: true,
            theme: "light2",
            title: {
                text: "Power Phase 3",
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
            data: [{
                lineColor : "#4f81bc",
                type: "line",
                dataPoints : [
                    <?php
                    require 'modules/db.php';
                    $sql = "SELECT * FROM api_data WHERE machine_mac='".$device_mac_address."'";
                    //                echo $sql;
                    $res = mysqli_query($con, $sql);
                    while($row=mysqli_fetch_assoc($res)){
                        $time_24 = explode(':', date("H:i", strtotime($row["time_now"])));
                        $a = str_replace("-",",",$row["date_now"]);
                        echo "{ label: '".$row["date_now"]." (".$row["time_now"].")', y: ".$row['pow3'].",  color: '#4f81bc'}, ";
                    }
                    ?>
                ]
            }]
        });
        chart_power_1.render();
        chart_power_2.render();
        chart_power_3.render();




















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