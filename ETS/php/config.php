<?php
$conn = mysqli_connect('localhost','root','','db_ba3101');

if($conn->connect_error){
    echo $conn->connect_error;
}

?>