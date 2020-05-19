<?php

  require('../dbconn.php');

  $id = $_POST['id'];
  $subject = $_POST['subject'];
  $category = $_POST['category'];
  $term = $_POST['term'];
  $cost = $_POST['cost'];
  $content = $_POST['content'];

  $sql = "INSERT INTO post (id, subject, category, term, cost, content) VALUES ('$id', '$subject', '$category', '$term', '$cost', '$content')";

  if($result = mysqli_query($conn, $sql)){
    echo "ok";
  }else{
    echo "fail";
  }

  $conn -> close();



 ?>
