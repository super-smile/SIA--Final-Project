<?php
include 'config.php';

$errors = [];

if (isset($_POST['submit'])) {
    $userName = $_POST['userName'];
    $userDept = $_POST['userDept'];
    $userEmail = $_POST['userEmail'];
    $userPass = $_POST['userPass'];
    $cuserPass = $_POST['cuserPass'];
    $userType = "Organization";

    // Check if passwords match
    if ($userPass !== $cuserPass) {
        $errors[] = 'Passwords do not match';
    } else {
        // Check if an image is selected
        if (!empty($_FILES['userImage']['name'])) {
            // Get the contents of the image file
            $imagePath = $_FILES['userImage']['tmp_name'];
            $imageData = file_get_contents($imagePath);

            // Use prepared statements to prevent SQL injection
            $select = "SELECT * FROM tbl_account WHERE userEmail = ?";
            $stmt = mysqli_prepare($conn, $select);
            mysqli_stmt_bind_param($stmt, "s", $userEmail);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) > 0) {
                $errors[] = 'User already exists!';
            } else {
                // Use prepared statement for inserting data
                $insert = "INSERT INTO tbl_account(userName, userDept, userEmail, userPass, userType, userImg) VALUES(?, ?, ?, ?, ?, ?)";
                $stmt = mysqli_prepare($conn, $insert);
                mysqli_stmt_bind_param($stmt, "sssssb", $userName, $userDept, $userEmail, $userPass, $userType, $imageData);
                mysqli_stmt_send_long_data($stmt, 5, $imageData); // Bind the image data
                mysqli_stmt_execute($stmt);
            }
        } else {
            $errors[] = 'Please select an image.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="register.css">
    <title>Document</title>
</head>

<body>
    <div class="form-container">
        <div class="container" style=" margin-left:20px; margin: 20px;">
            <p><strong>Personal Information</strong></p>
            <?php
            if (!empty($error)) { // Check if the $error array is not empty
                foreach ($error as $errorMsg) {
                    echo '<span class="error-msg">' . $errorMsg . '</span>';
                }
            }
            ?>
            <form method="post" enctype="multipart/form-data">
                <div class="form-group">
                    <div class="label-input">
                        <label for="userImage">Profile Picture:</label>
                        <input type="file" id="userImage" name="userImage" accept="image/*" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="label-input">
                        <label for="userName">Organization Name:</label>
                        <input type="text" id="userName" name="userName" class="form-control" placeholder="Enter Organization Name" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="label-input">
                        <label for="userDept">Department Name:</label>
                        <input type="text" id="userDept" name="userDept" class="form-control" placeholder="Enter your Department Name" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="label-input">
                        <label for="userEmail">Email Address:</label>
                        <input type="text" id="userEmail" name="userEmail" class="form-control" placeholder="Enter your Email" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="label-input">
                        <label for="userPass">Password:</label>
                        <input type="password" id="userPass" name="userPass" class="form-control" placeholder="Enter your password" required>
                    </div>
                </div>
                <div class="form-group">
                    <div class="label-input">
                        <label for="userPass">Confirm Password:</label>
                        <input type="password" id="cuserPass" name="cuserPass" class="form-control" placeholder="Confirm password" required>
                    </div>
                </div>
                <button class="pushable" type="submit" name="submit" value="Register">
                    <span class="shadow"></span>
                    <span class="edge"></span>
                    <span class="front">
                        Register
                    </span>
                </button>
            </form>

            <div class="sub-container">
                <p class="sub-title">If you find that the provided information is incorrect, please reach out to the
                    Lipa Office for assistance.</p>
                <span class="sub-email">Email: ict.lipa@g.batstate-u.edu.ph</span>
            </div>
        </div>
    </div>


</body>

</html>