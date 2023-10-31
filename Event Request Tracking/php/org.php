<?php
session_start();

include 'config.php';
//Account Information
if (isset($_SESSION['userName'])) {
    $userName = $_SESSION['userName'];

    $query = "SELECT userName, userDept, userEmail FROM tbl_account WHERE userName = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $userName);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $dbUserName, $userDept, $userEmail);
    mysqli_stmt_fetch($stmt);

    $userType = $_SESSION['userType'];
    $userID = $_SESSION['userID'];
} else {
    header('location: login.php');
}

include 'config.php';
$userID = $_SESSION['userID'];

//Request 
$query = "SELECT * FROM tbl_reqhistory WHERE orgID = ? and reqStatus = 'Pending'";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $userID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$queryReq = "SELECT * FROM tbl_requests WHERE userID = ?";
$stmtReq = mysqli_prepare($conn, $queryReq);
mysqli_stmt_bind_param($stmtReq, "s", $userID);
mysqli_stmt_execute($stmtReq);
$resultReq = mysqli_stmt_get_result($stmtReq);

//Archive
$queryArch = "SELECT * FROM tbl_reqhistory WHERE orgID = ? and reqStatus = 'Approved'";
$stmtArch = mysqli_prepare($conn, $queryArch);
mysqli_stmt_bind_param($stmtArch, "s", $userID);
mysqli_stmt_execute($stmtArch);
$resultArch = mysqli_stmt_get_result($stmtArch);

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styleOrg.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href='https://fonts.googleapis.com/css?family=Poppins'>
    <title>Document</title>
</head>

