<?php

include 'config.php';


$userID = $userName = $userDept = $userEmail = $userPass = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userID = $_POST['userID'];

    $query = "SELECT * FROM tbl_account WHERE userID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $userID);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($row = mysqli_fetch_assoc($result)) {
        $userName = $row['userName'];
        $userDept = $row['userDept'];
        $userEmail = $row['userEmail'];
        $userPass = $row['userPass'];
    }


    if (isset($_POST['update'])) {

        $updatedUserName = $_POST['userName'];
        $updatedUserDept = $_POST['userDept'];
        $updatedUserEmail = $_POST['userEmail'];
        $updatedUserPass = $_POST['userPass'];
        $retypeUserPass = $_POST['retypeUserPass'];


        if ($updatedUserPass != $retypeUserPass) {
            echo "Passwords do not match. Please try again.";
        } else {

            $updateQuery = "UPDATE tbl_account SET userName=?, userDept=?, userEmail=?, userPass=? WHERE userID=?";
            $updateStmt = mysqli_prepare($conn, $updateQuery);
            mysqli_stmt_bind_param($updateStmt, "ssssi", $updatedUserName, $updatedUserDept, $updatedUserEmail, $updatedUserPass, $userID);
            mysqli_stmt_execute($updateStmt);
            mysqli_stmt_close($updateStmt);

            echo "User updated successfully!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Account</title>
</head>

<body>
    <h2>Edit Account</h2>
    <form action="edit_account" method="post">
        <!-- Include hidden input for user ID -->
        <input type="hidden" name="userID" value="<?php echo $userID; ?>">

        <!-- Display current user information in the form fields -->
        <label for="userName">Organizations Name:</label>
        <input type="text" name="userName" value="<?php echo $userName; ?>" required>
        <br>

        <label for="userDept">Department:</label>
        <input type="text" name="userDept" value="<?php echo $userDept; ?>" required>
        <br>

        <label for="userEmail">Email:</label>
        <input type="email" name="userEmail" value="<?php echo $userEmail; ?>" required>
        <br>

        <label for="userPass">Password:</label>
        <input type="password" name="userPass" required>
        <br>

        <label for="retypeUserPass">Retype Password:</label>
        <input type="password" name="retypeUserPass" required>
        <br>

        <!-- Add other fields as needed -->

        <input type="submit" name="update" value="Update">
    </form>
</body>

</html>