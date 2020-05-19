<?php

  require('../dbconn.php');

  $request = $_GET['request'];

  $sql = "SELECT f.id, f.title, f.skill, f.introduce, f.location, f.image, u.name, u.phonenum,
          (SELECT AVG(score) FROM review WHERE listener = f.id) rate, (SELECT COUNT(num) FROM review WHERE listener = f.id) review,
          (SELECT COUNT(num) FROM contract WHERE employee = f.id) hiredNumber
          FROM freelancer f INNER JOIN userlist u ON f.id = u.id";

  // 쿼리 날리기
  $result = mysqli_query($conn, $sql);

  // 쿼리 결과가 한줄 이상이라면 이름 에코 보내고 없으면 아무도 없다고 알려줌
  if(mysqli_num_rows($result) > 0){

    $freelancers = array();

    while ($row = mysqli_fetch_assoc($result)) {
      $freelancers[] = $row;
    }
    echo json_encode($freelancers);
  }else{
    echo "no result".mysqli_error($conn);
  }

  $conn -> close();
 ?>
