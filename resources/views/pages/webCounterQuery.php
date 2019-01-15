<?php
    // define variables and set to empty values
$dtSt = $dtEd = $period= "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $dtSt = inputData($_POST["dtSt"]);
  $dtEd = inputData($_POST["dtEd"]);
  $period = inputData($_POST["period"]);
}
function inputData($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
//echo $dtSt."/".$dtEd."/".$period."<br>";
?>
<html>
<head>
<title>用電家計簿-瀏覽次數查詢</title>
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css" integrity="sha384-9gVQ4dYFwwWSjIDZnLEWnxCjeSWFphJiwGPXr1jddIhOegiu1FwO5qRGvFXOdJZ4" crossorigin="anonymous">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css"/>
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.5.2/css/buttons.dataTables.min.css"/>

</head>
<body>
<div class='container'>
<div class="form-group">
<h1>用電家計簿-瀏覽次數查詢</h1><a href="manage" class="btn btn-link" style=" position: fixed;top: 0; right: 10%;">返回管理</a>
</div>
<hr>
<form method='POST' action='/webCounterQuery'>
<input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
<div>
	開始:<input type='text' name='dtSt' class='datepicker' placeholder='開始日期' value='<?php echo $dtSt; ?>'/>
<br>
	結束:<input type='text' name='dtEd' class='datepicker' placeholder='結束日期' value='<?php echo $dtEd; ?>'/>
<br>
    統計區間：
  <input type="radio" name="period" value="day" <?php if($period=="day" or $period==""){echo "checked";} ?> > 日
  <input type="radio" name="period" value="week" <?php if($period=="week"){echo "checked";} ?> > 週
  <input type="radio" name="period" value="month" <?php if($period=="month"){echo "checked";} ?> > 月
  <input type="radio" name="period" value="year" <?php if($period=="year"){echo "checked";} ?> > 年
</div>

<div>
<input type="submit" class='btn btn-primary' name="submit" value='查詢'/>
</div>
</form>
<hr>
<div>
    查詢結果:<br>
<?php
$servername = "localhost";
$username = "root";
$password = "monoame";
$dbname = "itri2017";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
/*if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully <br><br>";
*/
if($dtSt==""){
$dtSt=" DATE_SUB(CURDATE(), INTERVAL 3 DAY) ";
}else{
$dtSt="'".$dtSt."'";
}
if($dtEd==""){
$dtEd=" DATE_ADD(CURDATE(),INTERVAL 1 DAY) ";
}else{
$dtEd_date=new DateTime($dtEd);
$dtEd_date=$dtEd_date->modify('+1 day');
$dtEd="'".$dtEd_date->format('Y-m-d')."'";
}
$groupByPeriod="";
switch ($period) {
	case "week":
        $groupByPeriod=" DATE_FORMAT(`created_at`,'%Y-W%u') ";
        break;
	case "month":
        $groupByPeriod=" DATE_FORMAT(`created_at`,'%Y-%m') ";
        break;
    case "year":
        $groupByPeriod=" DATE_FORMAT(`created_at`,'%Y') ";
        break;
    default: //"day" or ""
        $groupByPeriod=" DATE_FORMAT(`created_at`,'%Y-%m-%d') ";
}
$sql = "SELECT ".$groupByPeriod." as `DT`,count(*)\n"
    . "FROM `web_counter` WHERE `created_at` BETWEEN ".$dtSt." AND ".$dtEd." \n"
    . "group by ".$groupByPeriod." \n"
	. "union ALL \n"
	. "select '加總', count(*) \n"
	. "FROM `web_counter` \n"
	. "WHERE `created_at` BETWEEN ".$dtSt." AND ".$dtEd." \n"
	. "ORDER BY if(`DT`='加總',0,`DT`) desc";

//echo $sql."<br>";
//die();
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    echo "瀏覽統計";
	echo "<table class='table table-sm table-hover table-bordered'><thead><tr class='table-primary'><th>Date</th><th>瀏覽次數</th></tr></thead><tbody>";
    while($row = $result->fetch_array()) {
        //echo "id: " . $row["id"]. " - uuid: " . $row["uuid"]. " " . $row["county"]. "<br>";
        echo "<tr><td>" . $row[0] ."</td><td>".$row[1]."</td></tr>";
    }
	echo "</tbody></table><br>";
} else {
    echo "0 results";
}
$sql = "SELECT `id`, `uuid`, `IP`, `created_at` FROM `web_counter` WHERE `created_at` BETWEEN ".$dtSt." AND ".$dtEd." ORDER BY created_at desc";
// echo($sql);
// die();
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
    echo "<div>明細</div>";
    echo "<table id='tblDetail' class='table table-sm table-hover table-bordered'><thead><tr class='table-primary'><th>id</th><th>uuid</th><th>IP</th><th>DateTime</th></tr></thead><tbody>";
    while($row = $result->fetch_array()) {
        echo "<tr><td>" . $row[0]. "</td><td>" . $row[1]. "</td><td>" . $row[2]."</td><td>".$row[3]. "</td></tr>";
    }
	echo "</tbody></table>";
} else {
    echo "0 results";
}

$conn->close();

?>
</div>

<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js" integrity="sha384-cs/chFZiN24E4KMATLdqdvsezGxaGsi4hLGOzlXwp5UZB1LY//20VyM2taTB4QvJ" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js" integrity="sha384-uefMccjFJAIv6A+rW+L4AHf99KvxDjWSu1z9VI8SKNVmz4sk7buKt/6v9KI65qnm" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/dataTables.buttons.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.flash.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.html5.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/buttons/1.5.2/js/buttons.print.min.js"></script>

<script>
  $( function() {
    $( ".datepicker" ).datepicker({ dateFormat: 'yy-mm-dd' });
      //$( ".datepicker" ).datepicker( "option", "dateFormat", "yy-mm-dd" );
      $('#tblDetail').DataTable({
        dom: 'lBfrtip',
         buttons: ['excel']
      });
  } );
</script>
</div>
</body>
</html>