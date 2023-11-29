<?php
session_start();
include 'config.php';

if (isset($_SESSION['designation'])) {
    $designation = $_SESSION['designation'];
    $officeAccID = $_SESSION['officeAccID'];

    $query = "SELECT o.designation, o.employeeID, o.officeEmail, o.officeImg, e.department 
              FROM tbl_office o
              JOIN tbempinfo e ON o.employeeID = e.empid
              WHERE o.designation = ?
              AND o.officeAccID = '$officeAccID'";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $designation);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $officeDesig, $CuserDept, $CuserEmail, $employeeID, $department);
    if (mysqli_stmt_fetch($stmt)) {
        $CuserDept = $department;
    } else {
        // Handle the case where no results are found if necessary
    }
} else {
    header('location: login.php');
}


include 'config.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["approve"])) {
        $reqID = $_POST["reqID"];
        $orgID = $_POST["userID"];
        approveRequest($conn, $reqID, $orgID);
    } elseif (isset($_POST["decline"])) {
        $reqID = $_POST["reqID"];
        $orgID = $_POST["userID"];
        declineRequest($conn, $reqID, $orgID);
    }
}

$userID = $_SESSION['designation'];
$query = "SELECT tr.*, ta.userName 
          FROM tbl_requests tr
          LEFT JOIN tbl_account ta ON tr.userID = ta.userID
          WHERE tr.currentOffice = ? AND ta.userDept = ?
          ORDER BY tr.reqID DESC";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "ss", $userID, $CuserDept);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$userID = $_SESSION['designation'];

if ($userID == "OVCAA" or $userID == "Chancellor") {
    $queryC = "SELECT tr.*, ta.userName 
               FROM tbl_requests tr
               LEFT JOIN tbl_account ta ON tr.userID = ta.userID
               WHERE tr.currentOffice = ?";
    $stmtC = mysqli_prepare($conn, $queryC);
    mysqli_stmt_bind_param($stmtC, "s", $userID);
    mysqli_stmt_execute($stmtC);
    $resultC = mysqli_stmt_get_result($stmtC);
} else {

    $queryC = "SELECT tr.*, ta.userName 
               FROM tbl_requests tr
               LEFT JOIN tbl_account ta ON tr.userID = ta.userID
               WHERE tr.currentOffice = ? AND ta.userDept = ?";
    $stmtC = mysqli_prepare($conn, $queryC);
    mysqli_stmt_bind_param($stmtC, "ss", $userID, $CuserDept);
    mysqli_stmt_execute($stmtC);
    $resultC = mysqli_stmt_get_result($stmtC);
}





include 'HTML/office.html';

$officeAccID = $_SESSION['officeAccID'];
$queryImg = "SELECT officeImg FROM tbl_office WHERE officeAccID = ?";
$stmtImg = mysqli_prepare($conn, $queryImg);
mysqli_stmt_bind_param($stmtImg, "s", $officeAccID);
mysqli_stmt_execute($stmtImg);
mysqli_stmt_bind_result($stmtImg, $userImg);
mysqli_stmt_fetch($stmtImg);

$userImgBase64 = base64_encode($userImg);
?>

