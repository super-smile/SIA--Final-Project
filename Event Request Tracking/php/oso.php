<?php
session_start();

include 'config.php';
if (isset($_SESSION['userName'])) {
    $CurrentUser = $_SESSION['userName'];

    $query = "SELECT userName, userDept, userEmail, userImg FROM tbl_account WHERE userName = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $CurrentUser);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $dbUserName, $CuserDept, $CuserEmail, $userImg);
    mysqli_stmt_fetch($stmt);

    $userType = $_SESSION['userType'];
    $userID = $_SESSION['userID'];
} else {
    header('location: login.php');
}


include 'config.php';

$userID = $_SESSION['userID'];

$query = "SELECT userName FROM tbl_account WHERE userType = 'organization'";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$queryAcc = "SELECT * FROM tbl_account WHERE userType != 'OSO'";
$stmtAcc = mysqli_prepare($conn, $queryAcc);
mysqli_stmt_execute($stmtAcc);
$resultAcc = mysqli_stmt_get_result($stmtAcc);

//dashboard
$queryReq = "SELECT * FROM tbl_requests";
$stmtReq = mysqli_prepare($conn, $queryReq);
mysqli_stmt_execute($stmtReq);
$resultReq = mysqli_stmt_get_result($stmtReq);

$queryReq2 = "SELECT * FROM tbl_requests";
$stmtReq2 = mysqli_prepare($conn, $queryReq2);
mysqli_stmt_execute($stmtReq2);
$resultReq2 = mysqli_stmt_get_result($stmtReq2);

$queryEvents = "SELECT * FROM tbl_requests";
$stmtEvents = mysqli_prepare($conn, $queryEvents);
mysqli_stmt_execute($stmtEvents);
$resultEvents = mysqli_stmt_get_result($stmtEvents);


$queryImg = "SELECT userImg FROM tbl_account WHERE userName = ?";
$stmtImg = mysqli_prepare($conn, $queryImg);
mysqli_stmt_bind_param($stmtImg, "s", $CurrentUser);
mysqli_stmt_execute($stmtImg);
mysqli_stmt_bind_result($stmtImg, $userImg);
mysqli_stmt_fetch($stmtImg);

// Convert the binary image data to base64
$userImgBase64 = base64_encode($userImg);

include 'HTML/oso.html'
    ?>

