<?php

  require('../dbconn.php');

  // 클라이언트에서 받아온 카테고리
  $category = $_GET['category'];
  // 클라이언트에서 받아온 유저아이디
  $userId = $_GET['userId'];

  // 카테고리가 all 이면 그냥 쿼리, 아니면 해당 조건에 맞는 쿼리를 만듦
  if($category == 'all'){
    // 주 쿼리는 포스트 내용을 클라이언트측에 전달해주는 것인데, 부가적으로 포스트 내용에 투찰한 사람들의 숫자를 구해서 같이 보내줌.
    $sql = "SELECT p.*, (SELECT COUNT(distinct b.speaker) FROM bidding b WHERE b.postNum = p.num) bidderNum FROM post p";
  }else{
    $sql = "SELECT * FROM post p LEFT JOIN bidding b ON p.num = b.postNum WHERE p.category = '$category'";
  }

  if($userId != null){
    // 주 쿼리는 포스트 내용을 클라이언트측에 전달해주는 것인데, 부가적으로 포스트 내용에 투찰한 사람들의 숫자를 구해서 같이 보내줌.
    $sql = "SELECT p.*, (SELECT COUNT(distinct b.speaker) FROM bidding b WHERE b.postNum = p.num) bidderNum FROM post p WHERE id = '$userId'";
  }

  // 쿼리를 보냄
  $result = mysqli_query($conn, $sql);

  // 결과가 1개 이상이면, 클라이언트로 반환해주기 위한 작업을 함
  if(mysqli_num_rows($result) > 0){
    $data = array();

    // 어레이에 행을 모두 담는다.
    while($row = mysqli_fetch_assoc($result)){
      $data[] = $row;
    }

    // 어레이를 제이슨화 해서 보내준다.
    echo json_encode($data);

  }else{
    // 아무것도 없을때
    echo "err".mysqli_error($conn);
  }

  $conn -> close();

 ?>
