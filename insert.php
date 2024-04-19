
<?php
  if( isset( $_POST['firstname'] ) )
  {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $birthdate = $_POST['birthdate'];
    $mail = $_POST['mail'];
    $pwd = $_POST['pwd'];
    $con = mysqli_connect("localhost","robin","robin-mysql","twitter");
    $insert = " INSERT INTO user (lastname,firstname,birthdate,mail,pwd) VALUES( '$lastname','$firstname','$birthdate','$mail','$pwd') ";
    mysqli_query($con, $insert); 
  }
?>