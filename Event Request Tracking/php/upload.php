<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "db_ba3101";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $reqEventName = $_POST["eventName"];
    $reqEventDate = $_POST["eventDate"];
    $userID = $_SESSION["userID"]; // Assuming you store userID in the session when the user logs in

    // Determine the initial officeID based on your logic
    $initialOfficeID = determineInitialOffice($userID); // Implement this function based on your business logic

    // Handle file upload
    $targetDir = "uploads/"; // Adjust the target directory as needed

    $targetFile = $targetDir . basename($_FILES["pdfFile"]["name"]);

    if (move_uploaded_file($_FILES["pdfFile"]["tmp_name"], $targetFile)) {
        // File uploaded successfully, now insert data into tbl_requests
        $reqDeadline = date("Y-m-d", strtotime($reqEventDate . "+7 days")); // Calculate reqDeadline

        $sql = "INSERT INTO tbl_requests (reqEventName, reqLetter, reqEventDate, userID, reqDeadline) VALUES ('$reqEventName', '$targetFile', '$reqEventDate', '$userID', '$reqDeadline')";

        if ($conn->query($sql) === TRUE) {
            // Get the ID of the last inserted request
            $requestID = $conn->insert_id;

            // Insert data into tbl_reqhistory
            $reqStatus = "Pending";
            $statusDate = date("Y-m-d");

            $sqlHistory = "INSERT INTO tbl_reqhistory (reqStatus, statusDate, orgID, reqID, officeID) VALUES ('$reqStatus', '$reqDeadline', '$userID', '$requestID', '$initialOfficeID')";

            if ($conn->query($sqlHistory) === TRUE) {
                echo "Request submitted successfully.";
            } else {
                echo "Error: " . $sqlHistory . "<br>" . $conn->error;
            }
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Error uploading file.";
    }
}

$conn->close();

// Implement your logic to determine the initial officeID
function determineInitialOffice($userID)
{
    // Your logic to determine the initial officeID goes here
    // You might query the database or use some other logic to determine the officeID
    // For now, assuming officeID is hardcoded, but you should replace it with your logic
    return "1";
}
