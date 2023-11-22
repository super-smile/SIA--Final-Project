<?php
session_start();
include 'config.php';
if (isset($_SESSION['designation'])) {
    $designation = $_SESSION['designation'];

    $query = "SELECT o.designation, o.employeeID, o.officeEmail, o.officeImg, e.department 
              FROM tbl_office o
              JOIN tbempinfo e ON o.employeeID = e.empid
              WHERE o.designation = ?";

    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $designation);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $officeDesig, $CuserDept, $CuserEmail, $employeeID, $department);

    if (mysqli_stmt_fetch($stmt)) {
        $CuserDept = $department;
    } else {
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
          WHERE tr.currentOffice = ?";
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_bind_param($stmt, "s", $userID);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);




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
                        <br><br><br><br><br><br><br>
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
                        <div class="col-md-8 mb-4">
                            <div class="card" style="margin-left: 20px; margin-top: 11px">
                                <div class="card-body">
                                    <form id="formReq">
                                        <h2>Requests</h2>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card mb-4" style="margin-top: 11px">
                                <div class="card-body">
                                    <form id="formorganizations">
                                        <h2>Number of Requests</h2>
                                        <?php
                                        include 'config.php';
                                        $orgQuery = "SELECT COUNT(req.reqID) AS NumberofRequests
                                            FROM tbl_requests AS req
                                            WHERE currentOffice = '$userID'";

                                        $orgResult = mysqli_query($conn, $orgQuery);

                                        while ($rowOrg = mysqli_fetch_assoc($orgResult)) {
                                            echo '<td>' . $rowOrg['NumberofRequests'] . '</td>';
                                        }
                                        ?>
                                    </form>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <form id="formoverview">
                                        <h2>Overview</h2>
                                    </form>
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

                <div id="form3" style="display: none;">
                    <h2 class="form-title">Requests</h2>
                    <div class="tbl-container">
                        <table class="bordered stripe" id="dataTable" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Request ID</th>
                                    <th>Event Name</th>
                                    <th>Letter</th>
                                    <th>Event Date</th>
                                    <th>Request Sender</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                while ($rowArch = mysqli_fetch_assoc($result)) {
                                    echo "<tr>";
                                    echo "<td>{$rowArch['reqID']}</td>";
                                    echo "<td>{$rowArch['reqEventName']}</td>";
                                    echo "<td><a href='view_pdf.php?reqID={$rowArch['reqID']}' target='_blank'>View Letter</a></td>";
                                    echo "<td>{$rowArch['reqEventDate']}</td>";
                                    echo "<td>{$rowArch['userName']}</td>";
                                    echo "<td>
                        <form method='post'>
                            <input type='hidden' name='reqID' value='{$rowArch['reqID']}'>
                            <input type='hidden' name='userID' value='{$rowArch['userID']}'>
                            <button type='submit' name='approve' class='action-button approve-button'>Approve</button>
                            <button type='submit' name='decline' class='action-button decline-button'>Decline</button>        
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
                                $queryArch = "SELECT rh.reqID, rh.reqStatus, rh.statusDate, rh.orgID, r.reqEventName, r.currentOffice
                                FROM tbl_reqhistory rh
                                JOIN tbl_requests r ON rh.reqID = r.reqID
                                WHERE rh.officeID = ? AND (rh.reqStatus = 'Approved' OR rh.reqStatus = 'Declined')";
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
                                    echo "<td>{$rowArch['currentOffice']}</td>";
                                    echo "</tr>";
                                }
                                ?>

                            </tbody>
                        </table>
                    </div>
                </div>

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
                                <span class="sub-email">Email: studentorganization.lipa@g.batstate-u.edu.ph</span>
                            </div>
                        </div>

                        <script>
                            const navLinks = document.querySelectorAll('.nav-link');

                            function handleLinkClick(event) {
                                navLinks.forEach(link => link.classList.remove('active-link'));

                                event.target.classList.add('active-link');
                            }

                            navLinks.forEach(link => {
                                link.addEventListener('click', handleLinkClick);
                            });

                            $(document).ready(function() {
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

                            button1.addEventListener("click", function() {
                                form1.style.display = "block";
                                form2.style.display = "none";
                                form3.style.display = "none";
                                form4.style.display = "none";
                                form5.style.display = "none";
                            });

                            button2.addEventListener("click", function() {
                                form1.style.display = "none";
                                form2.style.display = "block";
                                form3.style.display = "none";
                                form4.style.display = "none";
                                form5.style.display = "none";
                            });

                            button3.addEventListener("click", function() {
                                form1.style.display = "none";
                                form2.style.display = "none";
                                form3.style.display = "block";
                                form4.style.display = "none";
                                form5.style.display = "none";
                            });

                            button4.addEventListener("click", function() {
                                form1.style.display = "none";
                                form2.style.display = "none";
                                form3.style.display = "none";
                                form4.style.display = "block";
                                form5.style.display = "none";
                            });
                            button5.addEventListener("click", function() {
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