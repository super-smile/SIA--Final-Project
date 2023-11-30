<?php
session_start();
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
        if ($userType == 'organization') {
            header('location: org.php');
        } else {
            $error[] = 'Incorrect email or password';
        }
        
    } else {
        $CuserEmail = $_POST['userEmail'];
        $CuserPass = $_POST['userPass'];

        $Cselect = "SELECT * FROM tbl_office WHERE officeEmail = ? AND officePass = ?";
        $Cstmt = mysqli_prepare($conn, $Cselect);
        mysqli_stmt_bind_param($Cstmt, "ss", $CuserEmail, $CuserPass);
        mysqli_stmt_execute($Cstmt);
        $Cresult = mysqli_stmt_get_result($Cstmt);

        if (mysqli_num_rows($Cresult) > 0) {
            $Crow = mysqli_fetch_array($Cresult);
            $_SESSION['officeAccID'] = $Crow['officeAccID'];
            $_SESSION['designation'] = $Crow['designation'];
            $_SESSION['employeeID'] = $Crow['employeeID'];
            header('location: office.php');
        } else {
            $error[] = 'Incorrect email or password';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleLogin.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href='https://fonts.googleapis.com/css?family=Poppins'>
    <title>Event Request Tracking</title>
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6" id="imgCon">
                <div class="container text-center mx-auto" id="image-container">
                    <h2><img src="logoo.png" alt="" width="350" height="400" class="img-fluid"></h2>
                    <div class="mt-4" style="font-size: 14px; color: white; padding-top: 90px">This website is managed
                        by the Office of the Student Organization <br>at Batangas State University
                        - The NEU Lipa Campus</div>
                </div>
            </div>
            <div class="col-md-6 d-flex align-items-center justify-content-center" id="login-container">
                <div class="container text-center mx-auto" style="padding-top: 30px; padding-right: 100px; padding-left: 100px;">
                    <div class="mx-auto" style="font-family: 'Poppins'; font-size: 41.953px; font-weight: 700;">
                        <strong>LOGIN</strong>
                    </div>
                    <div class="border border-dark w-80"></div>
                    <br>Please login to access your account</br>
                    <p class="error-msg">
                        <?php
                        if (isset($error)) {
                            foreach ($error as $errorMsg) {
                                echo '<span class="error-msg">' . $errorMsg . '</span>';
                            }
                        }
                        ?>
                    </p>
                    <form method="post">
                        <div class="form-group">
                            <input type="text" name="userEmail" class="form-control" id="InputText" placeholder="Email Address*" style="border: 1px solid #444444;" required>
                        </div>
                        <div class="form-group">
                            <input type="password" name="userPass" class="form-control" id="InputPassword" placeholder="Password*" style="border: 1px solid #444444;" required>
                        </div>
                        <button type="submit" name="login" class="btn btn-outline-dark btn-lg btn-block">LOGIN</button>
                    </form>

                    <div class="mt-4" style="font-size: 14px;"><b>Note:</b> If you're experiencing difficulty logging in, please contact the Office of Student Organization for assistance.</div>
                </div>
            </div>
        </div>

        <style>
            .login-as-admin {
                position: fixed;
                bottom: 15px;
                right: 15px;
            }
        </style>

    </div>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
</body>
</html>