<body style="background:#F3F3F3;">
    <div class="container-fluid" style="height: 100vh; width:100%; margin:0; padding: 0; display: flex; flex-direction: column;">
        <div class="header">
            <img src="logoo.png" alt="Logo" width="50" height="50" style="padding: 5px;" class="img-fluid">
            <div class="header-text">
                <p style="font-size: 15px; font-weight: bold; margin: 0;">Event Tracking System</p>
                <span style="font-size: 12px;">Office of the Student Organizations</span>
                <?php
                if ($userType == 'Organization') {
                    echo '<a href="letter.php" class="upload-button" id="uploadLetter">Upload a letter</a>';
                }
                ?>
            </div>
        </div>

        <div class="wrapper">
            <div class="sidebar">
                <img src="logoo.png" alt="sideLogo" width="180" height="180" style="padding: 30px 0px 0px 30px; margin-left:50px;" class="img-fluid">
                <?php
                if (isset($_SESSION['userName'])) {
                    $userName = $_SESSION['userName'];
                    echo '<div class="welcome-message">Welcome Back,</div>';
                    echo '<div class="user-name">' . $userName . '</div>';
                }
                ?>

                <br>
                <button type="button" class="btn" id="showForm1">Dashboard</button><br>
                <button type="button" class="btn" id="showForm2">Request</button><br>
                <button type="button" class="btn" id="showForm3">Archive</button><br>
                <button type="button" class="btn" id="showForm4">Account</button><br><br>

                <button class="logout" onclick="location.href='login.php'"><u>Logout</u></button>

            </div>

            <form id="form1" style="display: block;">
                <h2 style="font-family:'Poppins'; margin:10px 10px 10px 10px"><strong>Dashboard</strong></h2>
            </form>

            <form id="form2" style="display: none;">
                <h2 style="font-family:'Poppins'; margin:10px 10px 10px 10px"><strong>Request</strong></h2>
                <div class="container-fluid request" style="background: white;
                        margin: 30px 50px 25px 65px;
                        padding: 10px 45px 10px 45px;
                        box-shadow: 0 0 7px rgba(0, 0, 0, 0.2);
                        border-radius: .5rem;">
                    <h3 id="date-time">Date</h3>

                    <script>
                        function updateDateTime() {
                            const dateTimeElement = document.getElementById("date-time");
                            const currentDate = new Date();
                            const dateOptions = {
                                weekday: 'long',
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            };
                            const timeOptions = {
                                hour: '2-digit',
                                minute: '2-digit',
                                second: '2-digit'
                            };

                            const formattedDate = currentDate.toLocaleDateString(undefined, dateOptions);
                            const formattedTime = currentDate.toLocaleTimeString(undefined, timeOptions);

                            dateTimeElement.innerHTML = ${formattedDate} <span style="float: right">${formattedTime}</span>;
                        }

                        // Call the function to update the date and time initially
                        updateDateTime();

                        // Update the date and time every second (1000 milliseconds)
                        setInterval(updateDateTime, 1000);
                    </script>

                    <div class="horizontal-line" style="width:100%"></div>
                    <table id="Req" class="table table-striped" style="width:100%">
                        <thead>
                            <tr>
                                <th>Req ID</th>
                                <th>Status</th>
                                <th>Date Approved</th>
                                <th>Deadline</th>
                                <th>Organization ID</th>
                                <th>Office ID</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include 'config.php';
                            while ($row = mysqli_fetch_assoc($result)) {
                                echo "<tr>";
                                echo "<td>{$row['reqID']}</td>";
                                echo "<td>{$row['reqStatus']}</td>";
                                echo "<td>{$row['statusDate']}</td>";
                                echo "<td>{$row['reqDeadline']}</td>";
                                echo "<td>{$row['orgID']}</td>";
                                echo "<td>{$row['officeID']}</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </form>


            <form id="form3" style="display: none;">
                <h2 style="font-family:'Poppins'; margin:10px 10px 10px 10px"><strong>Archive</strong></h2>


                <table id="Arch" class="table table-striped" style="width:100%">
                    <thead>
                        <tr>
                            <th>Request ID</th>
                            <th>Submission Date</th>
                            <th>Status</th>
                            <th>Request Deadline</th>
                            <th>userID</th>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include 'config.php';
                        while ($rowArch = mysqli_fetch_assoc($resultArch)) {
                            echo "<tr>";
                            echo "<td>{$rowArch['reqID']}</td>";
                            echo "<td>{$rowArch['statusDate']}</td>";
                            echo "<td>{$rowArch['reqStatus']}</td>";

                            echo "<td>{$rowArch['reqDeadline']}</td>";
                            echo "<td>{$rowArch['orgID']}</td>";

                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </form>

            <form id="form4" style="display: none;">
                <h2 style="font-family:'Poppins'; margin:10px 10px 10px 10px"><strong>Account</strong></h2>
                <div class="container" id="account-container">
                    <h3 id="h3style">Organizations Information</h3>

                    <div class="container" id="information-container">
                        <p><strong>Organizations Name:</strong> <input type="text" id="userNameDisplay" class="text" readonly /></p>
                        <p><strong>Department:</strong> <input type="text" id="userDeptDisplay" class="text" readonly /></p>
                        <p><strong>Email Address:</strong> <input type="text" id="userEmailDisplay" class="text" readonly /></p>
                    </div>

                    <div class="container" id="container-assistance">
                        <p>If you find that the provided information is incorrect, please reach out to the Office of Student<br>
                            &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;Organization for assistance.</p>
                        <p style="margin-left: 175px; font-weight:normal">Email: studentorganization.lipa@g.batstate-u.edu.ph</p>
                    </div>
                </div>
            </form>
        </div>
</body>
<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>
    new DataTable('#Req');
    new DataTable('#Arch');

    function loginUser() {



    }

    function updateAccountInformation(userName, userDept, userEmail) {
        document.getElementById('userNameDisplay').value = userName;
        document.getElementById('userDeptDisplay').value = userDept;
        document.getElementById('userEmailDisplay').value = userEmail;
    }
    var button1 = document.getElementById("showForm1");
    var button2 = document.getElementById("showForm2");
    var button3 = document.getElementById("showForm3");
    var button4 = document.getElementById("showForm4");

    var form1 = document.getElementById("form1");
    var form2 = document.getElementById("form2");
    var form3 = document.getElementById("form3");
    var form4 = document.getElementById("form4");

    button1.addEventListener("click", function() {
        form1.style.display = "block";
        form2.style.display = "none";
        form3.style.display = "none";
        form4.style.display = "none";
    });

    button2.addEventListener("click", function() {
        form1.style.display = "none";
        form2.style.display = "block";
        form3.style.display = "none";
        form4.style.display = "none";
    });

    button3.addEventListener("click", function() {
        form1.style.display = "none";
        form2.style.display = "none";
        form3.style.display = "block";
        form4.style.display = "none";
    });

    button4.addEventListener("click", function() {
        form1.style.display = "none";
        form2.style.display = "none";
        form3.style.display = "none";
        form4.style.display = "block";

        updateAccountInformation("<?php echo $userName; ?>", "<?php echo $userDept; ?>", "<?php echo $userEmail; ?>");
    });


    var showForm1Button = document.getElementById('showForm1');
    var showForm2Button = document.getElementById('showForm2');
    var showForm3Button = document.getElementById('showForm3');
    var showForm4Button = document.getElementById('showForm4');

    var activeButton = null;

    showForm1Button.addEventListener('click', function() {
        if (activeButton !== showForm1Button) {
            if (activeButton) {
                activeButton.classList.remove('clicked');
            }
            showForm1Button.classList.add('clicked');
            activeButton = showForm1Button;
        }
    });

    showForm2Button.addEventListener('click', function() {
        if (activeButton !== showForm2Button) {
            if (activeButton) {
                activeButton.classList.remove('clicked');
            }
            showForm2Button.classList.add('clicked');
            activeButton = showForm2Button;
        }
    });

    showForm3Button.addEventListener('click', function() {
        if (activeButton !== showForm3Button) {
            if (activeButton) {
                activeButton.classList.remove('clicked');
            }
            showForm3Button.classList.add('clicked');
            activeButton = showForm3Button;
        }
    });

    showForm4Button.addEventListener('click', function() {
        if (activeButton !== showForm4Button) {
            if (activeButton) {
                activeButton.classList.remove('clicked');
            }
            showForm4Button.classList.add('clicked');
            activeButton = showForm4Button;
        }
    });
</script>
</html>