</head>

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
                <i class="fas fa-bell" style="color: white; font-size: 17px;"></i>
            </div>
            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                <?php
                include 'config.php';
                $orgQuery = "SELECT COUNT(req.reqID) AS NumberofRequests
                FROM tbl_requests AS req
                WHERE currentOffice = '$userID'";

                $orgResult = mysqli_query($conn, $orgQuery);

                while ($rowOrg = mysqli_fetch_assoc($orgResult)) {
                    echo '<span class="notif">' . $rowOrg['NumberofRequests'] . '</span>';
                }
                ?>
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
                        if (isset($_SESSION['designation'])) {
                            $designation = $_SESSION['designation'];
                            echo "<span class = welcom >Welcome Back,</span><br><p><b> $designation!</b></p>";
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
                            <a class="nav-link text text-left " id="showForm2">
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
                                <i class="fas fa-calendar"></i> Archive
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text text-left" id="showForm5">
                                <i class="fas fa-user"></i> Account
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text text-left" href="login.php">
                                <i class="fas fa-sign-out-alt"></i><u>Logout</u>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>


            <div class="content" style="flex: 1; padding: 20px;">

                <div id="form1" style="display: block;">
                    <h2 class="form-title">Dashboard</h2>
                    <div class="row">
                        <div class="col-md-7" style="padding:10px;">
                            <div class="card text-bg-white mb-3 shadow-sm"
                                style="max-width:100%; height:115px; margin-left:20px">
                                <div class="card-header"><strong>Welcome!</strong></div>
                                <div class="card-body">
                                    <?php
                                    if (isset($_SESSION['designation'])) {
                                        $designation = $_SESSION['designation'];
                                        echo '<p class="card-text">Good day <b>', $designation, '!</b> Welcome to Event Tracking System of Group 7 BSIT BA-3101</p>';
                                    }
                                    ?>

                                </div>
                            </div>
                            <div class="db-container shadow-sm" style=" margin-left:20px">
                                <div class="db card-header"><strong>Dashboard</strong></div>
                                <table id="" class="table table-striped" style="width:100%; ">
                                    <thead>
                                        <tr>
                                            <th class="text-center">Event Name</th>
                                            <th class="text-center">Event Date</th>
                                            <th class="text-center">Organization</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-center">
                                        <?php
                                        include 'config.php';
                                        while ($rowReq = mysqli_fetch_assoc($result)) {
                                            echo "<tr>";
                                            echo "<td>{$rowReq['reqEventName']}</td>";
                                            echo "<td>{$rowReq['reqEventDate']}</td>";
                                            echo "<td>{$rowReq['userName']}</td>";
                                            echo "</tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="col-md-5" style="padding:10px">
                            <div class="card text-bg-white mb-3 shadow-sm" style="max-width: 100%; height:115px">
                                <div class="card-header"><strong>Number of Requests</strong></div>
                                <div class="card-body">
                                    <?php
                                    include 'config.php';
                                    $orgQuery = "SELECT COUNT(req.reqID) AS NumberofRequests
                                            FROM tbl_requests AS req
                                            WHERE currentOffice = '$userID'";

                                    $orgResult = mysqli_query($conn, $orgQuery);

                                    while ($rowOrg = mysqli_fetch_assoc($orgResult)) {
                                        echo '<td><ab>' . $rowOrg['NumberofRequests'] . '</ab></td>';
                                    }
                                    ?>
                                </div>

                            </div>
                            <div class="pieChart card text-bg-white mb-3 shadow-sm">
                                <div class="card-header"><strong>Overview</strong></div>
                                <div class="card-body">
                                    <div class="overview" style="height:400px">
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
                        echo '<table border="0" id="orgTable" class="display">';
                        echo '<thead>';
                        echo '<tr>';
                        echo '<th>Organization Name</th>';
                        echo '<th>Department</th>';
                        echo '<th>Number of Activities</th>';
                        echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';

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

                <script>
                    $(document).ready(function () {
                        $('#orgTable').DataTable();
                    });
                </script>

                <div id="form3" style="display: none;">
                    <h2 class="form-title">Requests</h2>
                    <div class="tbl-container">
                        <table class="bordered stripe" id="dataTable" style="width:100%">
                            <thead>
                                <tr>
                                    <th class="text-center">Request ID</th>
                                    <th class="text-center">Event Name</th>
                                    <th class="text-center">Letter</th>
                                    <th class="text-center">Event Date</th>
                                    <th class="text-center">Request Sender</th>
                                    <th class="text-center">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($rowC = mysqli_fetch_assoc($resultC)) {
                                    echo "<tr>";
                                    echo "<td>{$rowC['reqID']}</td>";
                                    echo "<td>{$rowC['reqEventName']}</td>";
                                    echo "<td><a href='view_pdf.php?reqID={$rowC['reqID']}' target='_blank' class='btn btn-glass btn-complement'>View Letter</a></td>";
                                    echo "<td>{$rowC['reqEventDate']}</td>";
                                    echo "<td>{$rowC['userName']}</td>";
                                    echo "<td>
                                        <form method='post'>
                                            <input type='hidden' name='reqID' value='{$rowC['reqID']}'>
                                            <input type='hidden' name='userID' value='{$rowC['userID']}'>
                                            <button type='submit' name='approve' class='action-button approve-button' onclick='return confirm(\"Are you sure you want to approve?\")'>Approve</button>
                                            <button type='submit' name='decline' class='action-button decline-button' onclick='return confirm(\"Are you sure you want to decline?\")'>Decline</button>
                                        </form>
                                    </td>";
                                    echo "</tr>";
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>

                <?php

                function approveRequest($conn, $reqID, $orgID)
                {
                    // Get the current userID
                    $officeID = $_SESSION['officeAccID'];

                    $designation = $_SESSION['designation'];
                    if ($designation == 'Program Chair') {
                        $nextDesignation = 'Dean';
                    } elseif ($designation == 'Dean') {
                        $nextDesignation = 'OSO Head';
                    } elseif ($designation == 'OSO Head') {
                        $nextDesignation = 'OVCAA';
                    } elseif ($designation == 'OVCAA') {
                        $nextDesignation = 'Chancellor';
                    } elseif ($designation == 'Chancellor') {
                        $nextDesignation = 'Approved';
                    }

                    // Update tbl_requests
                    $updateQuery = "UPDATE tbl_requests SET currentOffice = '{$nextDesignation}' WHERE reqID = '{$reqID}'";
                    if (!mysqli_query($conn, $updateQuery)) {
                        echo "Update Error: " . mysqli_error($conn);
                    }

                    $insertQuery = "INSERT INTO tbl_reqhistory (reqStatus, statusDate, orgID, reqID, officeID) VALUES (?, NOW(), ?, ?, ?)";

                    $stmt = mysqli_prepare($conn, $insertQuery);
                    $status = 'Approved';
                    mysqli_stmt_bind_param($stmt, 'siii', $status, $orgID, $reqID, $officeID);

                    if (!mysqli_stmt_execute($stmt)) {
                        echo "Insert Error: " . mysqli_stmt_error($stmt);
                    }

                    mysqli_stmt_close($stmt);
                }
                function declineRequest($conn, $reqID, $orgID)
                {
                    $officeID = $_SESSION['officeAccID'];

                    $updateQuery = "UPDATE tbl_requests SET currentOffice = 'Declined' WHERE reqID = '{$reqID}'";
                    if (!mysqli_query($conn, $updateQuery)) {
                        echo "Update Error: " . mysqli_error($conn);
                    }

                    $insertQuery = "INSERT INTO tbl_reqhistory (reqStatus, statusDate, orgID, reqID, officeID) VALUES (?, NOW(), ?, ?, ?)";

                    $stmt = mysqli_prepare($conn, $insertQuery);
                    $status = 'Declined';
                    mysqli_stmt_bind_param($stmt, 'siii', $status, $orgID, $reqID, $officeID);

                    if (!mysqli_stmt_execute($stmt)) {
                        echo "Insert Error: " . mysqli_stmt_error($stmt);
                    }

                    mysqli_stmt_close($stmt);
                }

                ?>

                <script>
                    $(document).ready(function () {
                        $('#dataTable').DataTable();
                    });
                </script>



                <div id="form4" style="display: none;">
                    <h2 class="form-title">Archive</h2>
                    <div class="tbl-container">
                        <table class="bordered stripe" id="dataTableArchive" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Request ID</th>
                                    <th>Event Name</th>
                                    <th>Status</th>
                                    <th>Date</th>
                                    <th>OrgID</th>
                                    <th>Organization</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $officeID = $_SESSION['officeAccID'];
                                // Archive
                                $queryArch = "SELECT rh.reqID, rh.reqStatus, rh.statusDate, rh.orgID, r.reqEventName, r.currentOffice, ac.userName
                                FROM tbl_reqhistory rh
                                JOIN tbl_requests r ON rh.reqID = r.reqID
                                JOIN tbl_account ac ON rh.orgID = ac.userID
                                WHERE rh.officeID = ? AND (rh.reqStatus = 'Approved' OR rh.reqStatus = 'Declined')
                                ORDER BY reqID DESC ";
                                $stmtArch = mysqli_prepare($conn, $queryArch);
                                mysqli_stmt_bind_param($stmtArch, "s", $officeID);
                                mysqli_stmt_execute($stmtArch);
                                $resultArch = mysqli_stmt_get_result($stmtArch);
                                include 'config.php';

                                while ($rowArch = mysqli_fetch_assoc($resultArch)) {
                                    echo "<tr>";
                                    echo "<td>{$rowArch['reqID']}</td>";
                                    echo "<td>{$rowArch['reqEventName']}</td>";
                                    echo "<td>{$rowArch['reqStatus']}</td>";
                                    echo "<td>{$rowArch['statusDate']}</td>";
                                    echo "<td>{$rowArch['orgID']}</td>";
                                    echo "<td>{$rowArch['userName']}</td>";
                                    echo "</tr>";
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>
                <script>
                    $(document).ready(function () {
                        $('#dataTableArchive').DataTable();
                    });
                </script>

                <div id="form5" style="display: none;">
                    <h2 class="form-title">Account</h2>
                    <div class="tbl-container">

                        <div class="acc-container">
                            <p><strong>Personal Information </strong></p>
                            <div class="user-image-container">
                                <?php
                                if (!empty($userImgBase64)) {
                                    echo '<img src="' . $accImgPath . '" alt="Profile Image" class="user-img">';
                                } else {
                                    echo '<img src="default_profile_image.png" alt="Default Image" class="user-img">';
                                }
                                ?>
                            </div>
                            <div class="account-photo-label">Account Photo</div><br>

                            <style>
                                .user-image-container {
                                    text-align: center;
                                    margin: auto;
                                    margin-top: 0;
                                    margin-bottom: 20px;
                                    width: 230px;
                                    height: 230px;
                                    border: 5px solid #a21a1e;
                                    border-radius: 50%;
                                    overflow: hidden;
                                    display: flex;
                                    justify-content: center;
                                    align-items: center;
                                    padding: 0;
                                    object-fit: cover;

                                }

                                .user-img {
                                    border-radius: 50%;
                                    width: 200px;
                                    height: 200px;
                                    object-fit: cover;
                                }

                                .account-photo-label {
                                    text-align: center;
                                }
                            </style>
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
                                    reach out to the Office of Student
                                    Organization for assistance.</p>
                                <span class="sub-email">Email: ict.lipa@g.batstate-u.edu.ph</span>
                            </div>
                        </div>
                        <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
                        <script type="text/javascript">
                            google.charts.load('current', {
                                'packages': ['corechart']
                            });
                            google.charts.setOnLoadCallback(drawChart);

                            function drawChart() {
                                <?php
                                include('config.php');
                                $officeID = $_SESSION['officeAccID'];


                                $queryPie = "SELECT reqStatus, COUNT(reqStatus) as count FROM tbl_reqhistory WHERE officeID = '$officeID' GROUP BY reqStatus";
                                $resultPie = mysqli_query($conn, $queryPie);

                                $chartData = [['Status', 'Count']];
                                while ($rowPie = mysqli_fetch_assoc($resultPie)) {
                                    $chartData[] = [$rowPie['reqStatus'], (int) $rowPie['count']];
                                }
                                ?>
                                var data = google.visualization.arrayToDataTable(<?php echo json_encode($chartData); ?>);

                                var options = {
                                    title: 'Event Approval Overview',
                                    width: '80%',
                                    height: '400',
                                    legend: {
                                        position: 'right'
                                    }
                                };

                                var chart = new google.visualization.PieChart(document.getElementById('piechart'));
                                chart.draw(data, options);
                            }
                        </script>

                        <script>
                            const navLinks = document.querySelectorAll('.nav-link');

                            function handleLinkClick(event) {
                                navLinks.forEach(link => link.classList.remove('active-link'));

                                event.target.classList.add('active-link');
                            }

                            navLinks.forEach(link => {
                                link.addEventListener('click', handleLinkClick);
                            });

                            $(document).ready(function () {
                                $('#dataTable').DataTable();
                                $('#dataTableArchive').DataTable();
                                $('#orgTable').DataTable();
                                $('#dataTablereq').DataTable();
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
                                updateAccountInformation("<?php echo $officeDesig; ?>", "<?php echo $CuserDept; ?>", "<?php echo $CuserEmail; ?>");
                            });
                        </script>
</body>

</html>