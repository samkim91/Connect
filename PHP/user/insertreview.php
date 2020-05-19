<?php

  require('../dbconn.php');

  $speaker = $_GET['speaker'];
  $listener = $_GET['listener'];
  $score = $_GET['score'];
  $content = $_GET['content'];

  $sql = "INSERT INTO review (speaker, listener, score, content) VALUES ('$speaker', '$listener', '$score', '$content')";

  // 쿼리 날리기
  if($result = mysqli_query($conn, $sql)){
    echo "inserted";
  }else{
    echo "err";
  }

  $conn -> close();

 ?>
