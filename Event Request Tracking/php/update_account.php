<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userID = $_POST['userID'];
    $userName = $_POST['userName'];
    $userDept = $_POST['userDept'];
    $userEmail = $_POST['userEmail'];
    $userPass = $_POST['userPass'];
    $retypeUserPass = $_POST['retypeUserPass'];

    if ($userPass !== $retypeUserPass) {
        echo '<script>
                alert("Passwords do not match! Please retype the password correctly.");
                window.history.back();
              </script>';
        exit();
    }

    $query = "UPDATE tbl_account 
              SET userName = ?, userDept = ?, userEmail = ?, userPass = ? 
              WHERE userID = ?";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "sssss", $userName, $userDept, $userEmail, $userPass, $userID);
    $success = mysqli_stmt_execute($stmt);

    if ($success) {
        echo '<script>
                alert("Update successful!");
                window.location.href = "oso.php";
              </script>';
    } else {
        echo "Update failed. Please try again.";
    }
} else {
    echo "Invalid request method.";
}
