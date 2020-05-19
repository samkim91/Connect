<?php

  require('../dbconn.php');

  //str_replace("제거할 문자", "대체할 문장", $xxx); 문자열 제거
  $id = str_replace("\"", "", $_POST['id']);
  $title = str_replace("\"", "", $_POST['listTitle']);
  // $name = str_replace("\"", "", $_POST['listName']);
  $skill = str_replace("\"", "", $_POST['listSkill']);
  $introduce = str_replace("\"", "", $_POST['listIntroduce']);
  $location = str_replace("\"", "", $_POST['listLocation']);

  // 이미지 처리해주는 부분.. 클라이언트로부터 이미지를 받아서 파일 이름을 시간을 이용해서 절대값으로 만들고, 서버에 저장한다.
  // DB에 사진주소를 저장하기 위한 값
  $serverURL = "http://34.64.144.139/images/";
  // 이미지를 저장할 폴더를 지정해줌.
  $saveLocation = '../images/';

  // 클라이언트에서 보낸 파일들을 하나씩 받아와 이름을 생성해주고 다시 보낼 준비를 한다.
  // 다수 이미지를 저장하기 위해 foreach문을 사용함.
  // files라는 태그를 달고 클라이언트로부터 받아온 데이터를 다룬다.
  // $_FILES['태그명']['필요한 내용']에 대해서 보자면 '필요한 내용'에 name이 들어가면 받은 파일의 이름, size가 들어가면 받은 파일의 크기,
  // type은 받은 파일의 형식, tmp_name은 받은 파일의 임시 이름 등을 출력할 수 있다.

  // 안드로이드에서 보낸 파일을 받아서 이름을 바꿔주는 절차를 함.(서버에서 중복되면 안 되기 때문에)
  $imageName = $_FILES['file']['name'];

  // 위 파일의 이름이 파일명.확장자 형식으로 되어 있으니 이것을 쪼갠다.
  $uploadName = explode('.', $imageName);
  // 서버에 저장할 이름이 중복되면 안되기 때문에 시간+키값(몇번째 파일)로 다시 이름을 만들고, 여기에 위에서 빼온 파일확장자를 붙여준다.(uploadName[1]이 확장자)
  $uploadName = time().$key.'.'.$uploadName[1];
  // 저장할 위치와 파일이름을 합쳐서 서버에 저장하기 위한 마무리 작업을 준비한다.
  $uploadFile = $saveLocation.$uploadName;
  // 서버에 본격적으로 저장하는 부분. 이 파일을 위에서 만들어준 이름으로 이동(저장)하겠다는 의미.
  move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile);
  // 데이터베이스에 파일 위치를 저장하기 위해 만들어준다.
  $image = $serverURL.$uploadName;

  $sql = "INSERT INTO freelancer (id, title, skill, introduce, location, image) VALUES ('$id', '$title', '$skill', '$introduce', '$location', '$image')
          ON DUPLICATE KEY UPDATE id = '$id', skill = '$skill', introduce = '$introduce', location = '$location', image = '$image'";

  if($result = mysqli_query($conn, $sql)){
    echo "ok";
  }else{
    echo "fail".mysqli_error($conn);
  }

  $conn -> close();

 ?>
