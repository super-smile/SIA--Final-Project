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
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="editAccountStyle.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    <title>Edit Account</title>
</head>

<body>
    <div class="container">


        <a href="oso.php">
            <div class="home-icon">
                <i class='bx bx-home'></i>
            </div>
        </a>


        <p class="title">Edit Account </p>
        <form action="edit_account" method="post">
            <!-- Include hidden input for user ID -->
            <input type="hidden" name="userID" value="<?php echo $userID; ?>">

            <div class="default-orgName">
                <label for=" userName">Organizations Name:</label>
                <input type="text" name="userName" value="<?php echo $userName; ?>" required>
                <br>
            </div>

            <div class="default-dept">
                <label for="userDept">Department:</label>
                <input type="text" name="userDept" value="<?php echo $userDept; ?>" required>
                <br>
            </div>


            <div class="default-email">
                <label for="userEmail">Email:</label>
                <input type="email" name="userEmail" value="<?php echo $userEmail; ?>" required>
                <br>
            </div>

            <div class="input-pass">
                <label for="userPass">Password:</label>
                <input type="password" name="userPass" required>
                <br>
            </div>

            <div class="input-retypePass">
                <label for="retypeUserPass">Retype Password:</label>
                <input type="password" name="retypeUserPass" required>
                <br>
            </div>

            <input type="submit" class="update-button" value="Update" onclick="confirmUpdate(event)">
        </form>

        <!-- Your HTML and previous code -->

        <script>
            function confirmUpdate(event) {
                event.preventDefault();

                const userPass = document.getElementsByName('userPass')[0].value;
                const retypeUserPass = document.getElementsByName('retypeUserPass')[0].value;

                if (!userPass || !retypeUserPass) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Please fill in both Password and Retype Password!',
                    });
                } else {
                    Swal.fire({
                        title: 'Confirm Update',
                        text: 'Are you sure you want to update?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, update it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('updateForm').submit();
                        }
                    });
                }
            }
        </script>


        </script>
        </form>
    </div>
</body>

</html>