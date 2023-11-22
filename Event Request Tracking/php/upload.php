<?php
session_start();
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reqEventName = $_POST["eventName"];
    $reqEventDate = $_POST["eventDate"];
    $userID = $_SESSION["userID"];

    // Check for errors during file upload
    if ($_FILES["pdfFile"]["error"] !== UPLOAD_ERR_OK) {
        echo "Error uploading file.";
        exit();
    }

    $targetFile = $_FILES["pdfFile"]["tmp_name"];

    $fileContent = file_get_contents($targetFile);

    $reqDeadline = date("Y-m-d", strtotime($reqEventDate . "-7 days"));

    $stmt = $conn->prepare("INSERT INTO tbl_requests (reqEventName, reqLetter, reqEventDate, userID, reqDeadline) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $reqEventName, $fileContent, $reqEventDate, $userID, $reqDeadline);

    // Check if the SQL statement is prepared successfully
    if ($stmt === false) {
        echo "Error preparing SQL statement.";
        exit();
    }

    if ($stmt->execute()) {
        $successMessage = "File uploaded and data inserted successfully.";
        echo "<script>alert('$successMessage'); window.location.replace('org.php');</script>";
    } else {
        // Print SQL error for debugging
        echo "Error inserting data into the database: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
