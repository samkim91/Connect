<?php

  require('../dbconn.php');

  // 최초 클라이언트가 계약서를 데이터베이스에 올리는 코드
  $postNum =  str_replace("\"", "", $_POST['postNum']);
  $employer = str_replace("\"", "", $_POST['employer']);
  $employee = str_replace("\"", "", $_POST['employee']);


  // echo $postNum."/".$employer."/".$employee;
  // 파일 가져오는 부분.. 클라이언트로부터 파일을 받아서 이름을 시간을 기준으로 재명명해서 절대값으로 만들고, 서버에 저장한다.
  // DB에 사진주소를 저장하기 위한 값
  $serverURL = "http://34.64.144.139/files/";
  // 이미지를 저장할 폴더를 지정해줌.
  $saveLocation = '../files/';

  // file이라는 태그를 달고 클라이언트로부터 받아온 데이터를 다룬다.
  // $_FILE['태그명']['필요한 내용']에 대해서 보자면 '필요한 내용'에 name이 들어가면 받은 파일의 이름, size가 들어가면 받은 파일의 크기,
  // type은 받은 파일의 형식, tmp_name은 받은 파일의 임시 이름 등을 출력할 수 있다.

  // 안드로이드에서 보낸 파일을 받아서 이름을 바꿔주는 절차를 함.(서버에서 중복되면 안 되기 때문에)
  $fileName = $_FILES['file']['name'];

  // 위 파일의 이름이 파일명.확장자 형식으로 되어 있으니 이것을 쪼갠다.
  $uploadName = explode('.', $fileName);
  // 서버에 저장할 이름이 중복되면 안되기 때문에 시간+키값(몇번째 파일)로 다시 이름을 만들고, 여기에 위에서 빼온 파일확장자를 붙여준다.(uploadName[1]이 확장자)
  $uploadName = time().$key.'.'.$uploadName[1];
  // 저장할 위치와 파일이름을 합쳐서 서버에 저장하기 위한 마무리 작업을 준비한다.
  $uploadFile = $saveLocation.$uploadName;
  // 서버에 본격적으로 저장하는 부분. 이 파일을 위에서 만들어준 이름으로 이동(저장)하겠다는 의미.
  if(move_uploaded_file($_FILES['file']['tmp_name'], $uploadFile)){
    echo "good";
  }else{
    echo "bad";
  }
  // 데이터베이스에 파일 위치를 저장하기 위해 만들어준다.
  $file = $serverURL.$uploadName;

  // echo $fileName."/".$uploadFile."/".$file;

  $sql = "INSERT INTO contract (postNum, employer, employee, employerAgree, docLocation) VALUES
          ('$postNum', '$employer', '$employee', 'agree', '$file')";

  // 쿼리 날리기
  if($result = mysqli_query($conn, $sql)){
    echo "inserted";
  }else{
    echo "err".mysqli_error($conn);
  }

  $conn -> close();


 ?>
