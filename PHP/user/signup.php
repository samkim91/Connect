<?php

  require('../dbconn.php');

  // post로 넘어온 값 받기
  $id = $_POST['SignUpEmail'];
  $pwd = $_POST['SignUpPw'];
  $name = $_POST['SignUpName'];
  $phonenum = $_POST['SignUpPhone'];

  // sql문 유저리스트 테이블에 각 값들 넣기
  $sql = "INSERT INTO userlist (id, password, name, phonenum) VALUES ('$id', '$pwd', '$name', '$phonenum')";

  // 쿼리 날리기
  if($result = mysqli_query($conn, $sql)){
    echo "inserted";
  }else{
    echo "err";
  }


  $conn -> close();

 ?>
