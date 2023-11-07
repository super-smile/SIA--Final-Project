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
    include 'HTML/office.html'
?>

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
                        echo "<span class = welcom ><center>Welcome Back,</span><br><p><b> $userName!</b></p>";
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
                            <i class="fas fa-calendar"></i> Account
                        </a>
                    </li>
                    <br><br><br><br><br><br><br><br>
                    <li class="nav-item">
                        <a class="nav-link text text-left" href="login.php">
                            <i class="fas fa-sign-out-alt"></i><u style="margin-left:2px">Logout</u>
                        </a>
                    </li>
                </ul>
            </div>


        <!-- Content Area -->
        <div class="content" style="flex: 1; padding: 20px;">

        <form id="form1" style="display: block;">
            <h2>Dashboard</h2>

            <!-- Requests  -->
            <div class="card mb-4">
                <div class="card-body">
                    <form id="formReq">
                        <h2>Requests</h2>
                        <!-- req -->
                    </form>
                </div>
            </div>
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
            </form>

            
    <form id="form3">
    <h2>Requests</h2>
    <table class="bordered stripe" id="dataTablereq">
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
    
    <script>
        $(document).ready(function () {
            $('#dataTable').DataTable();

            $('.approve-btn').on('click', function () {
                var requestId = $(this).data('id');
                $.ajax({
                    type: 'POST',
                    url: 'updatestatus.php',
                    data: { reqId: reqId, reqstatus: 'Approved' },
                    success: function (response) {
                        console.log(response);
                        // Refresh DataTable after update
                        $('#dataTable').DataTable().ajax.reload();
                    }
                });
            });

            $('.decline-btn').on('click', function () {
                var requestId = $(this).data('id');
                $.ajax({
                    type: 'POST',
                    url: 'updatestatus.php',
                    data: { reqId: reqId, reqstatus: 'Declined' },
                    success: function (response) {
                        console.log(response);
                        // Refresh DataTable after update
                        $('#dataTable').DataTable().ajax.reload();
                    }
                });
            });
        });
    </script>

    <table class="bordered stripe" id="dataTable" style="width:100%">
        <thead>
            <tr>
                <th>Request ID</th>
                <th>Submission Date</th>
                <th>Current Office</th>
                <th>Request Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include 'config.php';
            // Assuming $result is the result of your query for the specific columns
            while ($rowArch = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>{$rowArch['reqID']}</td>";
                echo "<td>{$rowArch['statusDate']}</td>";
                echo "<td>{$rowArch['officeID']}</td>";
                echo "<td>{$rowArch['reqStatus']}</td>";
                echo "<td>
                        <button class='btn btn-primary approve-btn' data-id='{$rowArch['reqID']}'>Approve</button>
                        <button class='btn btn-danger decline-btn' data-id='{$rowArch['reqID']}'>Decline</button>
                      </td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</form>

 
 
    <form id="form4" style="display: none;">
    <h2>Archive</h2>  
    <table class="bordered stripe" id="dataTableArchive">
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
    </form>

        <form id="form5" style="display: none;"> 
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
                    <p class="sub-title">If you find that the provided information is incorrect, please reach out to the Office of Student
                        Organization for assistance.</p>
                    <span class="sub-email">Email: studentorganization.lipa@g.batstate-u.edu.ph</span>
                </div>
        </div>

    <script>
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
