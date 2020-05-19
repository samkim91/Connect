<?php

  require('../dbconn.php');

  $num = $_POST['num'];
  $subject = $_POST['subject'];
  $category = $_POST['category'];
  $term = $_POST['term'];
  $cost = $_POST['cost'];
  $content = $_POST['content'];

  $sql = "UPDATE post SET subject = '$subject', category = '$category', term =  '$term', cost = '$cost', content = '$content' WHERE num = '$num'";

  if($result = mysqli_query($conn, $sql)){
    echo "ok";
  }else{
    echo "fail";
  }

  $conn -> close();



 ?>
