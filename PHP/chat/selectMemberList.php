<?php
  // 채팅방 목록에 보여질 내용을 가져오는 스크립트.

  // 클라이언트라면 bidding 테이블에서 accept된 프리랜서 아이디를 다 가져와서 유저리스트에서 아이디, 닉네임을 출력해야함.
  // 프리랜서라면 bidding 테이블에서 accept된 클라이언트 아이디를 다 가져와서 유저리스트에서 아이디, 닉네임을 출력해야함.
  require('../dbconn.php');

  $id = $_POST['id'];

  
  // 들어온 유저가 프리랜서다. 그렇다면 클라이언트를 뽑아야한다.
  $sql = "SELECT id, name FROM userlist WHERE id IN (SELECT listener FROM bidding WHERE speaker = '$id' AND state = 'accept')
          OR id IN (SELECT speaker FROM bidding WHERE listener = '$id' AND state = 'accept')";

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
