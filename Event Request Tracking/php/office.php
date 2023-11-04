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

    $query = "SELECT * FROM tbl_reqhistory WHERE reqStatus = 'Pending'";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $queryArch = "SELECT * FROM tbl_reqhistory WHERE reqStatus = 'Approved'";
    $stmtArch = mysqli_prepare($conn, $queryArch);
    mysqli_stmt_execute($stmtArch);
    $resultArch = mysqli_stmt_get_result($stmtArch);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Tracking System</title>

    <!-- Bootstrap CSS and JavaScript CDNs -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.js"></script>
    <script src="js/script.js"></script>
    <link rel="stylesheet" href="officeStyle.css">
</head>
<body style="background:#F3F3F3;"> 
    <div class="container-fluid" style="height: 100vh; width:100%; margin:0; padding: 0; display: flex; flex-direction: column;">
        <div class="header">
            <div style="display: flex; align-items: center; justify-content: space-between;">
                <div style="display: flex; align-items: center;">
                    <img src="logoo.png" alt="Logo" width="50" height="50" style="padding: 5px;" class="img-fluid">
                    <div class="header-text" style="margin-left: 10px;">
                        <p style="font-size: 15px; font-weight: bold; margin: 0;">Event Tracking System</p>
                        <span style="font-size: 12px;">Office of the Student Organizations</span>
                    </div>
                </div>
                <div class="notification-bell" style="margin-right: 10px; border-radius: 50%; overflow: hidden; background-color: black;">
                    <i class="fas fa-bell" style="color: white; padding: 10px;"></i>
                </div>
            </div>
        </div>

        <div class="wrapper" style="display: flex;">
        <div class="sidebar" style="background-color: #a21a1e; width: 250px; padding: 20px;">
            <img src="cics_logo.png" alt="sideLogo" width="180" height="180" class="img-fluid">
            <?php
            if (isset($_SESSION['userName'])) {
                $userName = $_SESSION['userName'];
                echo '<div class="welcome-message">Welcome Back,</div>';
                echo '<div class="user-name">' . $userName . '</div>';
            }
            ?>

            <br>
            <button type="button" class="btn" id="showForm1">
                <i class="fas fa-chart-line"></i> Dashboard
            </button><br>

            <button type="button" class="btn" id="showForm2">
                <i class="fas fa-users"></i> Organizations
            </button><br>

            <button type="button" class="btn" id="showForm3">
                <i class="fas fa-file"></i> Requests
            </button><br>

            <button type="button" class="btn" id="showForm4">
                <i class="fas fa-archive"></i> Archive
            </button><br>

            <button type="button" class="btn" id="showForm5">
                <i class="fas fa-user"></i> Account
            </button><br><br><br><br><br><br><br><br><br><br><br><br><br><br>

            <button class="logout" onclick="location.href='login.php'" style="background: none; border: none; padding: 0; text-decoration: underline; cursor: pointer;">
                <i class="fas fa-sign-out-alt"></i>  Logout
            </button>

        </div>
        <!-- Content Area -->
        <div class="content" style="flex: 1; padding: 20px;">

            <form id="form1" style="display: block;">
                <h2>Dashboard</h2>
            </form>

            <form id="form2" style="display: none;">
                <h2>Organizations</h2>

                <?php
                include 'config.php';

                    $orgQuery = "SELECT acc.userName, acc.userDept, COUNT(reqhist.reqID) AS numActivities
                                FROM tbl_account AS acc
                                LEFT JOIN tbl_reqhistory AS reqhist ON acc.userID = reqhist.orgID
                                GROUP BY acc.userID";
                                
                    $orgResult = mysqli_query($conn, $orgQuery);

                    echo '<table border="1">';
                    echo '<tr>';
                    echo '<th>Organization Name</th>';
                    echo '<th>Department</th>';
                    echo '<th>Number of Activities</th>';
                    echo '</tr>';

                    while ($rowOrg = mysqli_fetch_assoc($orgResult)) {
                        echo '<tr>';
                        echo '<td>' . $rowOrg['userName'] . '</td>';
                        echo '<td>' . $rowOrg['userDept'] . '</td>';
                        echo '<td>' . $rowOrg['numActivities'] . '</td>';
                        echo '</tr>';
                    }

                    echo '</table>';
                    ?>
            </form>

        <form id="form3" style="display: none;">
                <h2>Requests</h2>
                <style>
                    table {
                        border-collapse: collapse;
                        width: 100%;
                    }

                    th, td {
                        border: 1px solid #dddddd;
                        text-align: left;
                        padding: 8px;
                    }
                </style>       
            <table class="bordered stripe" id="dataTable">
                    <thead>
                        <tr>
                            <th>histID</th>
                            <th>reqStatus</th>
                            <th>statusDate</th>
                            <th>reqDeadline</th>
                            <th>userID</th>
                            <th>reqID</th>
                            <th>office ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include 'config.php';
                        while ($rowArch = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>{$rowArch['histID']}</td>";
                            echo "<td>{$rowArch['reqStatus']}</td>";
                            echo "<td>{$rowArch['statusDate']}</td>";
                            echo "<td>{$rowArch['reqDeadline']}</td>";
                            echo "<td>{$rowArch['orgID']}</td>";
                            echo "<td>{$rowArch['reqID']}</td>";
                            echo "<td>{$rowArch['officeID']}</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </form>

            <form id="form4" style="display: none;">
                <h2>Archive</h2>
                <!-- Archive Table Styles -->
                <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.css">
                <!-- Archive Table -->
                <table class="bordered stripe" id="dataTableArchive">
                    <thead>
                        <tr>
                            <th>histID</th>
                            <th>reqStatus</th>
                            <th>statusDate</th>
                            <th>reqDeadline</th>
                            <th>userID</th>
                            <th>reqID</th>
                            <th>office ID</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include 'config.php';
                        while ($rowArch = mysqli_fetch_assoc($resultArch)) {
                            echo "<tr>";
                            echo "<td>{$rowArch['histID']}</td>";
                            echo "<td>{$rowArch['reqStatus']}</td>";
                            echo "<td>{$rowArch['statusDate']}</td>";
                            echo "<td>{$rowArch['reqDeadline']}</td>";
                            echo "<td>{$rowArch['orgID']}</td>";
                            echo "<td>{$rowArch['reqID']}</td>";
                            echo "<td>{$rowArch['officeID']}</td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </form>


            <!-- Account Information Form -->
            <form id="form5" style="display: none;">
                <h2>Account</h2>
                <p>Username: <span id="userNameDisplay"></span></p>
                <p>Department: <span id="userDeptDisplay"></span></p>
                <p>Email: <span id="userEmailDisplay"></span></p>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable();
            $('#dataTableArchive').DataTable();
        });

        function updateAccountInformation(userName, userDept, userEmail) {
            document.getElementById('userNameDisplay').textContent = userName;
            document.getElementById('userDeptDisplay').textContent = userDept;
            document.getElementById('userEmailDisplay').textContent = userEmail;
        }

        var button1 = document.getElementById("showForm1");
        var button2 = document.getElementById("showForm2");
        var button3 = document.getElementById("showForm3");
        var button4 = document.getElementById("showForm4");
        var button5 = document.getElementById("showForm5");
        
        var form1 = document.getElementById("form1");
        var form2 = document.getElementById("form2");
        var form3 = document.getElementById("form3");
        var form4 = document.getElementById("form4");
        var form5 = document.getElementById("form5");
        
        button1.addEventListener("click", function () {
            form1.style.display = "block";
            form2.style.display = "none";
            form3.style.display = "none";
            form4.style.display = "none";
            form5.style.display = "none";
        });

        button2.addEventListener("click", function () {
            form1.style.display = "none";
            form2.style.display = "block";
            form3.style.display = "none";
            form4.style.display = "none";
            form5.style.display = "none";
        });

        button3.addEventListener("click", function () {
            form1.style.display = "none";
            form2.style.display = "none";
            form3.style.display = "block";
            form4.style.display = "none";
            form5.style.display = "none";
        });

        button4.addEventListener("click", function () {
            form1.style.display = "none";
            form2.style.display = "none";
            form3.style.display = "none";
            form4.style.display = "block";
            form5.style.display = "none";
        });
        button5.addEventListener("click", function () {
        form1.style.display = "none";
        form2.style.display = "none";
        form3.style.display = "none";
        form4.style.display = "none";
        form5.style.display = "block";
            updateAccountInformation("<?php echo $dbUserName; ?>", "<?php echo $userDept; ?>", "<?php echo $userEmail; ?>");
        });
    </script>
</body>
</html>
