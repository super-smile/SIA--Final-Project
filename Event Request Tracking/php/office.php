<?php
session_start();
//update
include 'config.php';
//Account Information
if (isset($_SESSION['userName'])) {
    $CurrentUser = $_SESSION['userName'];

    $query = "SELECT userName, userDept, userEmail FROM tbl_account WHERE userName = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, "s", $CurrentUser);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $dbUserName, $CuserDept, $CuserEmail);
    mysqli_stmt_fetch($stmt);

    $userType = $_SESSION['userType'];
    $userID = $_SESSION['userID'];
} else {
    header('location: login.php');
}

include 'config.php';

$userID = $_SESSION['userID'];
$query = "";
if ($userID == 1){
    $query = "SELECT * FROM tbl_requests WHERE currentOffice = 'Program Chair'";
}elseif ($userID == 2){
    $query = "SELECT * FROM tbl_requests WHERE currentOffice = 'Dean'";
}elseif ($userID == 3){
    $query = "SELECT * FROM tbl_requests WHERE currentOffice = 'OSO Head'";
}elseif ($userID == 4){
    $query = "SELECT * FROM tbl_requests WHERE currentOffice = 'OVCAA'";
}elseif ($userID == 5){
    $query = "SELECT * FROM tbl_requests WHERE currentOffice = 'Chancellor'";
}
$stmt = mysqli_prepare($conn, $query);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$queryArch = "SELECT * FROM tbl_reqhistory WHERE reqStatus = 'Approved'";
$stmtArch = mysqli_prepare($conn, $queryArch);
mysqli_stmt_execute($stmtArch);
$resultArch = mysqli_stmt_get_result($stmtArch);
include 'HTML/office.html';

$queryImg = "SELECT userImg FROM tbl_account WHERE userName = ?";
$stmtImg = mysqli_prepare($conn, $queryImg);
mysqli_stmt_bind_param($stmtImg, "s", $CurrentUser);
mysqli_stmt_execute($stmtImg);
mysqli_stmt_bind_result($stmtImg, $userImg);
mysqli_stmt_fetch($stmtImg);

// Convert the binary image data to base64
$userImgBase64 = base64_encode($userImg);
?>

</head>

<body style="background:#F3F3F3;">
    <div class="header d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center">
            <img src="logoo.png" alt="Logo" width="45" style="padding: 5px;" class="img-fluid">
            <div class="header-text">
                <p style="font-size: 11px; font-weight: 800; margin: 0;">Event Tracking System</p>
                <span style="font-size: 9px;">Office of the Student Organizations</span>

                <?php
                if ($userType == 'organization') {
                    echo '<a href="letter.php" class="upload-button" id="uploadLetter">Upload a letter</a>';
                }
                ?>

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



            <!-- Content Area -->
            <div class="content" style="flex: 1; padding: 20px;">

                <div id="form1" style="display: block;">
                    <h2 class="form-title">Dashboard</h2>
                    <div class="row">
                        <div class="col-md-8 mb-4">
                            <div class="card">
                                <div class="card-body">
                                    <form id="formReq">
                                        <h2>Requests</h2>
                                        <!-- req -->
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <form id="formorganizations">
                                        <h2>Number of Requests</h2>
                                        <?php
                                        include 'config.php';
                                        $orgQuery = "SELECT COUNT(reqhist.reqID) AS NumberofRequests
                                            FROM tbl_account AS acc
                                            LEFT JOIN tbl_reqhistory AS reqhist ON acc.userID = reqhist.orgID
                                            GROUP BY acc.userID";

                                        $orgResult = mysqli_query($conn, $orgQuery);
                                        //engk pa ito
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
                                        <!-- pie chart here -->
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="form2" style="display: none;">
                    <h2 class="form-title">Organizations</h2>
                    <?php
                    include 'config.php';

                    $orgQuery = "SELECT acc.userName, acc.userDept, COUNT(reqhist.reqID) AS numActivities
                            FROM tbl_account AS acc
                            LEFT JOIN tbl_reqhistory AS reqhist ON acc.userID = reqhist.orgID
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


                <div id="form3">
                    <h2 class="form-title">Requests</h2>
                    <table class="bordered stripe" id="dataTable" style="width:100%">
                        <thead>
                            <tr>
                                <th>Request ID</th>
                                <th>Event Name</th>
                                <th>Letter</th>
                                <th>Event Date</th>
                                <th>Request Sender</th>
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
                                echo "<td>{$rowArch['userID']}</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <div id="form4" style="display: none;">
                    <h2 class="form-title">Archive</h2>
                    <table class="bordered stripe" id="dataTableArchive" style="width:100%">
                        <thead>
                            <tr>
                                <!--<th>Reference Number</th>-->
                                <th>Request ID</th>
                                <th>Approval Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include 'config.php';
                            while ($rowArch = mysqli_fetch_assoc($resultArch)) {
                                echo "<tr>";
                                // refnum
                                echo "<td>{$rowArch['reqID']}</td>";
                                echo "<td>{$rowArch['statusDate']}</td>";
                                echo "<td>{$rowArch['reqStatus']}</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <div id="form5" style="display: none;">
                    <h2 class="form-title">Account</h2>
                    <div class="container mt-5 bg-white">

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
                                    reach out to the Office of Student
                                    Organization for assistance.</p>
                                <span class="sub-email">Email: studentorganization.lipa@g.batstate-u.edu.ph</span>
                            </div>
                        </div>

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
                                updateAccountInformation("<?php echo $dbUserName; ?>", "<?php echo $CuserDept; ?>", "<?php echo $CuserEmail; ?>");
                            });
                        </script>
</body>

</html>