<?php
session_start(); // Start a session
include 'config.php';

if (isset($_POST['login'])) {
    $userEmail = $_POST['userEmail'];
    $userPass = $_POST['userPass'];
 
    $select = "SELECT * FROM tbl_account WHERE userEmail = ? AND userPass = ?";
    $stmt = mysqli_prepare($conn, $select);
    mysqli_stmt_bind_param($stmt, "ss", $userEmail, $userPass);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_array($result);
        $_SESSION['userName'] = $row['userName'];
        $_SESSION['userID'] = $row['userID'];
        $_SESSION['userType'] = $row['userType'];

        $userType = $_SESSION['userType'];
        if ($userType == 'OSO') {
            header('location: oso.php');
        }elseif($userType == 'Office'){
            header('location: office.php');
        }else{
            header('location: org.php');
        }
            

        
    }else {
        $error[] = 'Incorrect email or password';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Request Tracking</title>
</head>
<body>
    <h1>Login</h1>
    <?php
    if (isset($error)) {
        foreach ($error as $errorMsg) {
            echo '<span class="error-msg">' . $errorMsg . '</span>';
        }
    }
    ?>
    <form method="post">
        <input type="text" name="userEmail" placeholder="Enter your Email" required>
        <input type="password" name="userPass" placeholder="Enter your Password" required>
        <input type="submit" name="login" value="Login">
    </form>
    <p>Don't have an account? Contact <a href="#">Here!</a></p>
</body>
</html>
