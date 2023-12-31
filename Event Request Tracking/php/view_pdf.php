<?php
include 'config.php';

if (isset($_GET['reqID'])) {
    $reqID = $_GET['reqID'];

    $query = "SELECT reqLetter FROM tbl_requests WHERE reqID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $reqID);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $reqLetter);

    if (mysqli_stmt_fetch($stmt)) {

        header('Content-Type: application/pdf');
        echo $reqLetter;
        exit;
    }
}

http_response_code(404);
echo 'PDF not found';