<body>
    <div class="header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <img src="logoo.png" alt="Logo" width="45" style="padding: 5px;" class="img-fluid">
            <div class="header-text">
                <p style="font-size: 11px; font-weight: 800; margin: 0;">Event Tracking System</p>
                <span style="font-size: 9px;">Office of the Student Organizations</span>
            </div>
        </div>
        <div class="notification-icon position-relative" style="margin-right: 20px">
            <div class="notification-bell">
                <i class="fas fa-bell" style="color: #a21a1e; font-size: 17px;"></i>
            </div>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                <span class="notif">4</span>
                <span class="visually-hidden">unread messages</span>
            </span>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 p-0">
                <div id="sidebar">
                    <div class="image-container p-1">
                        <?php
                        if (isset($_SESSION['userName'])) {
                            $userName = $_SESSION['userName'];
                            $accImgPath = 'data:image/png;base64,' . $userImgBase64;

                            if (!empty($userImgBase64)) {
                                echo '<img src="' . $accImgPath . '" alt="Profile Image" class="img-fluid">';
                            } else {
                                echo '<img src="default_profile_image.png" alt="Default Image" class="img-fluid">';
                            }
                        }
                        ?>
                    </div>
                    <div class="subtitle">
                        <?php
                        if (isset($_SESSION['userName'])) {
                            $userName = $_SESSION['userName'];
                            echo "<span class = welcom >Welcome Back,</span><br><p><b> $userName!</b></p>";
                        }
                        ?>
                    </div>

                    <ul class="nav flex-column ">
                        <li class="nav-item">
                            <a class="nav-link text text-left  active-link" id="showForm1">
                                <i class="fas fa-chart-line"></i> Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text text-left" id="showForm2">
                                <i class="fas fa-users"></i> Organizations
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text text-left" id="showForm3">
                                <i class="fas fa-tasks"></i> Requests
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text text-left" id="showForm4">
                                <i class="fas fa-calendar"></i> All Events
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text text-left" id="showForm5">
                                <i class="fas fa-user-plus"></i> Create Account
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text text-left" id="showForm6">
                                <i class="fas fa-user"></i> Accounts
                            </a>
                        </li><br><br><br><br><br>
                        <li class="nav-item">
                            <a class="nav-link text text-left" href="login.php">
                                <i class="fas fa-sign-out-alt"></i><u>Logout</u>
                            </a>
                        </li>
                    </ul>

                </div>
            </div>

            <div class="content" style="flex: 1; padding: 20px;">
                <div id="main-content">
                    <div id="form1" style="display: block;">
                        <h2 class="form-title">Dashboard</h2>
                        <div class="row">
                            <div class="col-md-7" style="padding:10px;">
                                <div class="card text-bg-white mb-3"
                                    style="max-width:100%; height:115px; margin-left:20px">
                                    <div class="card-header"><strong>Welcome!</strong></div>
                                    <div class="card-body">
                                        <p class="card-text">Good day</p>
                                    </div>
                                </div>
                                <div class="db-container" style=" margin-left:20px">
                                    <div class="db card-header"><strong>Dashboard</strong></div>
                                    <table id="" class="table table-striped" style="width:100%; ">
                                        <thead>
                                            <tr>
                                                <th class="text-center">Event Name</th>
                                                <th class="text-center">Event Date</th>
                                                <th class="text-center">Organization</th>
                                                <th class="text-center">Current Office</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <?php
                                            include 'config.php';
                                            while ($rowReq = mysqli_fetch_assoc($resultReq)) {
                                                echo "<tr>";
                                                echo "<td>{$rowReq['reqEventName']}</td>";
                                                echo "<td>{$rowReq['reqEventDate']}</td>";
                                                echo "<td>{$rowReq['userID']}</td>";
                                                echo "<td>{$rowReq['currentOffice']}</td>";
                                                echo "</tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-5" style="padding:10px">
                                <div class="card text-bg-white mb-3" style="max-width: 100%; height:115px">
                                    <div class="card-header"><strong>Time</strong></div>
                                    <div class="card-body">
                                        <span id="time" style="float: center; font-size: 30px"></span>
                                    </div>
                                    <script>
                                        function updateTime() {
                                            const timeElement = document.getElementById("time");
                                            const timeOptions = {
                                                hour: '2-digit',
                                                minute: '2-digit',
                                                second: '2-digit'
                                            };
                                            const currentTime = new Date().toLocaleTimeString(undefined, timeOptions);
                                            timeElement.innerHTML = currentTime;
                                        }

                                        updateTime();
                                        setInterval(updateTime, 1000);
                                    </script>
                                </div>
                                <div class="pieChart card text-bg-white mb-3">
                                    <div class="card-header"><strong>Overview</strong></div>
                                    <div class="card-body">
                                        <div class="overview" style="height:411px">
                                            <div id="piechart" style="width: 100%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="form2" style="display: none;">
                        <h2 class="form-title">Organizations</h2>
                        <div class="tbl-container">
                            <?php
                            include 'config.php';

                            $orgQuery = "SELECT acc.userName, acc.userDept, COUNT(req.reqID) AS numActivities
                        FROM tbl_account AS acc
                        LEFT JOIN tbl_requests AS req ON acc.userID = req.userID
                        WHERE acc.userType = 'organization'
                        GROUP BY acc.userID";

                            $orgResult = mysqli_query($conn, $orgQuery);
                            ?>
                            <table id="orgTable" class="table table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-center">Organization Name</th>
                                        <th class="text-center">Department Name/th>
                                        <th class="text-center">Number of Requests</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <?php
                                    while ($rowOrg = mysqli_fetch_assoc($orgResult)) {
                                        echo '<tr>';
                                        echo '<td>' . $rowOrg['userName'] . '</td>';
                                        echo '<td>' . $rowOrg['userDept'] . '</td>';
                                        echo '<td>' . $rowOrg['numActivities'] . '</td>';
                                        echo '</tr>';
                                    }
                                    echo '</tbody>';
                                    echo '</table>';
                                    ?>

                        </div>
                    </div>

                    <div id="form3" style="display: none;">
                        <h2 class="form-title">Requests</h2>
                        <div class="tbl-container">
                            <table id="example" class="table table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-center">Request ID</th>
                                        <th class="text-center">Event Name</th>
                                        <th class="text-center">Letter</th>
                                        <th class="text-center">Event Date</th>
                                        <th class="text-center">Request Deadline</th>
                                        <th class="text-center">User ID</th>
                                        <th class="text-center">Current Office</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <?php
                                    include 'config.php';
                                    while ($rowReq2 = mysqli_fetch_assoc($resultReq2)) {
                                        echo "<tr>";
                                        echo "<td>{$rowReq2['reqID']}</td>";
                                        echo "<td>{$rowReq2['reqEventName']}</td>";
                                        echo "<td><a href='view_pdf.php?reqID={$rowReq2['reqID']}' target='_blank'>View Letter</a></td>";
                                        echo "<td>{$rowReq2['reqEventDate']}</td>";
                                        echo "<td>{$rowReq2['reqDeadline']}</td>";
                                        echo "<td>{$rowReq2['userID']}</td>";
                                        echo "<td>{$rowReq2['currentOffice']}</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div id="form4" style="display: none">
                        <h2 class="form-title">All Events</h2>
                        <div class="tbl-container">
                            <table id="AllEvents" class="table table-striped" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="text-center">Request ID</th>
                                        <th class="text-center">Event Name</th>
                                        <th class="text-center">Event Date</th>
                                    </tr>

                                </thead>
                                <tbody class="text-center">
                                    <?php
                                    include 'config.php';
                                    while ($rowEvents = mysqli_fetch_assoc($resultEvents)) {
                                        echo "<tr>";
                                        echo "<td>{$rowEvents['reqID']}</td>";
                                        echo "<td>{$rowEvents['reqEventName']}</td>";
                                        echo "<td>{$rowEvents['reqEventDate']}</td>";
                                        echo "</tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div id="form5" style="display: none;">
                        <h2 class="form-title">Create Account</h2>
                        <?php require "register.php"; ?>
                    </div>

                    <div id="form6" style="display: none;">
                        <h2 class="form-title">Accounts</h2>
                        <div class="tbl-container">
                            <p><strong>Organizations Information</strong></p>
                            <table id="Account" class="table table-striped text-center" style="width:100%;">
                                <thead>
                                    <tr>
                                        <th class="text-center">Organizations Name</th>
                                        <th class="text-center">Department</th>
                                        <th class="text-center">Email</th>
                                        <th class="text-center">Password</th>
                                        <th class="text-center">Edit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    include 'config.php';
                                    while ($row = mysqli_fetch_assoc($resultAcc)) {
                                        echo "<tr>";
                                        echo "<td>{$row['userName']}</td>";
                                        echo "<td>{$row['userDept']}</td>";
                                        echo "<td>{$row['userEmail']}</td>";
                                        echo "<td>";
                                        $password = $row['userPass'];
                                        $maskedPassword = str_repeat('•', 8);
                                        echo $maskedPassword;
                                        echo "</td>";

                                        echo "<td>";
                                        echo "<form action='edit_account' method='post'>"; // <-- Corrected action attribute
                                        echo "<input type='hidden' name='userID' value='{$row['userID']}'>";
                                        echo "<button type='submit' class='edit-button'>Edit</button>";
                                        echo "</form>";
                                        echo "</td>";
                                    }
                                    ?>
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</body>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', {
        'packages': ['corechart']
    });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        <?php
        include('config.php');

        $queryPie = "SELECT reqStatus, COUNT(reqStatus) as count FROM tbl_reqhistory GROUP BY reqStatus";
        $resultPie = mysqli_query($conn, $queryPie);

        $chartData = [['Status', 'Count']];
        while ($rowPie = mysqli_fetch_assoc($resultPie)) {
            $chartData[] = [$rowPie['reqStatus'], (int) $rowPie['count']];
        }
        ?>
        var data = google.visualization.arrayToDataTable(<?php echo json_encode($chartData); ?>);

        var options = {
            title: 'Event Approval Overview'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));
        chart.draw(data, options);
    }
