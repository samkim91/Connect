<?php

  require('../dbconn.php');

  $postNum = $_POST['num'];
  $speaker = $_POST['id'];
  $state = $_POST['state'];

  // 클라이언트가 프리랜서의 입찰을 받을지 안받을지 선택하는 부분.. 테이블에 입찰에 대한 내용이 들어감.
  $sql = "UPDATE bidding SET state = '$state' WHERE postNum = '$postNum' AND speaker = '$speaker'";

  if($result = mysqli_query($conn, $sql)){
    echo "ok";
  }else{
    echo "fail".mysqli_error($conn);
  }

  $conn -> close();

 ?>
