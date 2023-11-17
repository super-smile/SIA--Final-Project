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
        <form action="update_account.php" method="post" id="updateForm">

            <input type="hidden" name="userID" value="<?php echo $userID; ?>">


            <div class="default-orgName">
                <label for=" userName">Organizations Name:</label>
                <input type="text" name="userName" value="<?php echo $userName; ?>">
                <br>
            </div>

            <div class="default-dept">
                <label for="userDept">Department:</label>
                <input type="text" name="userDept" value="<?php echo $userDept; ?>">
                <br>
            </div>


            <div class="default-email">
                <label for="userEmail">Email:</label>
                <input type="email" name="userEmail" value="<?php echo $userEmail; ?>">
                <br>
            </div>

            <div class="input-pass">
                <label for="userPass">Password:</label>
                <input type="password" name="userPass">
                <br>
            </div>

            <div class="input-retypePass">
                <label for="retypeUserPass">Retype Password:</label>
                <input type="password" name="retypeUserPass">
                <br>
            </div>

            <input type="submit" class="update-button" name="update" value="Update" onclick="confirmUpdate(event)">
        </form>

        <script>
            function confirmUpdate(event) {
                event.preventDefault();

                // Perform confirmation without checking for empty fields
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
        </script>



        </script>
        </form>
    </div>
</body>

</html>