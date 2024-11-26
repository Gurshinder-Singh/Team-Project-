<!-- Bilal section, BASIC TEMPLATE -->
<?php
include 'db_config.php'
if ($_SERVER["REQUEST_METHOD"]=="POST") {
$username= $_POST['username'];
$password= $_POST['password'];
$email=$_POST['email'];


$hashed_passoword= password_hash($password);

$sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$hashed_password')";


if (mysqli_query($conn,$sql){
    echo "registration successful"
}
    else {
    echo "Error" . mysqli_error($conn);
}

mysqli_close($conn);

?>
