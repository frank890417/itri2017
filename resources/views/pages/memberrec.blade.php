@extends('layouts.app')

@section('header')
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" />
@endsection

@section('content')
<form method='POST' action='/memberrec'>
{{ csrf_field() }}

<div class="container">
    <div class="row">
		<div class="col-md-12" style="overflow-x: auto;">
            <div class="panel panel-default">
                <div class="panel-heading">電器診斷歷史資料查詢 | <a href='/home'>返回上頁>></a></div>
                <div class="panel-body">                  
<?php
$users_id= @Auth::user()->id ;
$servername = "localhost";
$username = "root";
$password = "monoame";
$dbname = "itri2017";
$character_set="utf8mb4";
// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
mysqli_set_charset($conn,$character_set);
$sql = "SELECT t1.`updated_at`, IF(`county`=\"-1\",\"未選取\",`county`) \"縣市\", `member_count` \"家庭成員\", `area_size` \"坪數\", if(`summer`=\"true\",\"夏月\",\"非夏月\") \"近期用電\", `degree` \"度數\", `money` \"流動電費\", IF(IFNULL(sum(t3.device_consumption),0)=0,0,count(*)) \"填寫電器數\", sum(t3.device_consumption) \"總消耗度數(年)\", t1.`uuid` \n"
    . "FROM `userdetails` t1 join `users` t2 on t1.users_id=t2.id LEFT JOIN `devicelogs` t3 on t1.uuid=t3.uuid\n"
    . "WHERE t1.users_id=\"".$users_id."\" \n"
    . "GROUP BY t1.updated_at, t1.uuid, county, member_count, area_size, summer, degree, money\n"
    . "ORDER BY t1.`updated_at` desc";
//echo $sql;
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    // output data of each row
	echo "<table class='table table-sm table-hover table-bordered' id='tblUserDetail'><thead><tr class='table-primary'><th>時間</th><th>縣市</th><th>家庭成員</th><th>坪數</th><th>近期用電</th><th>度數</th><th>流動電費</th><th>填寫電器數</th><th>總消耗度數(年)</th><th>明細</th></tr></thead><tbody>";
    while($row = $result->fetch_array()) {
        echo "<tr><td>" . $row[0] ."</td><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]."</td><td>".$row[4]."</td><td>".$row[5]."</td><td>".$row[6]."</td><td>".$row[7]."</td><td>".$row[8]."</td><td><input type='submit' class='btn btn_detail' style='opacity: 1;background-color:#000000; color:#fff;' value='查詢' data-uuid='".$row[9]."' /></td></tr>";
    }
	echo "</tbody></table><br>";
} else {
    echo "0 results";
}
//$conn->close();
?>
<input name="uuid" id="hf_uuid" type=hidden value="">
				</div>
            </div>
        </div>
    </div>
<?php 
$uuid = @$uuid_value ;
//echo("uuid:".$uuid);
$sql = "SELECT t2.place, t2.name, t1.count, t1.device_consumption, t1.use_time, t1.buy_time\n"
    . "FROM `devicelogs` t1, `devices` t2\n"
    . "WHERE t1.device_id=t2.id\n"
    . "AND t1.uuid=\"".$uuid."\"";
$result = $conn->query($sql);
if ($uuid!=""){
  if ($result->num_rows > 0) {
      // output data of each row
  echo "<div id='divDetail'>詳細資料 \n"
  ."<table class='table table-sm table-hover table-bordered' id='tblDetail' style='background-color:#fff;'><thead><tr class='table-primary'><th>地點</th><th>電器</th><th>數量</th><th>度數（填寫)</th><th>使用(小時/日)</th><th>購買(年)</th></tr></thead><tbody>";
  while($row = $result->fetch_array()) {
          echo "<tr><td>" . $row[0] ."</td><td>".$row[1]."</td><td>".$row[2]."</td><td>".$row[3]."</td><td>".$row[4]."</td><td>".$row[5]."</td></tr>";
      }
  echo "</tbody></table><br></div>";
  } else {
      echo "0 results";
  }
}
  $conn->close();
?>
    <img src="/img/場景/Kitchen2.png" class="scene" id="imgBG" style="position: absolute; width: 80vw; right: -40vw; top: -10vw; mix-blend-mode: color-burn; pointer-events: none;" >
    <div class="row">
        <div class="col-sm-4 text-right">
            <img src="/img/thunder.svg" width="45">
            <h4>Comsumption of daily life</h4>
            <h1>家庭電器用電<br class="hidden-xs">家計簿</h1>
            <h5>尋找家中的吃電怪獸</h5><br>
			<a class="btn btn-lg" href="/" style="background-color: #4d4d4d; color: white; transition: 0.5s 0s, font-size 0.5s, line-height 0.5s; border-radius: 0%; letter-spacing: 2px;">前往探索</a>
        </div>  
    </div>
</div>
<BR>
</form>
@endsection

@section('script')
<script src="https://code.jquery.com/jquery-3.3.1.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>

<script>
$(function(){
  $(".btn_detail").click(function(){
    //$("#divDetail").show("slow");
    $("#hf_uuid").val($(this).attr("data-uuid"));
  })
  
  $("#tblUserDetail").DataTable({
  "order": [0,"desc"], 
  stateSave: true,
  stateDuration: 60 * 30, /* 30 mins */
  });

  $(".btn_detail").each(function(){
   if($(this).attr("data-uuid")=="{{$uuid}}"){
     $(this).parent().parent().css("color","blue")
   }
  })
    
  //alert('{{@$uuid_value}}')
})
</script>
@endsection