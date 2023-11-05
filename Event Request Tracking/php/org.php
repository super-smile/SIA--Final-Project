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

include 'HTML/org.html'

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
        <div class="notification-icon position-relative">
            <i class="far fa-bell fa-lg" style="color: #cf4444;"></i>
            <span class="position-absolute top-0 start-99 translate-middle badge rounded-pill bg-danger">
                <span class="notif">10</span>
                <span class="visually-hidden">unread messages</span>
            </span>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 p-0" style="background:#a21a1e; color: white;">
                <div class="image-container p-1">
                    <img src="logoo.png" alt="Logo" class="img-fluid">
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
                            <i class="fas fa-tachometer-alt"></i> Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text text-left " id="showForm2">
                            <i class="fas fa-users"></i> Request
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text text-left" id="showForm3">
                            <i class="fas fa-tasks"></i> Archive
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text text-left" id="showForm4">
                            <i class="fas fa-calendar"></i> Account
                        </a>
                    </li>
                    <br><br><br><br><br><br><br>
                    <li class="nav-item">
                        <a class="nav-link text text-left" href="login.php">
                            <i class="fas fa-sign-out-alt"></i> Logout
                        </a>
                    </li>
                </ul>
                <br>
                <br>
            </div>
            <div class="col-md-10 p-4 bg-body-secondary">
                <div id="form1" style="display: block;">
                    <h2 class="form-title">Dashboard</h2>
                    <div class="row">
                        <div class="col-md-7" style="padding:10px">
                            <div class="card text-bg-white mb-5" style="max-width:100%; height:115px">
                                <div class="card-header"><strong>Welcome!</strong></div>
                                <div class="card-body">
                                    <p class="card-text">Lorem ipsum dolor sit amet consectetur adipisicing elit.</p>
                                </div>
                                <div class="card text-bg-white mb-5" style="max-width:100%; height:115px; margin-top:40px;">
                                    <div class="card-header"><strong>Request</strong></div>
                                    <table id="Req" class="table table-striped" style="width:100%">
                                        <br>
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
                            </div>
                        </div>
                        <div class="col-md-5" style="padding:10px">
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
                    <h2 class="form-title">Request</h2>
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

                            dateTimeElement.innerHTML = `${formattedDate} <span style="float: right">${formattedTime}</span>`;
                        }

                        // Call the function to update the date and time initially
                        updateDateTime();

                        // Update the date and time every second (1000 milliseconds)
                        setInterval(updateDateTime, 1000);
                    </script>

                    <div class="horizontal-line" style="width:100%"></div>
                    <table id="Req" class="table table-striped" style="width:100%">
                        <br>
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



                <div id="form3" style="display: none;">
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
                </div>

                <div id="form4" style="display: none;">
                    <h2 style="font-family:'Poppins'; margin:10px 10px 10px 10px"><strong>Account</strong></h2>
                    <div class="container-fluid-account" id="account-container">
                        <h2 class="form-title">Organization Information</h2>
                        <p>Username: <span id="userNameDisplay"></span></p>
                        <p>Department: <span id="userDeptDisplay"></span></p>
                        <p>Email: <span id="userEmailDisplay"></span></p>

                        <div class="container" id="container-assistance">
                            <p>If you find that the provided information is incorrect, please reach out to the Office of Student<br>
                                &emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;Organization for assistance.</p>
                            <p style="margin-left: 175px; font-weight:normal">Email: studentorganization.lipa@g.batstate-u.edu.ph</p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
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

    new DataTable('#Req');
    new DataTable('#Arch');

    function updateAccountInformation(userName, userDept, userEmail) {
        document.getElementById('userNameDisplay').textContent = userName;
        document.getElementById('userDeptDisplay').textContent = userDept;
        document.getElementById('userEmailDisplay').textContent = userEmail;
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