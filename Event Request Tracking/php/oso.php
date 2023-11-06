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

$query = "SELECT userName FROM tbl_account";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$queryReq = "SELECT * FROM tbl_reqhistory";
$stmtReq = mysqli_prepare($conn, $queryReq);
mysqli_stmt_execute($stmtReq);
$resultReq = mysqli_stmt_get_result($stmtReq);

$queryReq2 = "SELECT * FROM tbl_reqhistory";
$stmtReq2 = mysqli_prepare($conn, $queryReq2);
mysqli_stmt_execute($stmtReq2);
$resultReq2 = mysqli_stmt_get_result($stmtReq2);

$queryEvents = "SELECT * FROM tbl_requests";
$stmtEvents = mysqli_prepare($conn, $queryEvents);
mysqli_stmt_execute($stmtEvents);
$resultEvents = mysqli_stmt_get_result($stmtEvents);

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
                        <img src="JPCS.png" alt="Logo" class="img-fluid">
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
                                <i class="fas fa-file"></i> Requests
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
                                <i class="fas fa-user"></i> Account
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

            <div class="col-md-10 p-4 bg-body-secondary" style="height:94vh">
                <div id="main-content">
                    <div id="form1" style="display: block;">
                        <h2 class="form-title">Dashboard</h2>
                        <div class="row">
                            <div class="col-md-7" style="padding:10px">
                                <div class="card text-bg-white mb-3" style="max-width:100%; height:115px">
                                    <div class="card-header"><strong>Welcome!</strong></div>
                                    <div class="card-body">
                                        <p class="card-text">Some quick example text to build on the card title and make
                                            up the bulk of the card's content.</p>
                                    </div>
                                </div>
                                <div class="db-container" style="height:430px">
                                <div class="card-header"><strong>Dashboard</strong></div>
                                    <table id="" class="table table-striped" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th class="text-center">ID</th>
                                                <th class="text-center">Status</th>
                                                <th class="text-center">Date</th>
                                                <th class="text-center">Deadline</th>
                                                <th class="text-center">User ID</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-center">
                                            <?php
                                            include 'config.php';
                                            while ($rowReq = mysqli_fetch_assoc($resultReq)) {
                                                echo "<tr>";
                                                echo "<td>{$rowReq['histID']}</td>";
                                                echo "<td>{$rowReq['reqStatus']}</td>";
                                                echo "<td>{$rowReq['statusDate']}</td>";
                                                echo "<td>{$rowReq['reqDeadline']}</td>";
                                                echo "<td>{$rowReq['orgID']}</td>";
                                                echo "</tr>";
                                            }
                                            ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-5" style="padding:10px">
                                <div class="card text-bg-white mb-3" style="max-width: 100%; height:115px">
                                    <div class="card-header"><strong>Organizations</strong></div>
                                    <div class="card-body">
                                        <p class="card-text">Some quick example text to build on the card title and make
                                            up
                                            the bulk of the card's content.</p>
                                    </div>
                                </div>
                                <div class="card text-bg-white mb-3" style="max-width: 100%; height:411px">
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

                        <table id="Requests" class="table table-striped text-center" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center">Organizations Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                include 'config.php';
                                while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>{$row['userName']}</td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>


                    <div id="form3" style="display: none;">
                        <h2 class="form-title">Requests</h2>


                        <table id="example" class="table table-striped" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center">ID</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Date</th>
                                    <th class="text-center">Deadline</th>
                                    <th class="text-center">User ID</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-center">
                                <?php
                                include 'config.php';
                                while ($rowReq2 = mysqli_fetch_assoc($resultReq2)) {
                                    echo "<tr>";
                                    echo "<td>{$rowReq2['histID']}</td>";
                                    echo "<td>{$rowReq2['reqStatus']}</td>";
                                    echo "<td>{$rowReq2['statusDate']}</td>";
                                    echo "<td>{$rowReq2['reqDeadline']}</td>";
                                    echo "<td>{$rowReq2['orgID']}</td>";
                                    echo '<td><button class="btn btn-primary">Update</button> <button class="btn btn-danger">Delete</button></td>';
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <div id="form4" style="display: none">
                        <h2 class="form-title">All Events</h2>
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

                    <div id="form5" style="display: none;">
                        <h2 class="form-title">Create Account</h2>
                        <?php require "register.php"; ?>
                    </div>

                    <div id="form6" style="display: none;">
                        <h2 class="form-title">Account</h2>
                        <div class="acc-container">
                            <p><strong>Personal Information</strong></p>
                            <div class="form-group">
                                <div class="label-input">
                                    <label for="userNameDisplay">Organization Name:</label>
                                    <span id="userNameDisplay" class="form-control"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="label-input">
                                    <label for="userDeptDisplay">Department Name:</label>
                                    <span id="userDeptDisplay" class="form-control"></span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="label-input">
                                    <label for="userEmailDisplay">Email Address:</label>
                                    <span id="userEmailDisplay" class="form-control"></span>
                                </div>
                            </div>
                            <div class="sub-container">
                                <p class="sub-title">If you find that the provided information is incorrect, please
                                    reach out to the
                                    Lipa Office for assistance.</p>
                                <span class="sub-email">Email: ict.lipa@g.batstate-u.edu.ph</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


</body>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', { 'packages': ['corechart'] });
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

    // Function to handle link clicks
    function handleLinkClick(event) {
        // Remove the "active-link" class from all links
        navLinks.forEach(link => link.classList.remove('active-link'));

        // Add the "active-link" class to the clicked link
        event.target.classList.add('active-link');
    }

    // Add a click event listener to each navigation link
    navLinks.forEach(link => {
        link.addEventListener('click', handleLinkClick);
    });

    new DataTable('#example');
    new DataTable('#Requests');
    new DataTable('#AllEvents');

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

        updateAccountInformation("<?php echo $dbUserName; ?>", "<?php echo $userDept; ?>", "<?php echo $userEmail; ?>");
    });
</script>

</html>