</script>

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>



<script>
    const navLinks = document.querySelectorAll('.nav-link');

    function handleLinkClick(event) {
        navLinks.forEach(link => link.classList.remove('active-link'));

        event.target.classList.add('active-link');
    }

    navLinks.forEach(link => {
        link.addEventListener('click', handleLinkClick);
    });

    new DataTable('#example');
    new DataTable('#Account');
    new DataTable('#Requests');
    new DataTable('#AllEvents');
    new DataTable('#orgTable');

    $(document).ready(function() {

        var globalOptions = {
            "lengthMenu": [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, "All"]
            ],

        };

        // Initialize DataTable for #example
        if ($.fn.dataTable.isDataTable('#example')) {
            $('#example').DataTable().destroy();
        }
        $('#example').DataTable(globalOptions);

        // Initialize DataTable for #Account
        if ($.fn.dataTable.isDataTable('#Account')) {
            $('#Account').DataTable().destroy();
        }
        $('#Account').DataTable(globalOptions);

        // Initialize DataTable for #Requests
        if ($.fn.dataTable.isDataTable('#Requests')) {
            $('#Requests').DataTable().destroy();
        }
        $('#Requests').DataTable(globalOptions);

        // Initialize DataTable for #AllEvents
        if ($.fn.dataTable.isDataTable('#AllEvents')) {
            $('#AllEvents').DataTable().destroy();
        }
        $('#AllEvents').DataTable(globalOptions);
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
    var button6 = document.getElementById("showForm6");

    var form1 = document.getElementById("form1");
    var form2 = document.getElementById("form2");
    var form3 = document.getElementById("form3");
    var form4 = document.getElementById("form4");
    var form5 = document.getElementById("form5");
    var form6 = document.getElementById("form6");

    button1.addEventListener("click", function () {
        form1.style.display = "block";
        form2.style.display = "none";
        form3.style.display = "none";
        form4.style.display = "none";
        form5.style.display = "none"
        form6.style.display = "none"
    });

    button2.addEventListener("click", function () {
        form1.style.display = "none";
        form2.style.display = "block";
        form3.style.display = "none";
        form4.style.display = "none";
        form5.style.display = "none";
        form6.style.display = "none";
    });

    button3.addEventListener("click", function () {
        form1.style.display = "none";
        form2.style.display = "none";
        form3.style.display = "block";
        form4.style.display = "none";
        form5.style.display = "none";
        form6.style.display = "none";
    });

    button4.addEventListener("click", function () {
        form1.style.display = "none";
        form2.style.display = "none";
        form3.style.display = "none";
        form4.style.display = "block";
        form5.style.display = "none";
        form6.style.display = "none";
    });

    button5.addEventListener("click", function () {
        form1.style.display = "none";
        form2.style.display = "none";
        form3.style.display = "none";
        form4.style.display = "none";
        form5.style.display = "block";
        form6.style.display = "none";
    });

    button6.addEventListener("click", function () {
        form1.style.display = "none";
        form2.style.display = "none";
        form3.style.display = "none";
        form4.style.display = "none";
        form5.style.display = "none";
        form6.style.display = "block";

        updateAccountInformation("<?php echo $dbUserName; ?>", "<?php echo $CuserDept; ?>", "<?php echo $CuserEmail; ?>");
    });
</script>

</html>