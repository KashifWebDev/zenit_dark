
<?php
if(isset($_POST["download"])){
    $start_date = $_POST["range1"];
    $end_date = $_POST["range2"];
    $file_name = $_POST["chart_type"];
    require 'db.php';
    $output = "";
    $sql = "SELECT * FROM recorded_values1 WHERE (date_now BETWEEN '$start_date' AND '$end_date')";
    $res = mysqli_query($con, $sql);
    if(mysqli_num_rows($res)){
        $output .= '
            <table class="table" border="1">
                <tr>
                    <th>ID</th>
                    <th>Value</th>
                    <th>Date</th>
                    <th>Time</th>
                </tr>
        ';
        while ($row = mysqli_fetch_array($res)){
            $output .= '
                <tr>
                    <td>'.$row["id"].'</td>
                    <td>'.$row["current_values"].'</td>
                    <td>'.$row["date_now"].'</td>
                    <td>'.$row["time_now"].'</td>
                </tr>
        ';
        }
        $output .=" </table>";
        header("Content-Type: application/xls");
        header("Content-Disposition:attachment; filename=".$file_name.".xls");
        echo $output;
    }
}
?>