<?php
include 'config.php';

if (isset($_POST['submit'])) {
    $userName = $_POST['userName'];
    $userDept = $_POST['userDept'];
    $userEmail = $_POST['userEmail'];
    $userPass = $_POST['userPass'];
    $cuserPass = $_POST['cuserPass'];
    $userType = $_POST['userType'];
    

    // Check if passwords match
    if ($userPass !== $cuserPass) {
        $error[] = 'Passwords do not match';
    } else {
        // Use prepared statements to prevent SQL injection
        $select = "SELECT * FROM tbl_account WHERE userEmail = ?";
        $stmt = mysqli_prepare($conn, $select);
        mysqli_stmt_bind_param($stmt, "s", $userEmail);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) > 0) {
            $error[] = 'User already exists!';
        } else {
            // Use prepared statement for inserting data
            $insert = "INSERT INTO tbl_account(userName, userDept, userEmail, userPass, userType) VALUES(?, ?, ?, ?, ?)";
            $stmt = mysqli_prepare($conn, $insert);
            mysqli_stmt_bind_param($stmt, "sssss", $userName, $userDept, $userEmail, $userPass, $userType);
            mysqli_stmt_execute($stmt);
            header('location:login.php');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="form-container">
        <h1>Register</h1>
        <?php
        if (isset($error)) {
            foreach ($error as $errorMsg) {
                echo '<span class="error-msg">' . $errorMsg . '</span>';
            }
        }
        ?>
        <form method="post">
            <input type="text" name="userName" placeholder="Enter Organization Name" required>
            <input type="text" name="userDept" placeholder="Enter your Department Name" required>
            <input type="text" name="userEmail" placeholder="Enter your Email" required>
            <input type="password" name="userPass" placeholder="Enter your password" required>
            <input type="password" name="cuserPass" placeholder="Confirm your password" required>
            <p>Select User Type:</p>
            <select name="userType">
                <option value="Organization">Organization</option>
                <option value="OSO">OSO</option>
                <option value="Office">Office</option>
            </select>
            <input type="submit" name="submit" value="Register" class="form-btn">
        </form>
        <p>Already have an account? <a href="login.php">Login</a></p>
    </div>
</body>
</html>
