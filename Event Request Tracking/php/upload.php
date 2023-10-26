<?php
include 'config.php';
session_start();

if (isset($_POST['upload'])) {
    $eventName = $_POST['eventName'];
    $eventDate = $_POST['eventDate'];
    $pdfName = $_FILES['reqLetter']['name'];
    $pdfTmpName = $_FILES['reqLetter']['tmp_name'];

    if ($_FILES['reqLetter']['error'] === UPLOAD_ERR_OK) {
        $pdfData = file_get_contents($pdfTmpName);
        $userID = $_SESSION['userID'];

        $insertRequest = "INSERT INTO tbl_requests(reqLetter, reqEventName, reqEventDate, userID) VALUES(?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insertRequest);
        mysqli_stmt_bind_param($stmt, "ssss", $pdfData, $eventName, $eventDate, $userID);
        mysqli_stmt_execute($stmt);

        $lastReqID = mysqli_insert_id($conn);

        $reqStatus = "Pending";
        $statusDate = date("Y-m-d");
        $reqDeadline = date("Y-m-d", strtotime("+14 days"));

        $insertHistory = "INSERT INTO tbl_reqhistory(reqStatus, statusDate, reqDeadline, userID, reqID) VALUES(?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $insertHistory);
        mysqli_stmt_bind_param($stmt, "sssss", $reqStatus, $statusDate, $reqDeadline, $userID, $lastReqID);
        mysqli_stmt_execute($stmt);

        header('location: home.php');
    } else {
        $error[] = 'File upload error: ' . $_FILES['reqLetter']['error'];
    }
}
?>
