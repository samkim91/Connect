<?php

  require('../dbconn.php');

  $postNum = $_POST['postNum'];
  $speaker = $_POST['speaker'];
  $listener = $_POST['listener'];
  $price = $_POST['price'];
  $state = $_POST['state'];

  // 이미 유저가 투찰을 했는지 검사함.
  $sql = "SELECT * FROM bidding WHERE postNum = '$postNum' AND speaker = '$speaker'";
  // 쿼리 날리기
  $result = mysqli_query($conn, $sql);

  if(mysqli_num_rows($result) > 0){
    // 이미 투찰한 경우라면 값만 바꿔준다.
    $sql = "UPDATE bidding SET price = '$price' WHERE postNum = '$postNum' AND speaker = '$speaker'";
  }else{
    // 투찰한 적이 없다면 새로 넣어준다.
    $sql = "INSERT INTO bidding (postNum, speaker, listener, price, state) VALUES ('$postNum', '$speaker', '$listener', '$price', '$state')";
  }

  if($result = mysqli_query($conn, $sql)){
    echo "ok";
  }else{
    echo "fail".mysqli_error($conn);
  }

  $conn -> close();

 ?>
