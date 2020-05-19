<?php

  require('../dbconn.php');
  // 프리랜서든 클라이언트든 들어오면, 계약서내용을 보여주는 곳이다.

  $employer = $_POST['employer'];
  $employee = $_POST['employee'];

  $sql = "SELECT * FROM contract WHERE employer = '$employer' AND employee = '$employee' ORDER BY date DESC limit 1";

  // 쿼리 날리기
  $result = mysqli_query($conn, $sql);

  // 쿼리 결과가 한줄 이상이라면 이름 에코 보내고 없으면 아무도 없다고 알려줌
  if(mysqli_num_rows($result) > 0){

    $contract = array();

    while ($row = mysqli_fetch_assoc($result)) {
      $contract[] = $row;
    }
    echo json_encode($contract);
  }else{
    echo "no result".mysqli_error($conn);
  }

  $conn -> close();

 ?>
