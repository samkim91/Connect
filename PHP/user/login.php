<?php

  require('../dbconn.php');

  // post로 넘어온 값들 받기
  $id = $_POST['login_id'];
  $pwd = $_POST['login_pw'];

  // sql문 만들기
  $sql = "SELECT * FROM userlist WHERE id = '$id' AND password = '$pwd'";

  // 쿼리 날리기
  $result = mysqli_query($conn, $sql);

  // 쿼리 결과가 한줄 이상이라면 이름 에코 보내고 없으면 아무도 없다고 알려줌
  if(mysqli_num_rows($result) > 0){

    $userinfo = array();

    while ($row = mysqli_fetch_assoc($result)) {
      $userinfo[] = $row;
    }
    echo json_encode($userinfo);
  }else{
    echo "no result";
  }

  $conn -> close();

 ?>
