<h1>Office of Students Organization</h1>

<?php
session_start();

include 'config.php';
//Account Information
if (isset($_SESSION['userName'])) {
    $userName = $_SESSION['userName'];
    echo "Welcome Back, $userName!";

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

$queryEvents = "SELECT * FROM tbl_requests";
$stmtEvents = mysqli_prepare($conn, $queryEvents);
mysqli_stmt_execute($stmtEvents);
$resultEvents = mysqli_stmt_get_result($stmtEvents);


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <title>OSO</title>
</head>

<body>


    <h2>Welcome to Event Tracking System</h2>

    <button type="button" class="btn btn-primary" id="showForm1">Dashboard</button>
    <button type="button" class="btn btn-primary" id="showForm2">Organizations</button>
    <button type="button" class="btn btn-primary" id="showForm3">Requests</button>
    <button type="button" class="btn btn-primary" id="showForm4">All Events</button>
    <button type="button" class="btn btn-primary" id="showForm5">Create Account</button>
    <button type="button" class="btn btn-primary" id="showForm6">Account</button>

    <p><a href="login.php">Logout</a></p>

    <form id="form1" style="display: block;">
        <h2>Dashboard</h2>
        <div id="piechart" style="width: 900px; height: 500px;"></div>
    </form>

    <form id="form2" style="display: none;">
        <h2>Organizations</h2>

        <style>
            table {
                text-align: center;
            }

            .bordered {
                padding: 5px;
                border-collapse: collapse;
                border: 1px solid #000;
            }

            .bordered th,
            .bordered td {
                padding: 5px;
                border: 1px solid #000;
            }
        </style>

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
    </form>


    <form id="form3" style="display: none;">
        <h2>Requests</h2>
        <style>
            table {
                text-align: center;
            }

            .bordered {
                padding: 5px;
                border-collapse: collapse;
                border: 1px solid #000;
            }

            .bordered th,
            .bordered td {
                padding: 5px;
                border: 1px solid #000;
            }
        </style>

        <table id="example" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th class="text-center">histID</th>
                    <th class="text-center">reqStatus</th>
                    <th class="text-center">statusDate</th>
                    <th class="text-center">reqDeadline</th>
                    <th class="text-center">userID</th>
                    <th class="text-center">reqID</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include 'config.php';
                while ($rowReq = mysqli_fetch_assoc($resultReq)) {
                    echo "<tr>";
                    echo "<td>{$rowReq['histID']}</td>";
                    echo "<td>{$rowReq['reqStatus']}</td>";
                    echo "<td>{$rowReq['statusDate']}</td>";
                    echo "<td>{$rowReq['reqDeadline']}</td>";
                    echo "<td>{$rowReq['orgID']}</td>";
                    echo '<td><button class="btn btn-primary">Update</button> <button class="btn">Delete</button></td>';
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </form>

    <form id="form4" style="display: none">
        <h2>All Events</h2>

        <style>
            table {
                text-align: center;
            }

            .bordered {
                padding: 5px;
                border-collapse: collapse;
                border: 1px solid #000;
            }

            .bordered th,
            .bordered td {
                padding: 5px;
                border: 1px solid #000;
            }
        </style>

        <table id="AllEvents" class="table table-striped" style="width:100%">
            <thead>
                <tr>
                    <th class="text-center">Request ID</th>
                    <th class="text-center">Event Name</th>
                    <th class="text-center">Event Date</th>
                </tr>

            </thead>
            <tbody>
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
    </form>

    <form id="form5" style="display: none;">
        <h2>Create Account</h2>
        <p>Create New Account <a href="register.php">Here!</a></p>
    </form>

    <form id="form6" style="display: none;">
        <h2>Account</h2>
        <p>Username: <span id="userNameDisplay"></span></p>
        <p>Department: <span id="userDeptDisplay"></span></p>
        <p>Email: <span id="userEmailDisplay"></span></p>
    </form>

</body>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
    google.charts.load('current', { 'packages': ['corechart'] });
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
        // Fetch data from your database using PHP and config.php
        <?php
        // Include your database connection configuration
        include('config.php');

        // Query the database to retrieve data
        $queryPie = "SELECT reqStatus, COUNT(reqStatus) as count FROM tbl_reqhistory GROUP BY reqStatus";
        $resultPie = mysqli_query($conn, $queryPie);

        // Create an empty array for the chart data
        $chartData = [['Status', 'Count']];

        // Loop through the database results and add them to the chart data array
        while ($rowPie = mysqli_fetch_assoc($resultPie)) {
            $chartData[] = [$rowPie['reqStatus'], (int) $rowPie['count']];
        }
        ?>

        var data = google.visualization.arrayToDataTable(<?php echo json_encode($chartData); ?>);

        var options = {
            title: 'Request Status Distribution'
        };

        var chart = new google.visualization.PieChart(document.getElementById('piechart'));

        chart.draw(data, options);
    }
</script>

<script src="https://code.jquery.com/jquery-3.7.0.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
<script>

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