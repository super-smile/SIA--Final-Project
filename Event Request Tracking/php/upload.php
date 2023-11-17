<?php
session_start();
include("config.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $reqEventName = $_POST["eventName"];
    $reqEventDate = $_POST["eventDate"];
    $userID = $_SESSION["userID"]; // Assuming you store userID in the session when the user logs in

    $targetFile = $_FILES["pdfFile"]["tmp_name"]; // Use tmp_name to get the temporary file path

    $fileContent = file_get_contents($targetFile);

    // File uploaded successfully, now insert data into tbl_requests
    $reqDeadline = date("Y-m-d", strtotime($reqEventDate . "+7 days")); // Calculate reqDeadline

    // Use prepared statements to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO tbl_requests (reqEventName, reqLetter, reqEventDate, userID, reqDeadline) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $reqEventName, $fileContent, $reqEventDate, $userID, $reqDeadline);

    if ($stmt->execute()) {
        $successMessage = "File uploaded and data inserted successfully.";
        echo "<script>alert('$successMessage'); window.location.replace('org.php');</script>";
    } else {
        echo "Error inserting data into the database.";
    }

    $stmt->close();
}

$conn->close();
