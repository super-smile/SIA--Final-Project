<?php
$conn = mysqli_connect('localhost','root','','db_eventtracking');

if($conn->connect_error){
    echo $conn->connect_error;
}

?>