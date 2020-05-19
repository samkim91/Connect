<?php

  require('../dbconn.php');

  $suggester = $_POST['speaker'];
  $receiver = $_POST['listener'];
  $content = $_POST['content'];
  $state = $_POST['state'];

  // 기존에 고용을 보냈었는지 확인함.
  $sql = "SELECT * FROM bidding WHERE suggester = '$suggester' AND receiver = '$receiver'";

  // 쿼리 날리기
  $result = mysqli_query($conn, $sql);

  if(mysqli_num_rows($result) > 0){
    // 보낸 적이 있다고 하면, 상태를 waiting으로 바꿔줌
    $sql = "UPDATE bidding SET state = 'waiting' WHERE suggester = '$suggester' AND receiver = '$receiver'";
  }else{
    // 보낸 적이 없다면, 고용하는 내용을 넣음.
    $sql = "INSERT INTO bidding (suggester, receiver, content) VALUES ('$suggester', '$receiver', '$content')";
  }

  if($result = mysqli_query($conn, $sql)){
    echo "ok";
  }else{
    echo "fail".mysqli_error($conn);
  }

  $conn -> close();

 ?>
