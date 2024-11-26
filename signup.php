<!-- Bilal section -->
<?php
include 'db_config.php'
if($_SERVER["REQUEST_METHOD"]=="POST") {
$username= $_POST['Username'];
$password= $_POST['Password'];


if($password != $confirm_password){
  echo "Password does not match";
}
else{
  $hashed_passoword= password_hash($password);
  




?>
