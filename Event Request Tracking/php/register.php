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
            header('location:oso.php');
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="osoStyles.css">
    <title>Document</title>
</head>

<body>
    <div class="form-container">
        <?php
        if (isset($error)) {
            foreach ($error as $errorMsg) {
                echo '<span class="error-msg">' . $errorMsg . '</span>';
            }
        }
        ?>
        <div class="container">
            <form method="post">
                <div class="form-group">
                    <label for="userName">Organization Name:</label>
                    <input type="text" id="userName" name="userName" class="form-control"
                        placeholder="Enter Organization Name" required>
                </div>
                <div class="form-group">
                    <label for="userDept">Department Name:</label>
                    <input type="text" id="userDept" name="userDept" class="form-control"
                        placeholder="Enter your Department Name" required>
                </div>
                <div class="form-group">
                    <label for="userEmail">Email:</label>
                    <input type="text" id="userEmail" name="userEmail" class="form-control"
                        placeholder="Enter your Email" required>
                </div>
                <div class="form-group">
                    <label for="userPass">Password:</label>
                    <input type="password" id="userPass" name="userPass" class="form-control"
                        placeholder="Enter your password" required>
                </div>
                <div class="form-group">
                    <label for="cuserPass">Confirm Password:</label>
                    <input type="password" id="cuserPass" name="cuserPass" class="form-control"
                        placeholder="Confirm your password" required>
                </div>
                <div class="form-group">
                    <label for="userType">Select User Type:</label>
                    <select id="userType" name="userType" class="form-control">
                        <option value="Organization">Organization</option>
                        <option value="OSO">OSO</option>
                        <option value="Office">Office</option>
                    </select>
                </div>
                <input type="button" name="submit" value="Register" class="form-btn">
            </form>
        </div>
    </div>


</body>

</html>